</div>
<div class="modal fade" id="chartModal" tabindex="-1" aria-labelledby="chartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chartModalLabel"><?= lang(
                    'App.chartModalTitle',
                ) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="<?= lang('App.close') ?>"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-4"><label for="chart-type"
                            class="form-label"><?= lang(
                                'App.chartType',
                            ) ?></label><select id="chart-type"
                            class="form-select">
                            <option value="bar"><?= lang('App.bar') ?></option>
                            <option value="line"><?= lang(
                                'App.line',
                            ) ?></option>
                            <option value="pie"><?= lang('App.pie') ?></option>
                        </select></div>
                    <div class="col-md-4"><label for="chart-label-col"
                            class="form-label"><?= lang(
                                'App.chartLabelAxis',
                            ) ?></label><select id="chart-label-col"
                            class="form-select"></select></div>
                    <div class="col-md-4"><label for="chart-value-col"
                            class="form-label"><?= lang(
                                'App.chartValueAxis',
                            ) ?></label><select id="chart-value-col"
                            class="form-select"></select></div>
                </div>
                <div class="mt-3" style="position: relative; height:60vh; width:100%"><canvas id="myChart"></canvas>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal"><?= lang('App.close') ?></button>
                <button type="button" id="generate-chart-btn"
                    class="btn btn-primary"><?= lang(
                        'App.chartGenerate',
                    ) ?></button>
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
        applyTheme: function(theme) {
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
        init: function() {
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
<script src="https://unpkg.com/vue@3"></script>
<script src="https://cdn.jsdelivr.net/npm/pev2@2.5.1/dist/js/pev2.min.js"></script>


<script>
    const LANG = {
        executing: "<?= lang('App.executing') ?>",
        execute: "<?= lang('App.execute') ?>",
        changing: "<?= lang('App.changing') ?>",
        error_alter_database: "<?= lang('App.error_alter_database') ?>",
        intellisense_error: "<?= lang('App.intellisense_error') ?>",
        confirm_delete_script: "<?= lang('App.confirm_delete_script') ?>",
        prompt_script_name: "<?= lang('App.prompt_script_name') ?>",
        script_name_default: "<?= lang('App.script_name_default') ?>",
        empty_script_alert: "<?= lang('App.empty_script_alert') ?>",
        empty_shared_script_alert: "<?= lang(
            'App.empty_shared_script_alert',
        ) ?>",
        prompt_shared_name: "<?= lang('App.prompt_shared_name') ?>",
        shared_name_default: "<?= lang('App.shared_name_default') ?>",
        prompt_author: "<?= lang('App.prompt_author') ?>",
        author_default: "<?= lang('App.author_default') ?>",
        share_fail: "<?= lang('App.share_fail') ?>",
        confirm_delete_shared: "<?= lang('App.confirm_delete_shared') ?>",
        delete_shared_fail: "<?= lang('App.delete_shared_fail') ?>",
        format_fail: "<?= lang('App.format_fail') ?>",
        exec_error: "<?= lang('App.exec_error') ?>",
        no_results_found: "<?= lang('App.no_results_found') ?>",
        empty_result: "<?= lang('App.empty_result') ?>",
        page: "<?= lang('App.page') ?>",
        of: "<?= lang('App.of') ?>",
        records: "<?= lang('App.records') ?>",
        result: "<?= lang('App.result') ?>",
        loading_definition_for: "<?= lang('App.loading_definition_for') ?>",
        error_loading_definition: "<?= lang('App.error_loading_definition') ?>",
    };

    var lastResultData = null;
    var myChart = null;
    var currentSql = '';
    var resultsDataTable = null;

    var editor = CodeMirror.fromTextArea(document.getElementById("query-editor"), {
        lineNumbers: true,
        mode: 'text/x-mssql',
        theme: 'material-darker',
        indentWithTabs: true,
        smartIndent: true,
        extraKeys: {
            "Ctrl-Space": "autocomplete",
            "Ctrl-Enter": (cm) => $("#execute-query-btn").click(),
            "F5": (cm) => $("#execute-query-btn").click(),
        },
        hintOptions: {
            tables: {}
        }
    });
    editor.setSize("100%", "100%");
    editor.focus();

    function exportToCsv(filename, headers, data) {
        const csvRows = [headers.join(',')];
        for (const row of data) {
            const values = headers.map(header => `"${('' + row[header]).replace(/"/g, '""')}"`);
            csvRows.push(values.join(','));
        }
        const blob = new Blob([csvRows.join('\n')], {
            type: 'text/csv;charset=utf-8;'
        });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function exportToJson(filename, data) {
        const jsonStr = JSON.stringify(data, null, 2);
        const blob = new Blob([jsonStr], {
            type: 'application/json;charset=utf-8;'
        });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function getSavedScripts() {
        return JSON.parse(localStorage.getItem('querysphere_scripts')) || [];
    }

    function renderSavedScripts() {
        const scripts = getSavedScripts();
        const list = $("#saved-scripts-list");
        list.empty();
        if (scripts && scripts.length > 0) {
            scripts.forEach((script, index) => {
                list.append(
                    `<li class="list-group-item list-group-item-action p-2 d-flex justify-content-between align-items-center">
                        <span class="load-script" data-index="${index}" title="${script.sql}" style="cursor:pointer; font-size: 0.8rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            ${script.name}
                        </span>
                        <button class="btn btn-sm btn-outline-danger delete-script" data-index="${index}" title="Apagar Script">
                            <i class="fa fa-trash"></i>
                        </button>
                    </li>`
                );
            });
        } else {
            list.append('<li class="list-group-item text-muted">Nenhum script salvo.</li>');
        }
    }

    function refreshHistory() {
        $.get('<?= site_url('api/history/get') ?>', (history) => {
            const list = $("#query-history-list");
            list.empty();
            if (history && history.length > 0) {
                history.forEach(query => {
                    list.append(
                        `<li class="list-group-item list-group-item-action p-2" style="cursor:pointer; font-size: 0.8rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${query}" data-query="${query}">
                            ${query}
                        </li>`
                    );
                });
            }
        });
    }

    function updateChartButtonAndOptions(resultIndex) {
        if (!lastResultData || !lastResultData.results || !lastResultData.results[resultIndex]) {
            $("#show-chart-btn").prop("disabled", true);
            return;
        }

        const result = lastResultData.results[resultIndex];
        let numericCols = [];
        let categoryCols = [];

        if (result.data && result.data.length > 0) {
            const firstRow = result.data[0];
            result.headers.forEach(header => {
                const value = firstRow[header];
                if (value !== null && !isNaN(parseFloat(value)) && isFinite(value)) {
                    numericCols.push(header);
                } else {
                    categoryCols.push(header);
                }
            });
        }

        if (numericCols.length > 0 && categoryCols.length > 0) {
            $("#show-chart-btn").prop("disabled", false);
            $("#chart-label-col").html(categoryCols.map(col => `<option>${col}</option>`).join(""));
            $("#chart-value-col").html(numericCols.map(col => `<option>${col}</option>`).join(""));
        } else {
            $("#show-chart-btn").prop("disabled", true);
        }
    }

    function executeQuery(sql, page = 1) {
        currentSql = sql;
        const btn = $('#execute-query-btn');
        btn.prop('disabled', true).html(`<span class="spinner-border spinner-border-sm"></span> ${LANG.executing}`);
        $('#export-csv-btn, #export-json-btn, #show-chart-btn').prop('disabled', true);
        $('#pagination-controls').hide();

        const resultsTabContainer = $('#resultsTab');
        const resultsContentContainer = $('#resultsTabContent');

        if (resultsDataTable) {
            resultsDataTable.destroy();
        }
        $('.dynamic-tab-pane').find('.table-responsive').empty();
        resultsTabContainer.find('.dynamic-tab').remove();
        resultsContentContainer.find('.dynamic-tab-pane').remove();
        resultsContentContainer.children('.tab-pane').removeClass('show active');
        resultsTabContainer.find('.nav-link').removeClass('active');
        $('#results-placeholder').hide();
        $('#messages-content').html('');

        $.ajax({
            url: '<?= site_url('api/query/execute') ?>',
            method: 'POST',
            data: {
                sql: sql,
                page: page,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: (response) => {
                $('#messages-content').html(`<pre class="p-2 m-0">${response.message}</pre>`);
                refreshHistory();

                if (!response.results || response.results.length === 0) {
                    $('#results-placeholder').html(LANG.no_results_found).show();
                    new bootstrap.Tab(document.getElementById('messages-tab')).show();
                    return;
                }

                lastResultData = response;
                $('#export-csv-btn, #export-json-btn').prop('disabled', false);

                response.results.forEach((result, index) => {
                    const tabId = `result-tab-${index}`;
                    const paneId = `result-pane-${index}`;
                    const tableId = `result-table-${index}`;

                    resultsTabContainer.prepend(
                        `<li class="nav-item dynamic-tab" role="presentation">
                            <button class="nav-link" id="${tabId}" data-bs-toggle="tab" data-bs-target="#${paneId}" type="button" role="tab">
                                ${LANG.result} ${index + 1} <span class="badge bg-secondary ms-1">${result.rowCount}</span>
                            </button>
                        </li>`
                    );

                    const tabPane = $(
                        `<div class="tab-pane fade dynamic-tab-pane" id="${paneId}" role="tabpanel">
                            <div id="results-table-container-${index}" class="table-responsive h-100"></div>
                        </div>`
                    );
                    resultsContentContainer.append(tabPane);

                    if (result.data && result.data.length > 0) {
                        let table = $(`<table id="${tableId}" class="table table-sm table-bordered table-striped table-hover" style="width:100%"></table>`);
                        let thead = $('<thead></thead>').append($('<tr></tr>'));
                        let tfoot = $('<tfoot></tfoot>').append($('<tr></tr>'));
                        result.headers.forEach(h => {
                            thead.find('tr').append($('<th></th>').text(h));
                            tfoot.find('tr').append($('<th></th>').text(h));
                        });
                        table.append(thead).append(tfoot);
                        $(`#results-table-container-${index}`).append(table);

                        resultsDataTable = new DataTable(`#${tableId}`, {
                            data: result.data,
                            columns: result.headers.map(h => ({ data: h })),
                            destroy: true,
                            language: { url: '//cdn.datatables.net/plug-ins/2.0.8/i18n/pt-BR.json' },
                            initComplete: function () {
                                this.api().columns().every(function () {
                                    let column = this;
                                    let title = column.footer().textContent;
                                    $(column.footer()).html(`<input type="text" class="form-control form-control-sm" placeholder="Buscar ${title}" />`)
                                        .on('keyup change clear', function () {
                                            if (column.search() !== this.value) {
                                                column.search(this.value).draw();
                                            }
                                        });
                                });
                            }
                        });
                    } else {
                        $(`#results-table-container-${index}`).html(`<p class="p-2 text-muted">${LANG.empty_result}</p>`);
                    }
                });

                const firstResult = response.results[0];
                if (firstResult.hasOwnProperty('totalRows')) {
                    $('#pagination-controls').css('display', 'flex');
                    $('#pagination-info').text(`${LANG.page} ${firstResult.currentPage} ${LANG.of} ${firstResult.totalPages} (${firstResult.totalRows} ${LANG.records})`);
                    $('#pagination-prev').prop('disabled', firstResult.currentPage <= 1);
                    $('#pagination-next').prop('disabled', firstResult.currentPage >= firstResult.totalPages);
                }

                const firstTabButton = document.getElementById('result-tab-0');
                if (firstTabButton) {
                    new bootstrap.Tab(firstTabButton).show();
                }
                updateChartButtonAndOptions(0);
            },
            error: (xhr) => {
                const errorMsg = xhr.responseJSON?.messages?.error?.message || xhr.responseText || "Ocorreu um erro.";
                $('#messages-content').html(
                    `<div class="alert alert-danger m-2">
                        <h5 class="alert-heading">${LANG.exec_error}</h5>
                        <hr>
                        <p class="mb-0">${$('<div>').text(errorMsg).html()}</p>
                    </div>`
                );
                new bootstrap.Tab(document.getElementById('messages-tab')).show();
            },
            complete: () => btn.prop('disabled', false).html(`<i class="fa fa-play me-1"></i> ${LANG.execute} (Ctrl+Enter)`)
        });
    }

    $(function () {
        function initializeIntellisense() {
            $.get('<?= site_url('api/intellisense') ?>', (data) => {
                if (Object.keys(data).length > 0) {
                    editor.setOption("hintOptions", { tables: data });
                }
            }).fail(() => console.error(LANG.intellisense_error));
        }

        if (document.getElementById('object-explorer-panel')) {
            Split(['#object-explorer-panel', '.main-panel'], {
                sizes: [20, 80],
                minSize: 280,
                gutterSize: 7,
                direction: 'horizontal',
                cursor: 'col-resize'
            });
            Split(['#query-editor-panel', '#results-panel'], {
                sizes: [65, 35],
                minSize: 100,
                gutterSize: 7,
                direction: 'vertical',
                cursor: 'row-resize',
                onDragEnd: function() {
                    if (editor) editor.refresh();
                }
            });
        }

        $('#object-explorer-tree').jstree({
            'core': {
                'data': {
                    'url': (node) => "#" === node.id ? '<?= site_url(
                        'api/objects/databases',
                    ) ?>' : '<?= site_url('api/objects/children') ?>',
                    'data': (node) => ({ id: node.id })
                }
            },
            'plugins': ["contextmenu", "search"],
            'search': {
                'ajax': {
                    'url': '<?= site_url('api/objects/search') ?>',
                    'data': (str) => ({ str: str })
                },
                'show_only_matches': true,
                'close_opened_onclear': true
            },
            'contextmenu': {
                'items': (node) => {
                    let menu = {};
                    const nodeData = node.data;
                    if (nodeData) {
                        if (nodeData.type === "table" || nodeData.type === "view") {
                            menu.selectTop1000 = {
                                label: "SELECT TOP 1000 Rows",
                                icon: "fa fa-bolt",
                                action: () => {
                                    const { db, schema, table } = nodeData;
                                    editor.setValue(`SELECT TOP 1000 *\nFROM [${db}].[${schema}].[${table}]`);
                                }
                            };
                        }
                        if (nodeData.type === "procedure" || nodeData.type === "function") {
                            menu.scriptAsExecute = {
                                label: "Script as EXECUTE",
                                icon: "fa fa-play-circle",
                                action: () => {
                                    const { db, schema, routine } = nodeData;
                                    $.get('<?= site_url(
                                        'api/objects/children',
                                    ) ?>', { id: node.id }, params => {
                                        let script = `EXEC [${db}].[${schema}].[${routine}]\n`;
                                        if (params && params.length > 0 && params[0].id) {
                                            script += params.map(p => `    ${p.text.split(" ")[0]} = ?`).join(",\n");
                                        }
                                        editor.setValue(script);
                                    });
                                }
                            };
                        }
                        menu.scriptAsAlter = {
                            label: "Script as ALTER",
                            icon: "fa fa-pencil-alt",
                            _separator_before: true,
                            action: () => {
                                const { db, schema, routine, type } = nodeData;
                                const objectName = `${schema}.${routine}`;
                                editor.setValue(`-- ${LANG.loading_definition_for.replace("{0}", objectName)}`);
                                $.get("<?= site_url(
                                    'api/objects/source',
                                ) ?>", { db: db, schema: schema, object: routine, type: type })
                                    .done(function(data) {
                                        editor.setValue(data.sql);
                                    })
                                    .fail(function() {
                                        editor.setValue(`-- ${LANG.error_loading_definition}`);
                                    });
                            }
                        };
                    }
                    return menu;
                }
            }
        });

        refreshHistory();
        renderSavedScripts();
        initializeIntellisense();

        $('#db-selector-list').on('click', 'a', function(e) {
            e.preventDefault();
            const dbName = $(this).data('dbname');
            const currentDbName = $('#active-database-name').text().trim();
            if (dbName && dbName !== currentDbName) {
                $('#active-database-name').text(LANG.changing);
                $.ajax({
                    url: '<?= site_url('api/session/database') ?>',
                    method: 'POST',
                    data: {
                        database: dbName,
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    success: () => window.location.reload(),
                    error: () => {
                        alert(LANG.error_alter_database);
                        $('#active-database-name').text(currentDbName);
                    }
                });
            }
        });

        let searchTimeout = false;
        $('#object-search-input').on('keyup', function() {
            if (searchTimeout) clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = $("#object-search-input").val();
                const tree = $("#object-explorer-tree").jstree(true);
                if (searchTerm.length >= 3) {
                    tree.search(searchTerm);
                } else {
                    tree.clear_search();
                }
            }, 300);
        });

        $('#theme-toggle-btn').on('click', () => {
            const isLight = document.body.classList.contains("light-theme");
            themeManager.applyTheme(isLight ? "dark" : "light");
        });

        $('#query-history-list').on('click', 'li', function() {
            const query = $(this).data("query");
            if (query) editor.setValue(query);
        });

        $('#saved-scripts-list').on('click', '.load-script', function() {
            const index = $(this).data("index");
            const scripts = getSavedScripts();
            if (scripts[index]) editor.setValue(scripts[index].sql);
        });

        $('#saved-scripts-list').on('click', '.delete-script', function() {
            const index = $(this).data("index");
            let scripts = getSavedScripts();
            if (confirm(LANG.confirm_delete_script.replace('{0}', scripts[index].name))) {
                scripts.splice(index, 1);
                localStorage.setItem("querysphere_scripts", JSON.stringify(scripts));
                renderSavedScripts();
            }
        });

        $('#save-script-btn').on('click', () => {
            const sql = editor.getValue();
            if (sql.trim() === "") return alert(LANG.empty_script_alert);
            const name = prompt(LANG.prompt_script_name, LANG.script_name_default);
            if (name) {
                const scripts = getSavedScripts();
                scripts.unshift({ name: name, sql: sql });
                localStorage.setItem("querysphere_scripts", JSON.stringify(scripts));
                renderSavedScripts();
            }
        });

        $('#share-script-btn').on('click', function() {
            const sql = editor.getValue();
            if (sql.trim() === '') return alert(LANG.empty_shared_script_alert);
            const name = prompt(LANG.prompt_shared_name, LANG.shared_name_default);
            if (!name) return;
            const author = prompt(LANG.prompt_author, LANG.author_default);
            if (!author) return;

            $.post('<?= site_url(
                'api/shared-queries',
            ) ?>', { name, author, sql, '<?= csrf_token() ?>': '<?= csrf_hash() ?>' })
                .done(() => renderSharedScripts())
                .fail(() => alert(LANG.share_fail));
        });

        $('#shared-scripts-list').on('click', '.load-shared-script', function() {
            editor.setValue($(this).data('sql'));
        });

        $('#shared-scripts-list').on('click', '.delete-shared-script', function() {
            const queryId = $(this).data('id');
            if (confirm(LANG.confirm_delete_shared)) {
                $.ajax({
                    url: `<?= site_url('api/shared-queries') ?>/${queryId}`,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '<?= csrf_hash() ?>' },
                    success: () => renderSharedScripts(),
                    error: () => alert(LANG.delete_shared_fail)
                });
            }
        });

        $('#format-sql-btn').on('click', () => {
            try {
                editor.setValue(sqlFormatter.format(editor.getValue(), {
                    language: "tsql",
                    tabWidth: 4,
                    keywordCase: "upper"
                }));
            } catch (e) {
                alert(LANG.format_fail);
            }
        });

        $('#resultsTab').on('shown.bs.tab', 'button.dynamic-tab', function(event) {
            const activeTabIndex = parseInt(event.target.id.split("-")[2]);
            updateChartButtonAndOptions(activeTabIndex);
        });

        $('#export-csv-btn').on('click', function() {
            if (!lastResultData) return;
            const activeTabIndex = $('#resultsTab button.dynamic-tab.active').attr('id')?.split('-')[2] || 0;
            const activeResult = lastResultData.results[activeTabIndex];
            if (activeResult) {
                exportToCsv(`export_result_${parseInt(activeTabIndex) + 1}.csv`, activeResult.headers, activeResult.data);
            }
        });

        $('#export-json-btn').on('click', function() {
            if (!lastResultData) return;
            const activeTabIndex = $('#resultsTab button.dynamic-tab.active').attr('id')?.split('-')[2] || 0;
            const activeResult = lastResultData.results[activeTabIndex];
            if (activeResult) {
                exportToJson(`export_result_${parseInt(activeTabIndex) + 1}.json`, activeResult.data);
            }
        });

        $('#execute-query-btn').on('click', () => {
            executeQuery(editor.getSelection() || editor.getValue(), 1);
        });

        $('#pagination-prev').on('click', () => {
            const result = lastResultData.results[0];
            if (result && result.currentPage > 1) {
                executeQuery(currentSql, result.currentPage - 1);
            }
        });

        $('#pagination-next').on('click', () => {
            const result = lastResultData.results[0];
            if (result && result.currentPage < result.totalPages) {
                executeQuery(currentSql, result.currentPage + 1);
            }
        });

        $('#generate-chart-btn').on('click', () => {
            const activeTabIndex = $('#resultsTab button.dynamic-tab.active').attr('id')?.split('-')[2] || 0;
            const activeResult = lastResultData?.results[activeTabIndex];
            if (!activeResult) return;

            const type = $('#chart-type').val();
            const labelCol = $('#chart-label-col').val();
            const valueCol = $('#chart-value-col').val();

            const labels = activeResult.data.map(row => row[labelCol]);
            const values = activeResult.data.map(row => parseFloat(row[valueCol]));
            const ctx = document.getElementById('myChart').getContext('2d');

            if (myChart) myChart.destroy();

            myChart = new Chart(ctx, {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: valueCol,
                        data: values,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });

        $('#explain-query-btn').on('click', function() {
            const sql = editor.getSelection() || editor.getValue();
            if (sql.trim() === '') return;

            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: '<?= site_url('api/query/explain') ?>',
                method: 'POST',
                data: {
                    sql: sql,
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                success: (response) => {
                    const planContainer = $('#execution-plan');
                    planContainer.empty();
                    Pev2.render(response.plan, planContainer);
                    new bootstrap.Tab(document.getElementById('plan-tab')).show();
                },
                error: (xhr) => {
                    const errorMsg = xhr.responseJSON?.messages?.error?.message || xhr.responseText || "Ocorreu um erro.";
                    $('#messages-content').html(
                        `<div class="alert alert-danger m-2">
                            <h5 class="alert-heading">${LANG.exec_error}</h5>
                            <hr>
                            <p class="mb-0">${$('<div>').text(errorMsg).html()}</p>
                        </div>`
                    );
                    new bootstrap.Tab(document.getElementById('messages-tab')).show();
                },
                complete: () => btn.prop('disabled', false).html(
                    `<i class="fa fa-sitemap me-1"></i> <?= lang(
                        'App.explain',
                    ) ?>`
                )
            });
        });
    });
</script>

</body>
</html>