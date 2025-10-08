import {
  initializeIntellisense,
  refreshIntellisense,
  customSqlHint,
} from './intellisense.js';
import notifier from './notifier.js';

/**
 * TabManager
 *
 * Este módulo é responsável por toda a gestão das abas do editor,
 * incluindo a criação, exclusão e gestão de estado de cada aba (editor, resultados, etc.).
 */
window.TabManager = {
  tabCounter: 0,
  activeTabId: null,
  tabs: {},
  isMaximized: false,
  csrfTokenName: window.csrfTokenName,
  csrfTokenValue: window.csrfTokenValue,

  /**
   * Inicializa o TabManager, cria a primeira aba e anexa os eventos globais.
   */
  init: function () {
    $(window).on('beforeunload', () => {
      this._saveStateImmediately();
    });

    const stateLoaded = this.loadTabsState();

    // Se nenhum estado foi carregado, cria a primeira aba padrão
    if (!stateLoaded) {
      this.addTab();
    }

    // Adiciona o listener para o botão de fixar
    $('#editor-tabs').on('click', '.tab-pin-btn', (e) => {
      e.preventDefault();
      e.stopPropagation();
      const tabLink = $(e.currentTarget).closest('.nav-link');
      const paneId = tabLink.attr('href').substring(1);
      this.pinTab(paneId);
    });

    // Inicializa o SortableJS para reordenar abas
    const tabList = document.getElementById('editor-tabs');
    new Sortable(tabList, {
      animation: 150,
      filter: '#new-tab-btn-container', // Impede que o botão "+" seja arrastado
      onMove: function (evt) {
        const isDraggedPinned = evt.dragged.classList.contains('pinned');
        const isRelatedPinned = evt.related.classList.contains('pinned');

        return isDraggedPinned === isRelatedPinned;
      },
      onEnd: () => {
        this.saveTabsState();
      },
    });

    // O timeout dá tempo para que a UI (Split.js, etc.) se processe completamente.
    setTimeout(() => this.getActiveTab()?.editor.refresh(), 100);

    $('#new-tab-btn').on('click', (e) => {
      e.preventDefault();
      this.addTab();
    });

    // Delegação de eventos para botões de fechar aba
    $('#editor-tabs').on('click', '.tab-close-btn', (e) => {
      e.preventDefault();
      e.stopPropagation();
      const tabLink = $(e.currentTarget).closest('.nav-link');
      const paneId = tabLink.attr('href').substring(1);
      this.closeTab(paneId);
    });

    // Atualiza a aba ativa quando o utilizador clica em uma.
    $('#editor-tabs').on('shown.bs.tab', 'a[data-bs-toggle="tab"]', (e) => {
      this.activeTabId = $(e.target).attr('href').substring(1);
      const activeTab = this.getActiveTab();
      if (activeTab && activeTab.editor) {
        activeTab.editor.refresh();
        activeTab.editor.focus();
      }
      this.saveTabsState();
    });

    // Lógica para renomear abas com duplo clique
    $('#editor-tabs').on('dblclick', '.tab-title', (e) => {
      e.preventDefault();
      const $span = $(e.currentTarget);
      const $tabLink = $span.closest('a.nav-link');
      const paneId = $tabLink.attr('href').substring(1); // Obter o paneId corretamente
      const tab = this.tabs[paneId];

      // Impede o duplo clique se já estiver a editar
      if ($span.find('input').length > 0) {
        return;
      }

      const originalName = $span.text().replace(' *', '');
      const $input = $('<input type="text" class="tab-rename-input">').val(
        originalName
      );

      $span.html($input);
      $input.focus().select();

      const finishEditing = () => {
        const newName = $input.val().trim() || originalName;
        $span.text(newName + (tab.isDirty ? ' *' : '')); // Mantém o indicador '*' se necessário

        // Atualiza o nome no objeto da aba
        if (tab) {
          tab.name = newName;
        }

        // Salva o estado de todas as abas
        this.saveTabsState();
      };

      $input.on('blur', finishEditing);
      $input.on('keydown', (ev) => {
        if (ev.key === 'Enter') {
          $input.blur();
        } else if (ev.key === 'Escape') {
          // Cancela a edição sem salvar
          $input.off('blur'); // Remove o listener para não salvar ao desfocar
          $span.text(originalName + (tab.isDirty ? ' *' : ''));
        }
      });
    });

    // Adiciona o listener global para atalhos de teclado
    $(document).on('keydown', (e) => {
      // Usa e.metaKey para a tecla Command no Mac
      if (e.ctrlKey || e.metaKey) {
        if (e.shiftKey) {
          switch (e.key.toLowerCase()) {
            case 't': // Nova Aba
              e.preventDefault();
              this.addTab();
              break;
            case 'n': // Próxima Aba
              e.preventDefault();
              this.nextTab();
              break;
            case 'p': // Aba Anterior
              e.preventDefault();
              this.previousTab();
              break;
            case 'q': // Fechar Aba
              e.preventDefault();
              if (this.activeTabId) {
                this.closeTab(this.activeTabId);
              }
              break;
          }
        }
      }
    });
  },

  /**
   * Dispara o salvamento do estado das abas com um debounce para otimizar o desempenho.
   */
  saveTabsState: function () {
    if (this.saveTimeout) clearTimeout(this.saveTimeout);
    this.saveTimeout = setTimeout(() => {
      this._saveStateImmediately();
    }, 500);
  },

  /**
   * Guarda o estado atual de todas as abas imediatamente no localStorage.
   * @private
   */
  _saveStateImmediately: function () {
    const orderedTabIds = [];
    $('#editor-tabs .nav-item')
      .not('#new-tab-btn-container')
      .each(
        function () {
          const paneId = $(this).find('a.nav-link').attr('href').substring(1);
          if (paneId && this.tabs.hasOwnProperty(paneId)) {
            orderedTabIds.push(paneId);
          }
        }.bind(this)
      );

    if (orderedTabIds.length === 0) {
      localStorage.removeItem('querysphere_tabs_state');
      return;
    }

    const stateToSave = orderedTabIds.map((paneId) => {
      const tab = this.tabs[paneId];
      return {
        name: tab.name,
        sql: tab.editor.getValue(),
        isDirty: tab.isDirty,
        isPinned: tab.isPinned,
        history: tab.history,
      };
    });

    localStorage.setItem('querysphere_tabs_state', JSON.stringify(stateToSave));
  },

  /**
   * Adiciona uma nova aba ao editor. Aceita um estado inicial para restaurar abas.
   * @param {object} initialState - Opcional. Contém { name, sql, isDirty }.
   */
  addTab: function (
    initialState = { name: null, sql: '', isDirty: false, isPinned: false }
  ) {
    this.tabCounter++;
    const paneId = `pane-${this.tabCounter}`;
    const tabTitle = initialState.name || `Query ${this.tabCounter}`;

    const tabLink = $(`
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="tab-${paneId}-link" data-bs-toggle="tab" href="#${paneId}" role="tab">
                <span class="tab-title" title="Dê um duplo clique para renomear">${tabTitle}</span>
                <button type="button" class="tab-pin-btn" title="Fixar Aba"><i class="fa-solid fa-thumb-tack"></i></button>
                <button type="button" class="tab-close-btn" aria-label="Close">&times;</button>
            </a>
        </li>
    `);

    if (initialState.isPinned) {
      tabLink.addClass('pinned');
    }

    $('#new-tab-btn-container').before(tabLink);

    const template = document.getElementById('editor-tab-template');
    const newPane = $(template.content.cloneNode(true).firstElementChild);
    newPane.attr('id', paneId).addClass('h-100');
    $('#editor-panes').append(newPane);

    this.initTab(paneId, tabTitle, initialState);

    if (initialState.isPinned) {
      this._reorderTabs();
    }

    new bootstrap.Tab(tabLink.find('a')[0]).show();
  },

  /**
   * Inicializa os componentes de uma nova aba (editor, split.js, eventos).
   * @param {string} paneId - O ID do painel da aba.
   * @param {string} tabTitle - O título inicial da aba.
   * @param {object} initialState - Contém o estado a ser restaurado.
   */
  initTab: function (paneId, tabTitle, initialState) {
    const $pane = $(`#${paneId}`);
    const editor = CodeMirror.fromTextArea($pane.find('.query-editor')[0], {
      lineNumbers: true,
      mode: DB_TYPE === 'mysql' ? 'text/x-mysql' : 'text/x-mssql',
      theme: document.body.classList.contains('light-theme')
        ? 'default'
        : 'material-darker',
      matchBrackets: true,
      autoCloseBrackets: true,
      styleActiveLine: true,
      indentWithTabs: true,
      smartIndent: true,
      extraKeys: {
        'Ctrl-Space': 'autocomplete',
        'Alt-Space': 'autocomplete',
        F5: () => $pane.find('.execute-query-btn').trigger('click'),
        'Ctrl-Enter': () => $pane.find('.execute-query-btn').trigger('click'),
        'Ctrl-F': 'findPersistent',
        'Ctrl-H': 'replace',
        F3: 'findNext',
        'Shift-F3': 'findPrev',
      },
      hintOptions: {
        hint: customSqlHint,
        schemaData: {},
      },
    });
    editor.setSize('100%', '100%');
    editor.setValue(initialState.sql || '');

    const split = Split(
      [`#${paneId} .query-editor-panel`, `#${paneId} .results-panel`],
      {
        sizes: [65, 35],
        minSize: 100,
        gutterSize: 7,
        direction: 'vertical',
        cursor: 'row-resize',
        onDragEnd: () => editor.refresh(),
      }
    );

    this.tabs[paneId] = {
      id: paneId,
      name: tabTitle,
      editor: editor,
      split: split,
      isDirty: initialState.isDirty || false,
      isPinned: initialState.isPinned || false,
      savedSql: initialState.sql || '', // Armazena o estado "limpo"
      history: initialState.history || [],
      lastResultData: null,
      currentSql: '',
      resultsDataTable: null,
      myChart: null,
      editableGrid: {
        enabled: false,
        pkColumn: null,
        dbName: null,
        schemaName: null,
        tableName: null,
        changedData: {},
      },
    };

    if (this.tabs[paneId].isDirty) {
      const $tabTitle = $(`#tab-${paneId}-link .tab-title`);
      $tabTitle.text($tabTitle.text() + ' *');
    }

    editor.on('change', () => {
      this._updateDirtyState(paneId);
      this.saveTabsState();
    });

    editor.on('inputRead', function (instance, change) {
      if (instance.state.completionActive) {
        return;
      }
      const token = instance.getTokenAt(instance.getCursor());
      if (token.string.length > 0) {
        CodeMirror.commands.autocomplete(instance, null, {
          completeSingle: false,
        });
      }
    });

    this.attachTabEvents(paneId);
    initializeIntellisense(this.tabs[paneId]);
  },

  /**
   * Navega para a próxima aba (à direita).
   */
  nextTab: function () {
    const $tabs = $('#editor-tabs .nav-item').not('#new-tab-btn-container');
    const $active = $('#editor-tabs .nav-item .nav-link.active').parent();
    let $next = $active.nextAll().not('#new-tab-btn-container').first();

    if (!$next.length) {
      $next = $tabs.first(); // Volta para a primeira
    }

    if ($next.length) {
      new bootstrap.Tab($next.find('a')[0]).show();
    }
  },

  /**
   * Navega para a aba anterior (à esquerda).
   */
  previousTab: function () {
    const $tabs = $('#editor-tabs .nav-item').not('#new-tab-btn-container');
    const $active = $('#editor-tabs .nav-item .nav-link.active').parent();
    let $prev = $active.prev();

    if (!$prev.length) {
      $prev = $tabs.last(); // Vai para a última
    }

    if ($prev.length) {
      new bootstrap.Tab($prev.find('a')[0]).show();
    }
  },

  /**
   * Abre uma nova aba dedicada para visualização e edição de dados de uma tabela.
   * @param {object} nodeData - Os dados do nó da árvore (tabela).
   */
  openDataViewerTab: function (nodeData) {
    let sql;
    const limit = 200;
    const tableName =
      DB_TYPE === 'mysql'
        ? `\`${nodeData.db}\`.\`${nodeData.table}\``
        : `[${nodeData.db}].[${nodeData.schema}].[${nodeData.table}]`;

    if (DB_TYPE === 'mysql') {
      sql = `SELECT * FROM ${tableName}\nLIMIT ${limit};`;
    } else {
      sql = `SELECT TOP ${limit} * FROM ${tableName};`;
    }

    this.addTab({ name: nodeData.table, sql: sql, isDirty: false });
    const newTabId = this.activeTabId;
    this.executeQuery(newTabId);
  },

  /**
   * Guarda o estado de todas as abas abertas (nome e conteúdo) no localStorage.
   */
  saveTabsState: function () {
    // Usa um debounce para evitar salvar em excesso a cada tecla pressionada
    if (this.saveTimeout) clearTimeout(this.saveTimeout);
    this.saveTimeout = setTimeout(() => {
      const stateToSave = Object.values(this.tabs).map((tab) => ({
        name: tab.name,
        sql: tab.editor.getValue(),
        isDirty: tab.isDirty,
        isPinned: tab.isPinned,
      }));
      localStorage.setItem(
        'querysphere_tabs_state',
        JSON.stringify(stateToSave)
      );
    }, 500); // Salva 500ms após a última alteração
  },

  /**
   * Carrega o estado das abas a partir do localStorage.
   */
  loadTabsState: function () {
    const savedState = localStorage.getItem('querysphere_tabs_state');
    if (savedState) {
      try {
        const tabsToRestore = JSON.parse(savedState);
        if (tabsToRestore && tabsToRestore.length > 0) {
          tabsToRestore.forEach((tabState) => {
            this.addTab(tabState);
          });
          this._reorderTabs();
          return true;
        }
      } catch (e) {
        console.error('Failed to parse saved tab state:', e);
        localStorage.removeItem('querysphere_tabs_state');
      }
    }
    return false;
  },

  /**
   * Alterna o estado de "fixado" de uma aba e reordena a lista.
   * @param {string} paneId O ID da aba a fixar/desafixar.
   */
  pinTab: function (paneId) {
    const tab = this.tabs[paneId];
    if (!tab) return;

    tab.isPinned = !tab.isPinned;
    const $tabListItem = $(`#tab-${paneId}-link`).closest('li');
    const $pinIcon = $tabListItem.find('.tab-pin-btn i');

    $tabListItem.toggleClass('pinned', tab.isPinned);

    if (tab.isPinned) {
      $pinIcon.removeClass('fa-thumb-tack').addClass('fa-thumbtack-slash');
    } else {
      $pinIcon.removeClass('fa-thumbtack-slash').addClass('fa-thumb-tack');
    }

    this._reorderTabs();
    this.saveTabsState();
  },

  /**
   * Reordena as abas no DOM, movendo todas as abas afixadas para o início.
   * @private
   */
  _reorderTabs: function () {
    const $tabsContainer = $('#editor-tabs');
    const $pinnedTabs = $tabsContainer.find('.nav-item.pinned');
    $tabsContainer.prepend($pinnedTabs);
  },

  /**
   * Salva o script da aba atual no armazenamento local do navegador.
   */
  saveScript: function (paneId) {
    const tab = this.tabs[paneId];
    if (!tab) return;

    const sql = tab.editor.getValue().trim();
    if (!sql) {
      notifier.show(LANG.empty_alert, 'warning');
      return;
    }

    const currentTabName = $(`#tab-${paneId}-link .tab-title`)
      .text()
      .replace(' *', '')
      .trim();
    const scriptName = prompt(
      LANG.prompt_name,
      currentTabName || LANG.default_name
    );

    if (scriptName) {
      try {
        const scripts = getSavedScripts();
        scripts.unshift({ name: scriptName, sql: sql });
        localStorage.setItem('querysphere_scripts', JSON.stringify(scripts));

        renderSavedScripts();
        notifier.show(`Script '${scriptName}' salvo com sucesso.`, 'success');

        // Atualiza o estado salvo e o indicador visual
        tab.savedSql = sql;
        this._updateDirtyState(paneId);
      } catch (e) {
        notifier.show(LANG.error_saving, 'error');
        console.error('Failed to save script:', e);
      }
    }
  },

  /**
   * Compartilha o script da aba atual, enviando-o para o servidor.
   */
  shareScript: function (paneId) {
    const tab = this.tabs[paneId];
    if (!tab) return;
    const sql = tab.editor.getValue().trim();
    if (!sql) {
      notifier.show(LANG.empty_shared_alert, 'warning');
      return;
    }
    const scriptName = prompt(
      LANG.prompt_shared_name,
      LANG.shared_default_name
    );
    if (!scriptName) return;
    const authorName = prompt(LANG.prompt_author, LANG.author_default);
    if (!authorName) return;

    $.ajax({
      url: site_url + 'api/shared-queries',
      method: 'POST',
      data: {
        sql: sql,
        name: scriptName,
        author: authorName,
        [this.csrfTokenName]: this.csrfTokenValue,
      },
      success: () => {
        notifier.show(LANG.share_success.replace('{0}', scriptName), 'success');
        renderSharedScripts();

        // Atualiza o estado salvo e o indicador visual
        tab.savedSql = sql;
        this._updateDirtyState(paneId);
      },
      error: () => {
        notifier.show(LANG.share_fail, 'error');
      },
    });
  },

  toggleEditorMaximize: function () {
    const activeTab = this.getActiveTab();
    if (!activeTab) return;

    // Alterna o estado
    this.isMaximized = !this.isMaximized;

    // Adiciona ou remove a classe no body. O CSS fará o resto.
    $('body').toggleClass('editor-maximized', this.isMaximized);

    const $allButtons = $('.maximize-editor-btn');
    const $allIcons = $allButtons.find('i');

    if (this.isMaximized) {
      // Atualiza o ícone e a dica de todos os botões de maximizar
      $allIcons.removeClass('fa-expand').addClass('fa-compress');
      $allButtons.prop('title', 'Restaurar Layout');
    } else {
      // Restaura o ícone e a dica
      $allIcons.removeClass('fa-compress').addClass('fa-expand');
      $allButtons.prop('title', 'Maximizar Editor');
    }

    // Força o redimensionamento do editor após a transição do CSS
    // O timeout é importante para garantir que o DOM foi atualizado
    setTimeout(() => {
      window.dispatchEvent(new Event('resize'));
      if (this.getActiveTab()) {
        this.getActiveTab().editor.refresh();
      }
    }, 150); // Um pequeno delay para a transição do CSS
  },

  /**
   * Anexa todos os manipuladores de eventos para os botões de uma aba específica.
   */
  attachTabEvents: function (paneId) {
    const $pane = $(`#${paneId}`);
    const tab = this.tabs[paneId];
    const $resultsPanel = $pane.find('.results-panel');
    const $historyDropdown = $pane.find('.tab-history-btn');
    const $historyMenu = $pane.find('.tab-history-dropdown');

    $historyDropdown.on('show.bs.dropdown', () => {
      $historyMenu.empty(); // Limpa itens antigos
      if (tab.history && tab.history.length > 0) {
        tab.history.forEach((query) => {
          const shortQuery =
            query.length > 100 ? query.substring(0, 100) + '...' : query;
          const $item = $(
            `<li><a class="dropdown-item" href="#" title="${escapeHtml(query)}">${escapeHtml(shortQuery)}</a></li>`
          );

          $item.on('click', (e) => {
            e.preventDefault();
            tab.editor.setValue(query);
          });

          $historyMenu.append($item);
        });
      } else {
        $historyMenu.append(
          `<li><span class="dropdown-item-text text-muted">${LANG.no_tab_history}</span></li>`
        );
      }
    });

    // Mapeamento de botões para funções
    $pane
      .find('.execute-query-btn')
      .on('click', () => this.executeQuery(paneId));
    $pane
      .find('.explain-query-btn')
      .on('click', () => this.explainQuery(paneId));
    $pane.find('.format-sql-btn').on('click', () => this.formatSql(paneId));
    $pane
      .find('.export-csv-btn')
      .on('click', () => this.exportResult(paneId, 'csv'));
    $pane
      .find('.export-json-btn')
      .on('click', () => this.exportResult(paneId, 'json'));
    $pane.find('.save-script-btn').on('click', () => this.saveScript(paneId));
    $pane.find('.share-script-btn').on('click', () => this.shareScript(paneId));
    $pane
      .find('.save-changes-btn')
      .on('click', () => this.saveGridChanges(paneId));
    $pane
      .find('.maximize-editor-btn')
      .on('click', () => this.toggleEditorMaximize());

    // Eventos do painel de resultados
    $resultsPanel.on('click', '.pagination-prev', () => {
      const result = tab.lastResultData?.results?.[0];
      if (result && result.currentPage > 1)
        this.executeQuery(paneId, result.currentPage - 1);
    });
    $resultsPanel.on('click', '.pagination-next', () => {
      const result = tab.lastResultData?.results?.[0];
      if (result && result.currentPage < result.totalPages)
        this.executeQuery(paneId, result.currentPage + 1);
    });
    $resultsPanel.on(
      'shown.bs.tab',
      '.results-tab-nav button[data-bs-toggle="tab"]',
      (event) => {
        if ($(event.target).parent().hasClass('dynamic-tab')) {
          const resultIndex = $(event.target).attr('id').split('-').pop();
          this.updateChartOptions(paneId, parseInt(resultIndex));
        } else {
          $pane.find('.show-chart-btn').prop('disabled', true);
        }
      }
    );
  },

  /**
   * Atualiza o estado "sujo" (dirty) de uma aba, comparando o conteúdo atual
   * com o último conteúdo salvo e atualizando a UI (adicionando/removendo '*').
   * @param {string} paneId - O ID da aba a ser atualizada.
   * @private
   */
  _updateDirtyState: function (paneId) {
    const tab = this.tabs[paneId];
    if (!tab) return;

    const currentSql = tab.editor.getValue();
    const isNowDirty = currentSql !== tab.savedSql;

    // Se o estado não mudou, não faz nada
    if (isNowDirty === tab.isDirty) {
      return;
    }

    tab.isDirty = isNowDirty;
    const $tabTitle = $(`#tab-${paneId}-link .tab-title`);
    const currentTitle = $tabTitle.text().replace(' *', '');

    if (isNowDirty) {
      $tabTitle.text(currentTitle + ' *');
    } else {
      $tabTitle.text(currentTitle);
    }
  },

  /**
   * Obtém a aba que está atualmente ativa.
   * @returns {object|null} O objeto da aba ativa.
   */
  getActiveTab: function () {
    if (!this.activeTabId) {
      const firstTabLink = $('#editor-tabs .nav-link.active');
      if (firstTabLink.length) {
        this.activeTabId = firstTabLink.attr('href').substring(1);
      }
    }
    return this.tabs[this.activeTabId] || null;
  },
  /**
   * Fecha uma aba específica.
   */
  closeTab: function (paneId) {
    if (Object.keys(this.tabs).length <= 1) {
      notifier.show(LANG.cannot_close_last_tab, 'warning');
      return;
    }

    const $tabLinkContainer = $(
      `#editor-tabs .nav-link[href="#${paneId}"]`
    ).closest('.nav-item');
    const $tabPane = $(`#${paneId}`);

    if (this.activeTabId === paneId) {
      const nextTabLink = $tabLinkContainer
        .next()
        .not('#new-tab-btn-container')
        .find('a');
      const prevTabLink = $tabLinkContainer.prev().find('a');
      if (nextTabLink.length) {
        new bootstrap.Tab(nextTabLink[0]).show();
      } else if (prevTabLink.length) {
        new bootstrap.Tab(prevTabLink[0]).show();
      }
    }

    if (this.tabs[paneId]) {
      this.tabs[paneId].split.destroy();
      if (this.tabs[paneId].resultsDataTable) {
        this.tabs[paneId].resultsDataTable.destroy();
      }
      delete this.tabs[paneId];
    }
    $tabLinkContainer.remove();
    $tabPane.remove();
  },

  getActiveResultData: function (paneId) {
    const tab = this.tabs[paneId];
    if (!tab || !tab.lastResultData) return null;
    const $activeResultTab = $(`#${paneId}`).find(
      '.results-tab-nav .dynamic-tab .nav-link.active'
    );
    if (!$activeResultTab.length) return null;
    const resultIndex = $activeResultTab.attr('id').split('-').pop();
    return tab.lastResultData.results?.[parseInt(resultIndex)] || null;
  },

  updateChartOptions: function (paneId, resultIndex) {
    const tab = this.tabs[paneId];
    if (!tab) return;
    const $pane = $(`#${paneId}`);
    const $chartBtn = $pane.find('.show-chart-btn');
    if (!tab.lastResultData?.results?.[resultIndex]) {
      $chartBtn.prop('disabled', true);
      return;
    }
    const result = tab.lastResultData.results[resultIndex];
    let numericCols = [],
      categoryCols = [];
    if (result.data?.length) {
      $.each(result.headers, (i, header) => {
        const value = result.data[0][header];
        if (value !== null && !isNaN(parseFloat(value)) && isFinite(value)) {
          numericCols.push(header);
        } else {
          categoryCols.push(header);
        }
      });
    }
    $chartBtn.prop('disabled', !(numericCols.length && categoryCols.length));
    $('#chart-label-col').html(
      $.map(categoryCols, (col) => `<option>${col}</option>`).join('')
    );
    $('#chart-value-col').html(
      $.map(numericCols, (col) => `<option>${col}</option>`).join('')
    );
  },

  initializeIntellisense: function (paneId) {
    const tab = this.tabs[paneId];
    $.get(site_url + 'api/intellisense', (data) => {
      if (Object.keys(data).length) {
        tab.editor.setOption('hintOptions', { tables: data });
      }
    }).fail(() => console.error(LANG.intellisense_error));
  },

  executeQuery: async function (paneId, page = 1) {
    const tab = this.tabs[paneId];
    if (!tab) return;

    const $pane = $(`#${paneId}`);
    tab.editableGrid = {
      enabled: false,
      pkColumn: null,
      dbName: null,
      schemaName: null,
      tableName: null,
      changedData: {},
    };
    $pane.find('.save-changes-btn').addClass('d-none');

    const sql = tab.editor.getSelection() || tab.editor.getValue();
    tab.currentSql = sql;

    const $btn = $pane
      .find('.execute-query-btn')
      .prop('disabled', true)
      .html(
        `<span class="spinner-border spinner-border-sm"></span> ${LANG.executing}`
      );
    const $resultsTabNav = $pane.find('.results-tab-nav');
    const $resultsTabContent = $pane.find('.results-tab-content');
    const $messagesContent = $pane.find('.messages-pane');
    const $placeholder = $pane.find('.results-placeholder');
    const $paginationControls = $pane.find('.pagination-controls');

    if (tab.resultsDataTable) {
      tab.resultsDataTable.destroy();
      tab.resultsDataTable = null;
    }
    $resultsTabNav.find('.dynamic-tab').remove();
    $resultsTabContent.find('.dynamic-tab-pane').remove();
    $placeholder.hide();
    $messagesContent.empty();
    $paginationControls.hide();

    $.ajax({
      url: site_url + 'api/query/execute',
      method: 'POST',
      data: { sql: sql, page: page, [this.csrfTokenName]: this.csrfTokenValue },
      success: (response) => {
        const tab = this.tabs[paneId];
        if (tab) {
          // Remove a query se já existir para a colocar no topo
          const index = tab.history.indexOf(sql);
          if (index > -1) {
            tab.history.splice(index, 1);
          }
          // Adiciona a query mais recente no início do histórico
          tab.history.unshift(sql);
          // Limita o histórico a 30 entradas
          if (tab.history.length > 30) {
            tab.history.pop();
          }
        }

        this.saveTabsState();
        $messagesContent.html(`<pre class="p-2 m-0">${response.message}</pre>`);
        //refreshHistory();

        if (!response.results?.length) {
          $placeholder.html(LANG.no_results_found).show();
          new bootstrap.Tab($pane.find('.messages-tab')[0]).show();
          return;
        }

        tab.lastResultData = response;
        $pane.find('.export-csv-btn, .export-json-btn').prop('disabled', false);

        $.each(response.results, (index, result) => {
          const resultTabId = `${paneId}-result-tab-${index}`,
            resultPaneId = `${paneId}-result-pane-${index}`,
            tableId = `${paneId}-result-table-${index}`;
          $resultsTabNav.prepend(
            `<li class="nav-item dynamic-tab" role="presentation"><button class="nav-link" id="${resultTabId}" data-bs-toggle="tab" data-bs-target="#${resultPaneId}" type="button" role="tab">${LANG.result} ${index + 1} <span class="badge bg-secondary ms-1">${result.rowCount}</span></button></li>`
          );
          $resultsTabContent.append(
            `<div class="tab-pane fade dynamic-tab-pane" id="${resultPaneId}" role="tabpanel"><div id="results-table-container-${tableId}" class="table-responsive h-100"></div></div>`
          );
          const $tableContainer = $resultsTabContent.find(
            `#results-table-container-${tableId}`
          );
          if (result.data?.length) {
            const $table = $(
              `<table id="${tableId}" class="table table-sm table-bordered table-striped table-hover w-100pct"></table>`
            );
            const $thead = $('<thead><tr></tr></thead>'),
              $tfoot = $('<tfoot><tr></tr></tfoot>');
            $.each(result.headers, (i, h) => {
              $thead.find('tr').append(`<th>${h}</th>`);
              $tfoot.find('tr').append(`<th>${h}</th>`);
            });
            $table.append($thead).append($tfoot);
            $tableContainer.append($table);

            tab.resultsDataTable = new DataTable(`#${tableId}`, {
              data: result.data,
              columns: $.map(result.headers, (h) => ({ data: h })),
              destroy: true,
              searching: true,
              initComplete: function () {
                this.api()
                  .columns()
                  .every(function () {
                    const column = this;
                    const title = column.footer().textContent;
                    $(column.footer())
                      .html(
                        `<input type="text" class="form-control form-control-sm" placeholder="${LANG.search_placeholder.replace('{0}', title)}" />`
                      )
                      .on('keyup change clear', function () {
                        if (column.search() !== this.value) {
                          column.search(this.value).draw();
                        }
                      });
                  });
              },
            });

            // Evento de duplo clique diretamente na tabela recém-criada.
            $table.on('dblclick', 'tbody td', (e) =>
              this.handleCellDoubleClick(e.currentTarget, paneId)
            );

            this._resetDirtyState(paneId);
          } else {
            $tableContainer.html(
              `<p class="p-2 text-muted">${LANG.empty_result}</p>`
            );
          }
        });

        const firstResult = response.results[0];
        if (firstResult.hasOwnProperty('totalRows')) {
          $paginationControls.css('display', 'flex');
          $paginationControls
            .find('.pagination-info')
            .text(
              `${LANG.page} ${firstResult.currentPage} ${LANG.of} ${firstResult.totalPages} (${firstResult.totalRows} ${LANG.records})`
            );
          $paginationControls
            .find('.pagination-prev')
            .prop('disabled', firstResult.currentPage <= 1);
          $paginationControls
            .find('.pagination-next')
            .prop(
              'disabled',
              firstResult.currentPage >= firstResult.totalPages
            );
        }

        if ($resultsTabNav.find('.dynamic-tab button').length) {
          $pane.find('.messages-pane').removeClass('show active');
          $pane.find('.execution-plan-pane').removeClass('show active');

          const firstResultTab = $resultsTabNav.find('.dynamic-tab button')[0];
          new bootstrap.Tab(firstResultTab).show();
        }
        this.updateChartOptions(paneId, 0);

        if (
          response.results.length === 1 &&
          response.results[0].data.length > 0
        ) {
          this.initEditableGrid(paneId, sql);
        }
      },
      error: (xhr) => {
        const errorMsg =
          xhr.responseJSON?.message ||
          xhr.responseJSON?.messages?.error?.message ||
          xhr.responseText ||
          'An error occurred.';
        $messagesContent.html(
          `<div class="alert alert-danger m-2"><h5 class="alert-heading">${LANG.exec_error}</h5><hr><p class="mb-0">${$('<div>').text(errorMsg).html()}</p></div>`
        );
        new bootstrap.Tab($pane.find('.messages-tab')[0]).show();
      },
      complete: () => {
        $btn
          .prop('disabled', false)
          .html(
            `<i class="fa-solid fa-play me-1"></i> ${LANG.execute} (Ctrl+Enter)`
          );
      },
    });
  },

  /**
   * Reseta o estado 'isDirty' de uma aba e remove o indicador visual (*).
   * @param {string} paneId - O ID da aba a ser resetada.
   */
  _resetDirtyState: function (paneId) {
    const tab = this.tabs[paneId];
    if (tab && tab.isDirty) {
      tab.isDirty = false;
      const $tabTitle = $(`#tab-${paneId}-link .tab-title`);
      $tabTitle.text($tabTitle.text().replace(' *', ''));
    }
  },

  explainQuery: function (paneId) {
    const tab = this.tabs[paneId];
    const $pane = $(`#${paneId}`);
    const sql = tab.editor.getSelection() || tab.editor.getValue();
    if (!sql.trim()) return;
    const $explainBtn = $pane
      .find('.explain-query-btn')
      .prop('disabled', true)
      .html('<span class="spinner-border spinner-border-sm"></span>');
    $.ajax({
      url: site_url + 'api/query/explain',
      method: 'POST',
      data: { sql: sql, [this.csrfTokenName]: this.csrfTokenValue },
      success: (response) => {
        const $planContainer = $pane.find('.execution-plan-pane').empty();
        if (response.db_type === 'mysql') {
          $planContainer.append(
            $('<pre class="planContainer"></pre>').text(
              JSON.stringify(JSON.parse(response.plan), null, 2)
            )
          );
        } else {
          QP.showPlan($planContainer.get(0), response.plan);
        }
        new bootstrap.Tab($pane.find('.plan-tab')[0]).show();
      },
      error: (xhr) => {
        const errorMsg =
          xhr.responseJSON?.messages?.error?.message ||
          xhr.responseText ||
          'An error occurred.';
        $pane
          .find('.messages-content')
          .html(
            `<div class="alert alert-danger m-2"><h5 class="alert-heading">${LANG.exec_error}</h5><hr><p class="mb-0">${$('<div>').text(errorMsg).html()}</p></div>`
          );
        new bootstrap.Tab($pane.find('.messages-tab')[0]).show();
      },
      complete: () =>
        $explainBtn
          .prop('disabled', false)
          .html(`<i class="fa-solid fa-sitemap me-1"></i> ${LANG.explain}`),
    });
  },

  parseTableName: function (sql, sessionDb) {
    const cleanSql = sql.replace(/--.*$/gm, '').replace(/\s+/g, ' ').trim();
    if (/JOIN\s+/i.test(cleanSql)) return null;
    const fromMatch = /FROM\s+([^\s;]+)/i.exec(cleanSql);
    if (!fromMatch || !fromMatch[1]) return null;
    const fullName = fromMatch[1].replace(/[`\[\]]/g, '');
    const parts = fullName.split('.');
    let db, schema, table;
    if (DB_TYPE === 'mysql') {
      table = parts[parts.length - 1];
      db = parts.length > 1 ? parts[0] : sessionDb;
      schema = db;
    } else {
      table = parts[parts.length - 1];
      schema = parts.length > 1 ? parts[parts.length - 2] : 'dbo';
      db = parts.length > 2 ? parts[0] : sessionDb;
    }
    return { db, schema, table };
  },

  initEditableGrid: function (paneId, sql) {
    const tab = this.tabs[paneId];
    if (!sessionDb)
      return console.log(
        `[Aba ${paneId}] Edição desabilitada: nenhuma base de dados de sessão selecionada.`
      );
    const tableInfo = this.parseTableName(sql, sessionDb);
    if (!tableInfo)
      return console.log(
        `[Aba ${paneId}] Edição desabilitada: não foi possível identificar uma tabela única na query.`
      );
    tab.editableGrid.dbName = tableInfo.db;
    tab.editableGrid.schemaName = tableInfo.schema;
    tab.editableGrid.tableName = tableInfo.table;
    $.get(
      `${site_url}api/editor/pk/${encodeURIComponent(tableInfo.db)}/${encodeURIComponent(tableInfo.table)}`
    )
      .done((data) => {
        tab.editableGrid.pkColumn = data.primaryKey;
        tab.editableGrid.enabled = true;
        console.log(
          `[Aba ${paneId}] Edição HABILITADA para a tabela "${tableInfo.table}" com PK "${data.primaryKey}".`
        );
      })
      .fail(() => {
        tab.editableGrid.enabled = false;
        console.warn(
          `[Aba ${paneId}] Edição DESABILITADA: a tabela "${tableInfo.table}" não tem PK ou ocorreu um erro na busca.`
        );
      });
  },

  handleCellDoubleClick: function (cell, paneId) {
    const tab = this.tabs[paneId];
    if (!tab || !tab.editableGrid.enabled) {
      console.log(
        `[Aba ${paneId}] Duplo clique ignorado. Edição habilitada: ${tab?.editableGrid?.enabled || false}`
      );
      return;
    }
    const td = $(cell);
    if (td.find('input').length > 0) return;
    const originalValue = td.text();
    const input = $(
      '<input type="text" class="form-control form-control-sm">'
    ).val(originalValue);
    td.html(input).find('input').focus();
    const finishEditing = () => {
      const newValue = input.val();
      td.text(newValue);
      if (newValue !== originalValue) {
        const row = td.closest('tr');
        const rowData = tab.resultsDataTable.row(row).data();
        const pkValue = rowData[tab.editableGrid.pkColumn];
        const columnName = tab.resultsDataTable
          .column(td.index())
          .header().textContent;
        if (columnName === tab.editableGrid.pkColumn) {
          notifier.show(LANG.no_pk_edit, 'warning');
          td.text(originalValue);
          return;
        }
        if (!tab.editableGrid.changedData[pkValue])
          tab.editableGrid.changedData[pkValue] = { changes: {} };
        tab.editableGrid.changedData[pkValue].changes[columnName] = newValue;
        row.addClass('datatable-row-changed');
        $(`#${paneId}`).find('.save-changes-btn').show();
      }
    };
    input.on('blur', finishEditing).on('keydown', (e) => {
      if (e.key === 'Enter') input.blur();
      if (e.key === 'Escape') {
        input.off('blur');
        td.text(originalValue);
      }
    });
  },

  saveGridChanges: function (paneId) {
    const tab = this.tabs[paneId];
    const $btn = $(`#${paneId}`).find('.save-changes-btn');
    $btn
      .prop('disabled', true)
      .html(
        `<span class="spinner-border spinner-border-sm"></span> ${LANG.saving_changes}`
      );
    const promises = Object.entries(tab.editableGrid.changedData).map(
      ([pkValue, data]) => {
        const payload = {
          [this.csrfTokenName]: this.csrfTokenValue,
          database: tab.editableGrid.dbName,
          schema: tab.editableGrid.schemaName,
          table: tab.editableGrid.tableName,
          pkColumn: tab.editableGrid.pkColumn,
          pkValue: pkValue,
          changes: data.changes,
        };
        return $.post(site_url + 'api/editor/update', payload);
      }
    );
    Promise.all(promises)
      .then(() => {
        notifier.show(LANG.data_saved, 'success');
        tab.editableGrid.changedData = {};
        $btn.addClass('d-none');
        $(`#${paneId}`)
          .find('.datatable-row-changed')
          .removeClass('datatable-row-changed');
      })
      .catch((err) => {
        const errorMsg = err.responseJSON?.messages?.error || LANG.error_saving;
        notifier.show(errorMsg, 'error');
      })
      .finally(() => {
        $btn
          .prop('disabled', false)
          .html(`<i class="fa-solid fa-save me-1"></i> ${LANG.save_changes}`);
      });
  },

  /**
   * Exporta os dados do resultado da query ativa para um ficheiro (CSV ou JSON).
   * @param {string} paneId - O ID da aba ativa.
   * @param {string} format - O formato para exportação ('csv' ou 'json').
   */
  exportResult: function (paneId, format) {
    const tab = this.tabs[paneId];

    if (
      !tab ||
      !tab.lastResultData ||
      !tab.lastResultData.results ||
      !tab.lastResultData.results.length
    ) {
      notifier.show(LANG.feedback.noquery_to_export, 'warning');
      return;
    }

    const resultSet = tab.lastResultData.results[0];
    const headers = resultSet.headers;
    const data = resultSet.data;

    if (!data || data.length === 0) {
      notifier.show(LANG.feedback.noquery_to_export, 'warning');
      return;
    }

    const timestamp = new Date()
      .toISOString()
      .slice(0, 19)
      .replace('T', '_')
      .replace(/:/g, '-');
    const filename = `export_${timestamp}.${format}`;

    if (format === 'csv') {
      window.exportToCsv(filename, headers, data);
    } else if (format === 'json') {
      const jsonData = data.map((row) => {
        const newRow = {};
        headers.forEach((header, index) => {
          newRow[header] = Object.values(row)[index];
        });
        return newRow;
      });
      window.exportToJson(filename, jsonData);
    }
  },
};
