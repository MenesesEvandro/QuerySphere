/**
 * TabManager
 *
 * Este módulo é responsável por toda a gestão das abas do editor,
 * incluindo a criação, exclusão e gestão de estado de cada aba (editor, resultados, etc.).
 */
const TabManager = {
  tabCounter: 0,
  activeTabId: null,
  tabs: {},
  csrfTokenName: window.csrfTokenName,
  csrfTokenValue: window.csrfTokenValue,

  /**
   * Inicializa o TabManager, cria a primeira aba e anexa os eventos globais.
   */
  init: function () {
    this.addTab(); // Adiciona a primeira aba ao iniciar

    // **CORREÇÃO:** Agenda uma atualização para o primeiro editor.
    // O timeout dá tempo para que a UI (Split.js, etc.) se processe completamente.
    setTimeout(() => this.getActiveTab()?.editor.refresh(), 100);

    $("#new-tab-btn").on("click", (e) => {
      e.preventDefault();
      this.addTab();
    });

    // Delegação de eventos para botões de fechar aba
    $("#editor-tabs").on("click", ".tab-close-btn", (e) => {
      e.preventDefault();
      e.stopPropagation();
      const tabLink = $(e.currentTarget).closest(".nav-link");
      const paneId = tabLink.attr("href").substring(1);
      this.closeTab(paneId);
    });

    // Atualiza a aba ativa quando o utilizador clica em uma.
    $("#editor-tabs").on("shown.bs.tab", 'a[data-bs-toggle="tab"]', (e) => {
      this.activeTabId = $(e.target).attr("href").substring(1);
      const activeTab = this.getActiveTab();
      if (activeTab && activeTab.editor) {
        activeTab.editor.refresh();
        activeTab.editor.focus();
      }
    });
  },

  /**
   * Adiciona uma nova aba ao editor.
   */
  addTab: function () {
    this.tabCounter++;
    const paneId = `pane-${this.tabCounter}`;

    const tabLink = $(`
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="tab-${paneId}-link" data-bs-toggle="tab" href="#${paneId}" role="tab">
                    Query ${this.tabCounter}
                    <button type="button" class="tab-close-btn" aria-label="Close">&times;</button>
                </a>
            </li>
        `);
    $("#new-tab-btn-container").before(tabLink);

    const template = document.getElementById("editor-tab-template");
    const newPane = $(template.content.cloneNode(true).firstElementChild);
    newPane.attr("id", paneId).addClass("h-100");
    $("#editor-panes").append(newPane);

    this.initTab(paneId);
    new bootstrap.Tab(tabLink.find("a")[0]).show();
  },

  /**
   * Inicializa os componentes de uma nova aba (editor, split.js, eventos).
   */
  initTab: function (paneId) {
    const $pane = $(`#${paneId}`);
    const editor = CodeMirror.fromTextArea($pane.find(".query-editor")[0], {
      lineNumbers: true,
      mode: DB_TYPE === "mysql" ? "text/x-mysql" : "text/x-mssql",
      theme: document.body.classList.contains("light-theme")
        ? "default"
        : "material-darker",
      indentWithTabs: true,
      smartIndent: true,
      extraKeys: {
        "Ctrl-Space": "autocomplete",
        F5: () => $pane.find(".execute-query-btn").trigger("click"),
        "Ctrl-Enter": () => $pane.find(".execute-query-btn").trigger("click"),
      },
      hintOptions: { tables: {} },
    });
    editor.setSize("100%", "100%");

    const split = Split(
      [`#${paneId} .query-editor-panel`, `#${paneId} .results-panel`],
      {
        sizes: [65, 35],
        minSize: 100,
        gutterSize: 7,
        direction: "vertical",
        cursor: "row-resize",
        onDragEnd: () => editor.refresh(),
      },
    );

    this.tabs[paneId] = {
      id: paneId,
      editor: editor,
      split: split,
      lastResultData: null,
      currentSql: "",
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

    this.attachTabEvents(paneId);
    this.initializeIntellisense(paneId);
  },
  /**
   * Anexa todos os manipuladores de eventos para os botões de uma aba específica.
   */
  attachTabEvents: function (paneId) {
    const $pane = $(`#${paneId}`);
    const tab = this.tabs[paneId];
    const $resultsPanel = $pane.find(".results-panel");

    // Mapeamento de botões para funções
    $pane
      .find(".execute-query-btn")
      .on("click", () => this.executeQuery(paneId));
    $pane
      .find(".explain-query-btn")
      .on("click", () => this.explainQuery(paneId));
    $pane.find(".format-sql-btn").on("click", () => this.formatSql(paneId));
    $pane
      .find(".export-csv-btn")
      .on("click", () => this.exportResult(paneId, "csv"));
    $pane
      .find(".export-json-btn")
      .on("click", () => this.exportResult(paneId, "json"));
    $pane.find(".save-script-btn").on("click", () => this.saveScript(paneId));
    $pane.find(".share-script-btn").on("click", () => this.shareScript(paneId));
    $pane
      .find(".save-changes-btn")
      .on("click", () => this.saveGridChanges(paneId));

    // Eventos do painel de resultados
    $resultsPanel.on("click", ".pagination-prev", () => {
      const result = tab.lastResultData?.results?.[0];
      if (result && result.currentPage > 1)
        this.executeQuery(paneId, result.currentPage - 1);
    });
    $resultsPanel.on("click", ".pagination-next", () => {
      const result = tab.lastResultData?.results?.[0];
      if (result && result.currentPage < result.totalPages)
        this.executeQuery(paneId, result.currentPage + 1);
    });
    $resultsPanel.on(
      "shown.bs.tab",
      '.results-tab-nav button[data-bs-toggle="tab"]',
      (event) => {
        if ($(event.target).parent().hasClass("dynamic-tab")) {
          const resultIndex = $(event.target).attr("id").split("-").pop();
          this.updateChartOptions(paneId, parseInt(resultIndex));
        } else {
          $pane.find(".show-chart-btn").prop("disabled", true);
        }
      },
    );
  },

  /**
   * Obtém a aba que está atualmente ativa.
   * @returns {object|null} O objeto da aba ativa.
   */
  getActiveTab: function () {
    if (!this.activeTabId) {
      const firstTabLink = $("#editor-tabs .nav-link.active");
      if (firstTabLink.length) {
        this.activeTabId = firstTabLink.attr("href").substring(1);
      }
    }
    return this.tabs[this.activeTabId] || null;
  },
  /**
   * Fecha uma aba específica.
   */
  closeTab: function (paneId) {
    if (Object.keys(this.tabs).length <= 1) {
      notifier.show(LANG.feedback.cannot_close_last_tab, "warning");
      return;
    }

    const $tabLinkContainer = $(
      `#editor-tabs .nav-link[href="#${paneId}"]`,
    ).closest(".nav-item");
    const $tabPane = $(`#${paneId}`);

    if (this.activeTabId === paneId) {
      const nextTabLink = $tabLinkContainer
        .next()
        .not("#new-tab-btn-container")
        .find("a");
      const prevTabLink = $tabLinkContainer.prev().find("a");
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
      ".results-tab-nav .dynamic-tab .nav-link.active",
    );
    if (!$activeResultTab.length) return null;
    const resultIndex = $activeResultTab.attr("id").split("-").pop();
    return tab.lastResultData.results?.[parseInt(resultIndex)] || null;
  },

  updateChartOptions: function (paneId, resultIndex) {
    const tab = this.tabs[paneId];
    if (!tab) return;
    const $pane = $(`#${paneId}`);
    const $chartBtn = $pane.find(".show-chart-btn");
    if (!tab.lastResultData?.results?.[resultIndex]) {
      $chartBtn.prop("disabled", true);
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
    $chartBtn.prop("disabled", !(numericCols.length && categoryCols.length));
    $("#chart-label-col").html(
      $.map(categoryCols, (col) => `<option>${col}</option>`).join(""),
    );
    $("#chart-value-col").html(
      $.map(numericCols, (col) => `<option>${col}</option>`).join(""),
    );
  },

  initializeIntellisense: function (paneId) {
    const tab = this.tabs[paneId];
    $.get(site_url + "api/intellisense", (data) => {
      if (Object.keys(data).length) {
        tab.editor.setOption("hintOptions", { tables: data });
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
    $pane.find(".save-changes-btn").hide();

    const sql = tab.editor.getSelection() || tab.editor.getValue();
    tab.currentSql = sql;

    const $btn = $pane
      .find(".execute-query-btn")
      .prop("disabled", true)
      .html(
        `<span class="spinner-border spinner-border-sm"></span> ${LANG.executing}`,
      );
    const $resultsTabNav = $pane.find(".results-tab-nav");
    const $resultsTabContent = $pane.find(".results-tab-content");
    const $messagesContent = $pane.find(".messages-content");
    const $placeholder = $pane.find(".results-placeholder");
    const $paginationControls = $pane.find(".pagination-controls");

    if (tab.resultsDataTable) {
      tab.resultsDataTable.destroy();
      tab.resultsDataTable = null;
    }
    $resultsTabNav.find(".dynamic-tab").remove();
    $resultsTabContent.find(".dynamic-tab-pane").remove();
    $placeholder.hide();
    $messagesContent.empty();
    $paginationControls.hide();

    $.ajax({
      url: site_url + "api/query/execute",
      method: "POST",
      data: { sql: sql, page: page, [this.csrfTokenName]: this.csrfTokenValue },
      success: (response) => {
        $messagesContent.html(`<pre class="p-2 m-0">${response.message}</pre>`);
        refreshHistory();

        if (!response.results?.length) {
          $placeholder.html(LANG.no_results_found).show();
          new bootstrap.Tab($pane.find(".messages-tab")[0]).show();
          return;
        }

        tab.lastResultData = response;
        $pane.find(".export-csv-btn, .export-json-btn").prop("disabled", false);

        $.each(response.results, (index, result) => {
          const resultTabId = `${paneId}-result-tab-${index}`,
            resultPaneId = `${paneId}-result-pane-${index}`,
            tableId = `${paneId}-result-table-${index}`;
          $resultsTabNav.prepend(
            `<li class="nav-item dynamic-tab" role="presentation"><button class="nav-link" id="${resultTabId}" data-bs-toggle="tab" data-bs-target="#${resultPaneId}" type="button" role="tab">${LANG.result} ${index + 1} <span class="badge bg-secondary ms-1">${result.rowCount}</span></button></li>`,
          );
          $resultsTabContent.append(
            `<div class="tab-pane fade dynamic-tab-pane" id="${resultPaneId}" role="tabpanel"><div id="results-table-container-${tableId}" class="table-responsive h-100"></div></div>`,
          );
          const $tableContainer = $resultsTabContent.find(
            `#results-table-container-${tableId}`,
          );
          if (result.data?.length) {
            const $table = $(
              `<table id="${tableId}" class="table table-sm table-bordered table-striped table-hover" style="width:100%"></table>`,
            );
            const $thead = $("<thead><tr></tr></thead>"),
              $tfoot = $("<tfoot><tr></tr></tfoot>");
            $.each(result.headers, (i, h) => {
              $thead.find("tr").append(`<th>${h}</th>`);
              $tfoot.find("tr").append(`<th>${h}</th>`);
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
                        `<input type="text" class="form-control form-control-sm" placeholder="${LANG.search_placeholder.replace("{0}", title)}" />`,
                      )
                      .on("keyup change clear", function () {
                        if (column.search() !== this.value) {
                          column.search(this.value).draw();
                        }
                      });
                  });
              },
            });

            // *** INÍCIO DA CORREÇÃO ***
            // Ligamos o evento de duplo clique diretamente na tabela recém-criada.
            $table.on("dblclick", "tbody td", (e) =>
              this.handleCellDoubleClick(e.currentTarget, paneId),
            );
            // *** FIM DA CORREÇÃO ***
          } else {
            $tableContainer.html(
              `<p class="p-2 text-muted">${LANG.empty_result}</p>`,
            );
          }
        });

        const firstResult = response.results[0];
        if (firstResult.hasOwnProperty("totalRows")) {
          $paginationControls.css("display", "flex");
          $paginationControls
            .find(".pagination-info")
            .text(
              `${LANG.page} ${firstResult.currentPage} ${LANG.of} ${firstResult.totalPages} (${firstResult.totalRows} ${LANG.records})`,
            );
          $paginationControls
            .find(".pagination-prev")
            .prop("disabled", firstResult.currentPage <= 1);
          $paginationControls
            .find(".pagination-next")
            .prop(
              "disabled",
              firstResult.currentPage >= firstResult.totalPages,
            );
        }

        if ($resultsTabNav.find(".dynamic-tab button").length) {
          new bootstrap.Tab(
            $resultsTabNav.find(".dynamic-tab button")[0],
          ).show();
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
          xhr.responseJSON?.messages?.error?.message ||
          xhr.responseText ||
          "An error occurred.";
        $messagesContent.html(
          `<div class="alert alert-danger m-2"><h5 class="alert-heading">${LANG.exec_error}</h5><hr><p class="mb-0">${$("<div>").text(errorMsg).html()}</p></div>`,
        );
        new bootstrap.Tab($pane.find(".messages-tab")[0]).show();
      },
      complete: () => {
        $btn
          .prop("disabled", false)
          .html(`<i class="fa fa-play me-1"></i> ${LANG.execute} (Ctrl+Enter)`);
      },
    });
  },

  explainQuery: function (paneId) {
    const tab = this.tabs[paneId];
    const $pane = $(`#${paneId}`);
    const sql = tab.editor.getSelection() || tab.editor.getValue();
    if (!sql.trim()) return;
    const $explainBtn = $pane
      .find(".explain-query-btn")
      .prop("disabled", true)
      .html('<span class="spinner-border spinner-border-sm"></span>');
    $.ajax({
      url: site_url + "api/query/explain",
      method: "POST",
      data: { sql: sql, [this.csrfTokenName]: this.csrfTokenValue },
      success: (response) => {
        const $planContainer = $pane.find(".execution-plan-pane").empty();
        if (response.db_type === "mysql") {
          $planContainer.append(
            $(
              '<pre style="white-space: pre-wrap; word-wrap: break-word;"></pre>',
            ).text(JSON.stringify(JSON.parse(response.plan), null, 2)),
          );
        } else {
          QP.showPlan($planContainer.get(0), response.plan);
        }
        new bootstrap.Tab($pane.find(".plan-tab")[0]).show();
      },
      error: (xhr) => {
        const errorMsg =
          xhr.responseJSON?.messages?.error?.message ||
          xhr.responseText ||
          "An error occurred.";
        $pane
          .find(".messages-content")
          .html(
            `<div class="alert alert-danger m-2"><h5 class="alert-heading">${LANG.exec_error}</h5><hr><p class="mb-0">${$("<div>").text(errorMsg).html()}</p></div>`,
          );
        new bootstrap.Tab($pane.find(".messages-tab")[0]).show();
      },
      complete: () =>
        $explainBtn
          .prop("disabled", false)
          .html(`<i class="fa fa-sitemap me-1"></i> ${LANG.explain}`),
    });
  },

  parseTableName: function (sql, sessionDb) {
    const cleanSql = sql.replace(/--.*$/gm, "").replace(/\s+/g, " ").trim();
    if (/JOIN\s+/i.test(cleanSql)) return null;
    const fromMatch = /FROM\s+([^\s;]+)/i.exec(cleanSql);
    if (!fromMatch || !fromMatch[1]) return null;
    const fullName = fromMatch[1].replace(/[`\[\]]/g, "");
    const parts = fullName.split(".");
    let db, schema, table;
    if (DB_TYPE === "mysql") {
      table = parts[parts.length - 1];
      db = parts.length > 1 ? parts[0] : sessionDb;
      schema = db;
    } else {
      table = parts[parts.length - 1];
      schema = parts.length > 1 ? parts[parts.length - 2] : "dbo";
      db = parts.length > 2 ? parts[0] : sessionDb;
    }
    return { db, schema, table };
  },

  initEditableGrid: function (paneId, sql) {
    const tab = this.tabs[paneId];
    if (!sessionDb)
      return console.log(
        `[Aba ${paneId}] Edição desabilitada: nenhuma base de dados de sessão selecionada.`,
      );
    const tableInfo = this.parseTableName(sql, sessionDb);
    if (!tableInfo)
      return console.log(
        `[Aba ${paneId}] Edição desabilitada: não foi possível identificar uma tabela única na query.`,
      );
    tab.editableGrid.dbName = tableInfo.db;
    tab.editableGrid.schemaName = tableInfo.schema;
    tab.editableGrid.tableName = tableInfo.table;
    $.get(
      `${site_url}api/editor/pk/${encodeURIComponent(tableInfo.db)}/${encodeURIComponent(tableInfo.table)}`,
    )
      .done((data) => {
        tab.editableGrid.pkColumn = data.primaryKey;
        tab.editableGrid.enabled = true;
        console.log(
          `[Aba ${paneId}] Edição HABILITADA para a tabela "${tableInfo.table}" com PK "${data.primaryKey}".`,
        );
      })
      .fail(() => {
        tab.editableGrid.enabled = false;
        console.warn(
          `[Aba ${paneId}] Edição DESABILITADA: a tabela "${tableInfo.table}" não tem PK ou ocorreu um erro na busca.`,
        );
      });
  },

  handleCellDoubleClick: function (cell, paneId) {
    const tab = this.tabs[paneId];
    if (!tab || !tab.editableGrid.enabled) {
      console.log(
        `[Aba ${paneId}] Duplo clique ignorado. Edição habilitada: ${tab?.editableGrid?.enabled || false}`,
      );
      return;
    }
    const td = $(cell);
    if (td.find("input").length > 0) return;
    const originalValue = td.text();
    const input = $(
      '<input type="text" class="form-control form-control-sm">',
    ).val(originalValue);
    td.html(input).find("input").focus();
    const finishEditing = () => {
      const newValue = input.val();
      td.text(newValue);
      if (newValue !== originalValue) {
        const row = td.closest("tr");
        const rowData = tab.resultsDataTable.row(row).data();
        const pkValue = rowData[tab.editableGrid.pkColumn];
        const columnName = tab.resultsDataTable
          .column(td.index())
          .header().textContent;
        if (columnName === tab.editableGrid.pkColumn) {
          notifier.show(LANG.no_pk_edit, "warning");
          td.text(originalValue);
          return;
        }
        if (!tab.editableGrid.changedData[pkValue])
          tab.editableGrid.changedData[pkValue] = { changes: {} };
        tab.editableGrid.changedData[pkValue].changes[columnName] = newValue;
        row.addClass("datatable-row-changed");
        $(`#${paneId}`).find(".save-changes-btn").show();
      }
    };
    input.on("blur", finishEditing).on("keydown", (e) => {
      if (e.key === "Enter") input.blur();
      if (e.key === "Escape") {
        input.off("blur");
        td.text(originalValue);
      }
    });
  },

  saveGridChanges: function (paneId) {
    const tab = this.tabs[paneId];
    const $btn = $(`#${paneId}`).find(".save-changes-btn");
    $btn
      .prop("disabled", true)
      .html(
        `<span class="spinner-border spinner-border-sm"></span> ${LANG.saving_changes}`,
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
        return $.post(site_url + "api/editor/update", payload);
      },
    );
    Promise.all(promises)
      .then(() => {
        notifier.show(LANG.data_saved, "success");
        tab.editableGrid.changedData = {};
        $btn.hide();
        $(`#${paneId}`)
          .find(".datatable-row-changed")
          .removeClass("datatable-row-changed");
      })
      .catch((err) => {
        const errorMsg = err.responseJSON?.messages?.error || LANG.error_saving;
        notifier.show(errorMsg, "error");
      })
      .finally(() => {
        $btn
          .prop("disabled", false)
          .html(`<i class="fa fa-save me-1"></i> ${LANG.save_changes}`);
      });
  },
};
