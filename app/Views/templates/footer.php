</div>
<div class="modal fade" id="chartModal" tabindex="-1" aria-labelledby="chartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chartModalLabel"><?= lang(
                    'App.charts.modalTitle',
                ) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="<?= lang('App.general.close') ?>"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-4"><label for="chart-type" class="form-label"><?= lang(
                        'App.charts.type',
                    ) ?></label><select id="chart-type" class="form-select">
                            <option value="bar"><?= lang(
                                'App.charts.bar',
                            ) ?></option>
                            <option value="line"><?= lang(
                                'App.charts.line',
                            ) ?></option>
                            <option value="pie"><?= lang(
                                'App.charts.pie',
                            ) ?></option>
                        </select></div>
                    <div class="col-md-4"><label for="chart-label-col" class="form-label"><?= lang(
                        'App.charts.labelAxis',
                    ) ?></label><select id="chart-label-col" class="form-select"></select></div>
                    <div class="col-md-4"><label for="chart-value-col" class="form-label"><?= lang(
                        'App.charts.valueAxis',
                    ) ?></label><select id="chart-value-col" class="form-select"></select></div>
                </div>
                <div class="mt-3" style="position: relative; height:60vh; width:100%"><canvas id="myChart"></canvas>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal"><?= lang(
                        'App.general.close',
                    ) ?></button>
                <button type="button" id="generate-chart-btn" class="btn btn-primary"><?= lang(
                    'App.charts.generate',
                ) ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="schema-editor-modal" tabindex="-1" aria-labelledby="schemaEditorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="schemaEditorModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= lang(
                    'App.general.close',
                ) ?>"></button>
            </div>
            <div class="modal-body">
                 <div class="mb-3">
                    <label for="table-name" class="form-label"><?= lang(
                        'App.schema_editor.table_name',
                    ) ?></label>
                    <input type="text" class="form-control" id="table-name" required>
                </div>
                <hr>

                <ul class="nav nav-tabs" id="schema-editor-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="columns-tab" data-bs-toggle="tab" data-bs-target="#columns-pane" type="button" role="tab">Columns</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="indexes-tab" data-bs-toggle="tab" data-bs-target="#indexes-pane" type="button" role="tab"><?= lang(
                            'App.schema_editor.indexes',
                        ) ?></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="constraints-tab" data-bs-toggle="tab" data-bs-target="#constraints-pane" type="button" role="tab"><?= lang(
                            'App.schema_editor.constraints',
                        ) ?></button>
                    </li>
                </ul>

                <div class="tab-content" id="schema-editor-tab-content">
                    <div class="tab-pane fade show active" id="columns-pane" role="tabpanel">
                        <form id="schema-editor-form">
                            <table class="table table-sm mt-3">
                                <thead>
                                    <tr>
                                        <th><?= lang(
                                            'App.schema_editor.primary_key',
                                        ) ?></th>
                                        <th><?= lang(
                                            'App.schema_editor.column_name',
                                        ) ?></th>
                                        <th><?= lang(
                                            'App.schema_editor.data_type',
                                        ) ?></th>
                                        <th><?= lang(
                                            'App.schema_editor.size_length',
                                        ) ?></th>
                                        <th><?= lang(
                                            'App.schema_editor.allow_null',
                                        ) ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="columns-container">
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="add-column-btn">
                                <i class="fa fa-plus me-1"></i> <?= lang(
                                    'App.schema_editor.add_column',
                                ) ?>
                            </button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="indexes-pane" role="tabpanel">
                        <table class="table table-sm mt-3">
                            <thead>
                                <tr>
                                    <th><?= lang(
                                        'App.schema_editor.index_name',
                                    ) ?></th>
                                    <th><?= lang(
                                        'App.schema_editor.index_columns',
                                    ) ?></th>
                                    <th><?= lang(
                                        'App.schema_editor.index_unique',
                                    ) ?></th>
                                    <th><?= lang(
                                        'App.schema_editor.index_type',
                                    ) ?></th>
                                </tr>
                            </thead>
                            <tbody id="indexes-container">
                            </tbody>
                        </table>
                        <hr>
                        <h5><?= lang('App.schema_editor.add_index') ?></h5>
                        <form id="add-index-form" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="new-index-name" class="form-label"><?= lang('App.schema_editor.index_name') ?></label>
                                <input type="text" class="form-control form-control-sm" id="new-index-name" placeholder="<?= lang('App.schema_editor.index_name_placeholder') ?>" required>
                            </div>
                            <div class="col-md-5">
                                <label for="new-index-columns" class="form-label"><?= lang('App.schema_editor.select_columns') ?></label>
                                <select id="new-index-columns" class="form-select form-select-sm" multiple required>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="new-index-unique">
                                    <label class="form-check-label" for="new-index-unique"><?= lang('App.schema_editor.index_unique') ?></label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-sm btn-success w-100"><?= lang('App.schema_editor.add_index') ?></button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="constraints-pane" role="tabpanel">
                        <h5 class="mt-3"><?= lang('App.schema_editor.foreign_keys') ?></h5>
                        <table class="table table-sm table-hover mt-2">
                            <thead>
                                <tr>
                                    <th><?= lang('App.schema_editor.fk_name') ?></th>
                                    <th><?= lang('App.schema_editor.fk_columns') ?></th>
                                    <th><?= lang('App.schema_editor.fk_references_table') ?></th>
                                    <th><?= lang('App.schema_editor.fk_references_columns') ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="constraints-container">
                                </tbody>
                        </table>
                        <hr>
                        <h6><?= lang('App.schema_editor.add_foreign_key') ?></h6>
                        <form id="add-fk-form" class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label"><?= lang('App.schema_editor.fk_name') ?></label>
                                <input type="text" class="form-control form-control-sm" id="new-fk-name" placeholder="<?= lang('App.schema_editor.fk_name_placeholder') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><?= lang('App.schema_editor.fk_columns') ?></label>
                                <select id="new-fk-columns" class="form-select form-select-sm" multiple></select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><?= lang('App.schema_editor.fk_references_table') ?></label>
                                <select id="new-fk-references-table" class="form-select form-select-sm"></select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><?= lang('App.schema_editor.fk_references_columns') ?></label>
                                <select id="new-fk-references-columns" class="form-select form-select-sm" multiple></select>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-sm btn-success"><?= lang('App.schema_editor.add_foreign_key') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang(
                    'App.general.close',
                ) ?></button>
                <button type="button" class="btn btn-primary" id="save-table-btn"><?= lang(
                    'App.general.submit',
                ) ?></button>
            </div>
        </div>
    </div>
