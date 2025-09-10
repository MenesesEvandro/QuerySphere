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




<script>
    const LANG = {
        executing: "<?= lang('App.workspace.workspace') ?>",
        execute: "<?= lang('App.workspace.execute') ?>",
        changing: "<?= lang('App.connection.changing') ?>",
        error_alter_database: "<?= lang(
            'App.feedback.error_alter_database',
        ) ?>",
        intellisense_error: "<?= lang('App.feedback.intellisense_error') ?>",
        confirm_delete_script: "<?= lang('App.scripts.confirm_delete') ?>",
        prompt_script_name: "<?= lang('App.scripts.prompt_name') ?>",
        script_name_default: "<?= lang('App.scripts.default_name') ?>",
        empty_script_alert: "<?= lang('App.scripts.empty_alert') ?>",
        empty_shared_script_alert: "<?= lang(
            'App.scripts.empty_shared_alert',
        ) ?>",
        prompt_shared_name: "<?= lang('App.scripts.prompt_shared_name') ?>",
        shared_name_default: "<?= lang('App.scripts.shared_default_name') ?>",
        prompt_author: "<?= lang('App.scripts.prompt_author') ?>",
        author_default: "<?= lang('App.scripts.author_default') ?>",
        share_fail: "<?= lang('App.scripts.share_fail') ?>",
        confirm_delete_shared: "<?= lang(
            'App.scripts.confirm_delete_shared',
        ) ?>",
        delete_shared_fail: "<?= lang('App.scripts.delete_shared_fail') ?>",
        format_fail: "<?= lang('App.feedback.format_fail') ?>",
        exec_error: "<?= lang('App.feedback.exec_error') ?>",
        no_results_found: "<?= lang('App.feedback.no_results_found') ?>",
        empty_result: "<?= lang('App.feedback.empty_result') ?>",
        page: "<?= lang('App.general.page') ?>",
        of: "<?= lang('App.general.of') ?>",
        records: "<?= lang('App.general.records') ?>",
        result: "<?= lang('App.general.result') ?>",
        loading_definition_for: "<?= lang(
            'App.objects_browser.loading_definition_for',
        ) ?>",
        error_loading_definition: "<?= lang(
            'App.feedback.error_loading_definition',
        ) ?>",
        search_placeholder: "<?= lang('App.workspace.search_placeholder') ?>",
        no_templates: "<?= lang('App.feedback.no_templates') ?>",
        loading: "<?= lang('App.general.loading') ?>",
        job_name: "<?= lang('App.agent.job_name') ?>",
        status: "<?= lang('App.agent.status') ?>",
        last_run: "<?= lang('App.agent.last_run') ?>",
        last_run_status: "<?= lang('App.agent.last_run_status') ?>",
        next_run: "<?= lang('App.agent.next_run') ?>",
        actions: "<?= lang('App.general.actions') ?>",
        start_job: "<?= lang('App.agent.start_job') ?>",
        stop_job: "<?= lang('App.agent.stop_job') ?>",
        running: "<?= lang('App.general.running') ?>",
        enabled: "<?= lang('App.general.enabled') ?>",
        disabled: "<?= lang('App.general.disabled') ?>",
        success: "<?= lang('App.general.success') ?>",
        failed: "<?= lang('App.general.failed') ?>",
        unknown: "<?= lang('App.general.unknown') ?>",
        no_jobs_found: "<?= lang('App.agent.no_jobs_found') ?>",
        view_job_history: "<?= lang('App.agent.history') ?>",
        no_history_found: "<?= lang('App.agent.no_history_found') ?>",
        error_retrieving_history: "<?= lang(
            'App.agent.error_retrieving_history',
        ) ?>",
        retry: "<?= lang('App.general.retry') ?>",
        canceled: "<?= lang('App.general.canceled') ?>",
        job_started: "<?= lang('App.agent.job_started') ?>",
        job_start_failed: "<?= lang('App.agent.job_start_failed') ?>",
        job_stopped: "<?= lang('App.agent.job_stopped') ?>",
        job_stop_failed: "<?= lang('App.agent.job_stop_failed') ?>",
    };

    var lastResultData = null;
    var myChart = null;
    var currentSql = '';
    var resultsDataTable = null;
    var isTemplateQuery = false;

    var editor = CodeMirror.fromTextArea(document.getElementById("query-editor"), {
        lineNumbers: true,
        mode: 'text/x-mssql',
        theme: 'material-darker',
        indentWithTabs: true,
        smartIndent: true,
        extraKeys: {
            "Ctrl-Space": "autocomplete",
            "Alt-Space": "autocomplete",
            "Ctrl-Enter": (cm) => $("#execute-query-btn").click(),
            "F5": (cm) => $("#execute-query-btn").click(),
        },
        hintOptions: {
            tables: {}
        }
    });
    editor.setSize("100%", "100%");
    editor.focus();

    editor.on('change', function() {
        isTemplateQuery = false;
    });

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

    function renderAgentJobs() {
        const container = $('#agent-jobs-container');
        container.html('<div class="text-center p-3"><div class="spinner-border" role="status"><span class="visually-hidden">'+ LANG.loading + '</span></div></div>');

        $.get('<?= site_url('api/agent/jobs') ?>', function(jobs) {
            container.empty();
            if (jobs && jobs.length > 0) {
                const table = $('<table class="table table-sm table-hover"></table>');
                const thead = $('<thead><tr><th>' + LANG.job_name + '</th><th>' + LANG.status + '</th><th>' + LANG.last_run + '</th><th>' + LANG.last_run_status + '</th><th>' + LANG.next_run + '</th><th>' + LANG.actions + '</th></tr></thead>');
                const tbody = $('<tbody></tbody>');

                jobs.forEach(job => {
                    const status = job.enabled ? (job.last_run_step === 'Running' ? '<span class="badge bg-primary">' + LANG.running + '</span>' : '<span class="badge bg-success">' + LANG.enabled + '</span>') : '<span class="badge bg-secondary">' + LANG.disabled + '</span>';
                    const outcome = job.run_status === 1 ? '<span class="text-success">' + LANG.success + '</span>' : (job.run_status === 0 ? '<span class="text-danger">' + LANG.failed + '</span>' : LANG.unknown);
                    const lastRun = job.last_run_datetime ? new Date(job.last_run_datetime.date).toLocaleString() : 'N/A';
                    const nextRun = job.next_run_date && job.next_run_date !== '1900-01-01 00:00:00.000' ? new Date(job.next_run_date + ' ' + job.next_run_time).toLocaleString() : 'N/A';

                    const row = `<tr>
                        <td><a href="#" class="view-job-history" data-job-name="${job.job_name}">${job.job_name}</a></td>
                        <td>${status}</td>
                        <td>${lastRun}</td>
                        <td>${outcome}</td>
                        <td>${nextRun}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-success start-job" data-job-name="${job.job_name}" title="${LANG.start_job}"><i class="fa fa-play"></i></button>
                            <button class="btn btn-sm btn-outline-danger stop-job" data-job-name="${job.job_name}" title="${LANG.stop_job}"><i class="fa fa-stop"></i></button>
                        </td>
                    </tr>`;
                    tbody.append(row);
                });

                table.append(thead).append(tbody);
                container.append(table);
            } else {
                container.html('<p class="text-muted p-2">' + LANG.no_jobs_found + '</p>');
            }
        });
    }

    function displayJobHistory(jobName) {
        const resultsTabContainer = $('#resultsTab');
        const resultsContentContainer = $('#resultsTabContent');
        const tabId = `job-history-tab-${jobName.replace(/\s/g, '-')}`;
        const paneId = `job-history-pane-${jobName.replace(/\s/g, '-')}`;

        $(`#${tabId}`).closest('.nav-item').remove();
        $(`#${paneId}`).remove();

        resultsTabContainer.append(
            `<li class="nav-item dynamic-tab" role="presentation">
                <button class="nav-link" id="${tabId}" data-bs-toggle="tab" data-bs-target="#${paneId}" type="button" role="tab">
                    History: ${$('<div>').text(jobName).html()}
                </button>
            </li>`
        );

        const tabPane = $(
            `<div class="tab-pane fade dynamic-tab-pane" id="${paneId}" role="tabpanel">
                <div class="p-2">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>`
        );
        resultsContentContainer.append(tabPane);

        new bootstrap.Tab(document.getElementById(tabId)).show();

        $.get(`<?= site_url(
            'api/agent/history',
        ) ?>/${encodeURIComponent(jobName)}`, function(history) {
            const container = $(`#${paneId}`);
            container.empty();

            if (history && history.length > 0) {
                const table = $('<table class="table table-sm table-bordered table-striped"></table>');
                const thead = $('<thead><tr><th>Run Datetime</th><th>Step Name</th><th>Duration</th><th>Outcome</th><th>Message</th></tr></thead>');
                const tbody = $('<tbody></tbody>');

                history.forEach(item => {
                    const runDateTime = item.run_datetime ? new Date(item.run_datetime.date).toLocaleString() : 'N/A';
                    const outcome = formatRunStatus(item.run_status);
                    const duration = formatDuration(item.run_duration);

                    const row = `<tr>
                        <td>${runDateTime}</td>
                        <td>${item.step_name}</td>
                        <td>${duration}</td>
                        <td>${outcome}</td>
                        <td><div style="max-height: 100px; overflow-y: auto;">${item.message}</div></td>
                    </tr>`;
                    tbody.append(row);
                });

                table.append(thead).append(tbody);
                container.append(table);
            } else {
                container.html('<p class="text-muted p-2">' + LANG.no_history_found + '</p>');
            }
        }).fail(function() {
            const container = $(`#${paneId}`);
            container.html('<p class="text-danger p-2">' + LANG.error_retrieving_history + '</p>');
        });
    }

    function formatRunStatus(status) {
        switch (status) {
            case 0: return '<span class="badge bg-danger">'+ LANG.failed +'</span>';
            case 1: return '<span class="badge bg-success">'+ LANG.success +'</span>';
            case 2: return '<span class="badge bg-info">'+ LANG.retry +'</span>';
            case 3: return '<span class="badge bg-warning text-dark">'+ LANG.canceled +'</span>';
            default: return '<span class="badge bg-secondary">'+ LANG.unknown +'</span>';
        }
    }

    function formatDuration(duration) {
        if (duration === null) return 'N/A';
        let str = duration.toString().padStart(6, '0');
        let h = str.substring(0, 2);
        let m = str.substring(2, 4);
        let s = str.substring(4, 6);
        return `${h}:${m}:${s}`;
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

    function renderSharedScripts() {
        const list = $('#shared-scripts-list');
        list.html('<li class="list-group-item text-muted">' + LANG.loading + '</li>');
        
        $.get('<?= site_url('api/shared-queries') ?>', function(scripts) {
            list.empty();
            if (scripts && scripts.length > 0) {
                scripts.forEach(script => {
                    const itemDate = new Date(script.timestamp).toLocaleDateString('pt-BR');
                    const item = `
                        <li class="list-group-item list-group-item-action p-2">
                            <div class="d-flex w-100 justify-content-between">
                                <span class="load-shared-script" data-sql="${script.sql}" style="cursor:pointer; font-weight: 500;">
                                    ${$('<div>').text(script.name).html()}
                                </span>
                                <button class="btn btn-sm btn-outline-danger delete-shared-script" data-id="${script.id}" title="Apagar Script">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                            <small class="text-muted">Por: ${$('<div>').text(script.author).html()} em ${itemDate}</small>
                        </li>`;
                    list.append(item);
                });
            } else {
                list.append('<li class="list-group-item text-muted">Nenhuma query compartilhada.</li>');
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
        
        if (isTemplateQuery) {
           postData.disable_pagination = true;
        }

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
                            searching: true,
                            initComplete: function () {
                                this.api().columns().every(function () {
                                    let column = this;
                                    let title = column.footer().textContent;
                                    $(column.footer()).html(`<input type="text" class="form-control form-control-sm" placeholder="${LANG.search_placeholder.replace('{0}', title)}" />`)
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

    function renderQueryTemplates() {
    const accordion = $('#query-templates-accordion');
    accordion.html('<div class="p-2 text-muted">' + LANG.loading + '</div>');

    $.get('<?= site_url('api/templates') ?>', function(categories) {
        accordion.empty();
        if (categories && categories.length > 0) {
            categories.forEach((cat, index) => {
                const categoryId = `category-${index}`;
                const categoryHtml = `
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-${categoryId}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${categoryId}">
                                ${$('<div>').text(cat.category).html()}
                            </button>
                        </h2>
                        <div id="collapse-${categoryId}" class="accordion-collapse collapse" data-bs-parent="#query-templates-accordion">
                            <div class="list-group list-group-flush">
                                ${cat.scripts.map(script => `
                                    <a href="#" class="list-group-item list-group-item-action load-template" 
                                       data-category="${script.category_key}" 
                                       data-filename="${script.filename}" 
                                       title="${$('<div>').text(script.description).html()}">
                                        ${$('<div>').text(script.name).html()}
                                    </a>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;
                accordion.append(categoryHtml);
            });
        } else {
            accordion.html('<div class="p-2 text-muted">${LANG.no_templates}</div>');
        }
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
                onDragEnd: function () {
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
                        if (nodeData && (nodeData.type === "table" || nodeData.type === "view")) {
                            menu.selectTop1000 = {
                                label: "SELECT TOP 1000 Rows",
                                icon: "fa fa-bolt",
                                action: () => {
                                    const { db, schema, table } = nodeData;
                                    
                                    editor.setValue(`-- ${LANG.loading}`);

                                    $.get('<?= site_url(
                                        'api/objects/columns',
                                    ) ?>', { db, table })
                                        .done(function(columns) {
                                            if (columns && columns.length > 0) {
                                                const columnList = columns.map(col => `    [${col}]`).join(',\n');
                                                
                                                const sql = `SELECT TOP 1000\n${columnList}\nFROM [${db}].[${schema}].[${table}]`;
                                                editor.setValue(sql);
                                            } else {
                                                editor.setValue(`SELECT TOP 1000 *\nFROM [${db}].[${schema}].[${table}]`);
                                            }
                                        })
                                        .fail(function() {
                                            editor.setValue(`-- ${LANG.error_loading_definition}\n\nSELECT TOP 1000 *\nFROM [${db}].[${schema}].[${table}]`);
                                        });
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
                                    .done(function (data) {
                                        editor.setValue(data.sql);
                                    })
                                    .fail(function () {
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
        renderQueryTemplates();
        renderSharedScripts();
        renderAgentJobs();

        $('#agent-jobs-container').on('click', '.start-job', function() {
            const jobName = $(this).data('job-name');
            $.post('<?= site_url(
                'api/agent/start',
            ) ?>', { 'job_name': jobName, '<?= csrf_token() ?>': '<?= csrf_hash() ?>' }, function() {
                alert(jobName + ': ' + LANG.job_started);
                renderAgentJobs();
            }).fail(function() {
                alert(jobName + ': ' + LANG.job_start_failed);
            });
        });

        $('#agent-jobs-container').on('click', '.stop-job', function() {
            const jobName = $(this).data('job-name');
            $.post('<?= site_url(
                'api/agent/stop',
            ) ?>', { 'job_name': jobName, '<?= csrf_token() ?>': '<?= csrf_hash() ?>' }, function() {
                alert(jobName + ': ' + LANG.job_stopped);
                renderAgentJobs();
            }).fail(function() {
                alert(jobName + ': ' + LANG.job_stop_failed);
            });
        });

        $('#agent-jobs-container').on('click', '.view-job-history', function(e) {
            e.preventDefault();
            const jobName = $(this).data('job-name');
            displayJobHistory(jobName);
        });

        $('#db-selector-list').on('click', 'a', function (e) {
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
        $('#object-search-input').on('keyup', function () {
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

        $('#query-history-list').on('click', 'li', function () {
            const query = $(this).data("query");
            if (query) editor.setValue(query);
        });

        $('#saved-scripts-list').on('click', '.load-script', function () {
            const index = $(this).data("index");
            const scripts = getSavedScripts();
            if (scripts[index]) editor.setValue(scripts[index].sql);
        });

        $('#saved-scripts-list').on('click', '.delete-script', function () {
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

        $('#share-script-btn').on('click', function () {
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

        $('#shared-scripts-list').on('click', '.load-shared-script', function () {
            editor.setValue($(this).data('sql'));
        });

        $('#shared-scripts-list').on('click', '.delete-shared-script', function () {
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

        $('#resultsTab').on('shown.bs.tab', 'button.dynamic-tab', function (event) {
            const activeTabIndex = parseInt(event.target.id.split("-")[2]);
            updateChartButtonAndOptions(activeTabIndex);
        });

        $('#export-csv-btn').on('click', function () {
            if (!lastResultData) return;
            const activeTabIndex = $('#resultsTab button.dynamic-tab.active').attr('id')?.split('-')[2] || 0;
            const activeResult = lastResultData.results[activeTabIndex];
            if (activeResult) {
                exportToCsv(`export_result_${parseInt(activeTabIndex) + 1}.csv`, activeResult.headers, activeResult.data);
            }
        });

        $('#export-json-btn').on('click', function () {
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
    
                    QP.showPlan(planContainer.get(0), response.plan);

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
                        'App.workspace.explain',
                    ) ?>`
                )
            });
        });

        $('#templates-tab').on('click', '.load-template', function (e) {
            e.preventDefault();
            const categoryKey = $(this).data('category');
            const filename = $(this).data('filename');

            $.get(`<?= site_url(
                'api/templates/get',
            ) ?>/${categoryKey}/${filename}`, function(response) {
                let finalSql = response.sql;

                const placeholders = finalSql.match(/'NOME_DA_SUA_TABELA'/g) || finalSql.match(/'schema.NomeDoObjeto'/g) || [];

                if (placeholders.length > 0) {
                    const objectName = prompt("Este script requer um nome de objeto (ex: dbo.MinhaTabela):");

                    if (objectName) {
                        finalSql = finalSql.replace(/'NOME_DA_SUA_TABELA'/g, objectName);
                        finalSql = finalSql.replace(/'schema.NomeDoObjeto'/g, objectName);
                    } else {
                        return; // Usuário cancelou
                    }
                }

                editor.setValue(finalSql);
                isTemplateQuery = true;
            });
        });
    });
</script>

</body>

</html>