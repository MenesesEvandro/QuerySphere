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

            if (typeof editor !== "undefined" && editor) {
                editor.setOption("theme", isLight ? "default" : "material-darker");
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sql-formatter@15.3.1/dist/sql-formatter.min.js"></script>
<script src="https://unpkg.com/split.js/dist/split.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
<script src="<?= base_url('libs/qp/qp.js') ?>"></script>

<script src="<?= base_url('js/editor.js') ?>"></script>
<script src="<?= base_url('js/export.js') ?>"></script>
<script src="<?= base_url('js/utility.js') ?>"></script>
<script src="<?= base_url('js/dbSelectorHandler.js') ?>"></script>
<script src="<?= base_url('js/editableGrid.js') ?>"></script>
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
<script src="<?= base_url('js/updateChartButtonAndOptions.js') ?>"></script>
<script src="<?= base_url('js/executeQuery.js') ?>"></script>
<script src="<?= base_url('js/renderQueryTemplates.js') ?>"></script>
<script src="<?= base_url('js/initializeIntellisense.js') ?>"></script>
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
  initializeIntellisense();
  renderQueryTemplates();
  renderSharedScripts();
  if (DB_TYPE === "sqlsrv") {
    renderAgentJobs();
  } else if (DB_TYPE === "mysql") {
    renderMySqlEvents();
  }
});
</script>

</body>

</html>