</div>


<div class="toast-container position-fixed top-0 end-0 p-3"></div>

<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel"><?= lang(
                    'App.general.confirmation',
                ) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= lang(
                    'App.general.close',
                ) ?>"></button>
            </div>
            <div class="modal-body" id="confirmModalBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="confirmModalCancelBtn"><?= lang(
                    'App.general.cancel',
                ) ?></button>
                <button type="button" class="btn btn-primary" id="confirmModalOkBtn">OK</button>
            </div>
        </div>
    </div>
</div>

<template id="editor-tab-template">
    <div class="tab-pane fade" role="tabpanel">
        <div class="d-flex flex-column h-100">
            <section class="query-editor-panel d-flex flex-column">
                <div class="p-2 border-bottom d-flex align-items-center flex-wrap">
                    <button class="btn btn-success btn-sm me-2 mb-1 mb-md-0 execute-query-btn">
                        <i class="fa fa-play me-1"></i> <?= lang('App.workspace.execute') ?> (Ctrl+Enter)
                    </button>
                    <button class="btn btn-warning btn-sm me-2 mb-1 mb-md-0 save-changes-btn" style="display: none;">
                        <i class="fa fa-save me-1"></i> <?= lang('App.workspace.save_changes') ?>
                    </button>
                    <button class="btn btn-info btn-sm me-2 mb-1 mb-md-0 explain-query-btn">
                        <i class="fa fa-sitemap me-1"></i> <?= lang('App.workspace.explain') ?>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm me-3 mb-1 mb-md-0 format-sql-btn"
                        title="<?= lang('App.workspace.formatSQL') ?>">
                        <i class="fa fa-align-left"></i>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm me-2 mb-1 mb-md-0 maximize-editor-btn"
                        title="<?= lang('App.workspace.maxEditor') ?>">
                        <i class="fa fa-expand"></i>
                    </button>
                    <div class="btn-group me-3 mb-1 mb-md-0">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle tab-history-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Histórico da Aba">
                            <i class="fa fa-history"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark tab-history-dropdown">
                            </ul>
                    </div>
                    <div class="btn-group me-3 mb-1 mb-md-0">
                        <button class="btn btn-outline-secondary btn-sm export-csv-btn" disabled>
                            <i class="fa fa-file-csv me-1"></i> <?= lang('App.workspace.exportCSV') ?>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm export-json-btn" disabled>
                            <i class="fa fa-file-code me-1"></i> <?= lang('App.workspace.exportJSON') ?>
                        </button>
                    </div>
                    <button class="btn btn-outline-primary btn-sm mb-1 mb-md-0 show-chart-btn" disabled
                        data-bs-toggle="modal" data-bs-target="#chartModal">
                        <i class="fa fa-chart-bar me-1"></i> <?= lang('App.charts.title') ?>
                    </button>
                    <button class="btn btn-outline-info btn-sm ms-auto mb-1 mb-md-0 save-script-btn">
                        <i class="fa fa-save me-1"></i> <?= lang('App.scripts.save') ?>
                    </button>
                    <button class="btn btn-outline-success btn-sm ms-2 mb-1 mb-md-0 share-script-btn">
                        <i class="fa fa-users me-1"></i> <?= lang('App.scripts.share') ?>
                    </button>
                </div>
                <textarea class="query-editor flex-grow-1"></textarea>
            </section>

            <section class="results-panel p-2 d-flex flex-column">
                <div class="pagination-controls pb-2 border-bottom d-flex justify-content-between align-items-center"
                    style="display: none;">
                    <div>
                        <button class="btn btn-sm btn-outline-secondary pagination-prev">&laquo; <?= lang('App.general.previous') ?></button>
                        <button class="btn btn-sm btn-outline-secondary pagination-next"><?= lang('App.general.next') ?> &raquo;</button>
                    </div>
                    <div class="pagination-info" style="font-size: 0.9em;"></div>
                </div>

                <ul class="nav nav-tabs flex-shrink-0 results-tab-nav" role="tablist">
                    <li class="nav-item static-tab" role="presentation">
                        <button class="nav-link messages-tab" data-bs-toggle="tab" data-bs-target=".messages-pane" type="button" role="tab"><?= lang('App.workspace.messages') ?></button>
                    </li>
                    <li class="nav-item static-tab" role="presentation">
                        <button class="nav-link plan-tab" data-bs-toggle="tab" data-bs-target=".execution-plan-pane" type="button" role="tab"><?= lang('App.workspace.explain') ?></button>
                    </li>
                </ul>
                <div class="tab-content flex-grow-1 results-tab-content" style="overflow: auto;">
                    <div class="results-placeholder p-3">
                        <?= lang('App.workspace.queryResultsPlaceholder') ?>
                    </div>
                    <div class="tab-pane fade h-100 messages-pane show active" role="tabpanel">
                        <div class="messages-content text-monospace h-100"></div>
                    </div>
                    <div class="tab-pane fade h-100 p-2 execution-plan-pane" role="tabpanel"></div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    const themeManager = {
        /**
         * Aplica o tema especificado (light ou dark) ao corpo do documento
         * e atualiza o ícone do botão de alternância de tema.
         * Também salva a preferência no localStorage.
         * @param {string} theme - 'light' ou 'dark'
         */
        applyTheme: function (theme) {
            const isLight = (theme === 'light');
            document.body.classList.toggle("light-theme", isLight);

            document.getElementById("theme-toggle-btn").innerHTML = isLight ?
                '<i class="fa fa-moon"></i>' :
                '<i class="fa fa-sun"></i>';

            localStorage.setItem("querysphere_theme", theme);

            // Percorre todas as abas existentes e atualiza o tema de cada editor.
            if (typeof TabManager !== 'undefined' && TabManager.tabs) {
                const newTheme = isLight ? "default" : "material-darker";
                for (const paneId in TabManager.tabs) {
                    if (TabManager.tabs.hasOwnProperty(paneId)) {
                        const tab = TabManager.tabs[paneId];
                        if (tab && tab.editor) {
                            tab.editor.setOption("theme", newTheme);
                        }
                    }
                }
            }
        },

        /**
         * Inicializa o gerenciador de temas, aplicando o tema salvo
         * ou detectando a preferência do sistema.
         */
        init: function () {
            const savedTheme = localStorage.getItem("querysphere_theme");

            if (savedTheme) {
                this.applyTheme(savedTheme);
            } else if (window.matchMedia && window.matchMedia("(prefers-color-scheme: light)").matches) {
                this.applyTheme("light");
            } else {
                this.applyTheme("dark");
            }
        }
    };

    themeManager.init();

    // language
    <?= view('templates/scripts/lang') ?>
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/sql/sql.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/hint/show-hint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/hint/sql-hint.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/dialog/dialog.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/search/searchcursor.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/search/search.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/scroll/annotatescrollbar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/search/matchesonscrollbar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/search/jump-to-line.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/matchbrackets.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closebrackets.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/matchbrackets.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closebrackets.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/selection/active-line.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sql-formatter@15.3.1/dist/sql-formatter.min.js"></script>
<script src="https://unpkg.com/split.js/dist/split.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="<?= base_url('libs/qp/qp.js') ?>"></script>

