// Importe e exponha o jQuery globalmente primeiro
import $ from 'jquery';
window.$ = window.jQuery = $;

// Importe as bibliotecas de terceiros que dependem do jQuery
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import 'jstree';
import Split from 'split.js';
window.Split = Split;

import Sortable from 'sortablejs';
window.Sortable = Sortable;

import DataTable from 'datatables.net';
import 'datatables.net-bs5';
window.DataTable = DataTable;

// --- Importação do CodeMirror e seus Addons ---
import CodeMirror from 'codemirror';
window.CodeMirror = CodeMirror;
import 'codemirror/mode/sql/sql.js';
import 'codemirror/addon/edit/matchbrackets.js';
import 'codemirror/addon/edit/closebrackets.js';
import 'codemirror/addon/hint/show-hint.js';
import 'codemirror/addon/hint/sql-hint.js';
import 'codemirror/addon/selection/active-line.js';
import 'codemirror/addon/dialog/dialog.js';
import 'codemirror/addon/search/search.js';
import 'codemirror/addon/search/searchcursor.js';
import 'codemirror/addon/search/jump-to-line.js';
import 'codemirror/addon/search/matchesonscrollbar.js';

// O restante das importações
import 'chart.js';
import 'sql-formatter';
import 'crypto-js';
import '../../public/libs/qp/qp.js';

// scripts da sua aplicação (ainda para atualizar)
import './utility.js';
import './tabManager.js';
import './export.js';
import './dbSelectorHandler.js';
import './schemaEditor.js';
import './scriptGenerator.js';
import './renderAgentJobs.js';
import './displayJobHistory.js';
import './renderMySqlEvents.js';
import './renderSavedScripts.js';
import './refreshHistory.js';
import './renderSharedScripts.js';
import './renderQueryTemplates.js';
import './objectExplorer.js';
import './jobsHandlers.js';
import './localEventHandlers.js';

// scripts da aplicação (atualizados)
import SessionTimeout from './sessionTimeout.js';
import ConnectionStatus from './connectionStatus.js';
import { confirmModal, showConfirmModal } from './confirmModal.js';
import notifier from './notifier.js';

const themeManager = {
  applyTheme: function (theme) {
    const isLight = theme === 'light';
    document.body.classList.toggle('light-theme', isLight);
    document.getElementById('theme-toggle-btn').innerHTML = isLight
      ? '<i class="fa-solid fa-moon"></i>'
      : '<i class="fa-solid fa-sun"></i>';
    localStorage.setItem('querysphere_theme', theme);

    if (typeof TabManager !== 'undefined' && TabManager.tabs) {
      const newTheme = isLight ? 'default' : 'material-darker';
      for (const paneId in TabManager.tabs) {
        if (TabManager.tabs.hasOwnProperty(paneId)) {
          const tab = TabManager.tabs[paneId];
          if (tab && tab.editor) {
            tab.editor.setOption('theme', newTheme);
          }
        }
      }
    }
  },
  init: function () {
    const savedTheme = localStorage.getItem('querysphere_theme');
    this.applyTheme(
      savedTheme ||
        (window.matchMedia &&
        window.matchMedia('(prefers-color-scheme: light)').matches
          ? 'light'
          : 'dark')
    );
  },
};
themeManager.init();

// Bloco de inicialização da aplicação
$(function () {
  $('#theme-toggle-btn').on('click', () => {
    themeManager.applyTheme(
      $('body').hasClass('light-theme') ? 'dark' : 'light'
    );
  });

  // Initial setup
  refreshHistory();
  renderSavedScripts();
  renderQueryTemplates();
  renderSharedScripts();
  TabManager.init();
  scriptGenerator.init(TabManager);
  if (DB_TYPE === 'sqlsrv') renderAgentJobs();
  if (DB_TYPE === 'mysql') renderMySqlEvents();
  SessionTimeout.init(window.sessionTimeoutConfig);
  ConnectionStatus.init({ lang: window.LANG });
});