<script src="<?= base_url('js/utility.js') ?>"></script>
<script src="<?= base_url('js/tabManager.js') ?>"></script>
<script src="<?= base_url('js/export.js') ?>"></script>
<script src="<?= base_url('js/dbSelectorHandler.js') ?>"></script>
<script src="<?= base_url('js/schemaEditor.js') ?>"></script>
<script src="<?= base_url('js/scriptGenerator.js') ?>"></script>
<script src="<?= base_url('js/notifier.js') ?>"></script>
<script src="<?= base_url('js/confirmModal.js') ?>"></script>
<script src="<?= base_url('js/renderAgentJobs.js') ?>"></script>
<script src="<?= base_url('js/displayJobHistory.js') ?>"></script>
<script src="<?= base_url('js/renderMySqlEvents.js') ?>"></script>
<script src="<?= base_url('js/renderSavedScripts.js') ?>"></script>
<script src="<?= base_url('js/refreshHistory.js') ?>"></script>
<script src="<?= base_url('js/renderSharedScripts.js') ?>"></script>
<script src="<?= base_url('js/renderQueryTemplates.js') ?>"></script>
<script src="<?= base_url('js/objectExplorer.js') ?>"></script>
<script src="<?= base_url('js/jobsHandlers.js') ?>"></script>
<script src="<?= base_url('js/localEventHandlers.js') ?>"></script>



<script>
const DB_TYPE = '<?= $db_type ?? '' ?>';
const sessionDb = '<?= $db_database ?? '' ?>';
var resultsDataTable = null; 
const site_url = '<?= site_url('/') ?>';
window.csrfTokenName = '<?= csrf_token() ?>';
window.csrfTokenValue = '<?= csrf_hash() ?>';

$(async function () {
  $("#theme-toggle-btn").on("click", () => {
    themeManager.applyTheme(
      $("body").hasClass("light-theme") ? "dark" : "light",
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
});
</script>

</body>

</html>