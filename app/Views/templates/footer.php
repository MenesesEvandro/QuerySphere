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
                <form id="schema-editor-form">
                    <input type="hidden" id="schema-editor-mode" value="create">
                    <div class="mb-3">
                        <label for="table-name" class="form-label"><?= lang(
                            'App.schema_editor.table_name',
                        ) ?></label>
                        <input type="text" class="form-control" id="table-name" required>
                    </div>
                    <hr>
                    <h6><?= lang('App.schema_editor.columns') ?></h6>
                    <table class="table table-sm">
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
    <?= view('templates/scripts/lang') ?>

$(async function () {
    // Initialize CodeMirror editor
    const editor = CodeMirror.fromTextArea($('#query-editor')[0], {
        lineNumbers: true,
        mode: 'text/x-mssql',
        theme: 'material-darker',
        indentWithTabs: true,
        smartIndent: true,
        extraKeys: {
            "Ctrl-Space": "autocomplete",
            "Alt-Space": "autocomplete",
            "Ctrl-Enter": () => $('#execute-query-btn').trigger('click'),
            "F5": () => $('#execute-query-btn').trigger('click')
        },
        hintOptions: { tables: {} }
    });
    editor.setSize("100%", "100%");
    editor.focus();

    // Editor change event
    editor.on('change', () => {
        isTemplateQuery = false;
    });

    // Export functions
    function exportToCsv(filename, headers, data) {
        const csvRows = [headers.join(',')];
        $.each(data, (i, row) => {
            const values = $.map(headers, header => `"${('' + row[header]).replace(/"/g, '""')}"`);
            csvRows.push(values.join(','));
        });
        const blob = new Blob([csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        
        $('<a>', { href: url, download: filename })
            .css('visibility', 'hidden')
            .appendTo('body')
            .trigger('click')
            .remove();
    }

    function exportToJson(filename, data) {
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        
        $('<a>', { href: url, download: filename })
            .css('visibility', 'hidden')
            .appendTo('body')
            .trigger('click')
            .remove();
    }

    // Utility functions
    function getSavedScripts() {
        return JSON.parse(localStorage.getItem('querysphere_scripts')) || [];
    }

    function formatRunStatus(status) {
        const statusMap = {
            0: `<span class="badge bg-danger">${LANG.failed}</span>`,
            1: `<span class="badge bg-success">${LANG.success}</span>`,
            2: `<span class="badge bg-info">${LANG.retry}</span>`,
            3: `<span class="badge bg-warning text-dark">${LANG.canceled}</span>`
        };
        return statusMap[status] || `<span class="badge bg-secondary">${LANG.unknown}</span>`;
    }

    function formatDuration(duration) {
        if (!duration) return 'N/A';
        const str = duration.toString().padStart(6, '0');
        return `${str.substring(0, 2)}:${str.substring(2, 4)}:${str.substring(4, 6)}`;
    }

    const scriptGenerator = {
        /**
         * Gera um script SELECT TOP 1000 (SQL Server) ou LIMIT 1000 (MySQL).
         * @param {object} nodeData - Os dados do nó da árvore.
         */
        selectTop1000: function(nodeData) {
            const { db, schema, table } = nodeData;
            editor.setValue(`-- ${LANG.loading}...`);

            $.get('<?= site_url('api/objects/columns') ?>', { db, table })
                .done(columns => {
                    let sql;
                    const columnList = columns?.length 
                        ? `\n${$.map(columns, col => `    ${this._quoteIdentifier(col)}`).join(',\n')}\n` 
                        : ' * ';

                    if (DB_TYPE === 'mysql') {
                        sql = `SELECT${columnList}FROM ${this._quoteIdentifier(db)}.${this._quoteIdentifier(table)}\nLIMIT 1000;`;
                    } else { // Padrão para sqlsrv
                        sql = `SELECT TOP 1000${columnList}FROM ${this._quoteIdentifier(db)}.${this._quoteIdentifier(schema)}.${this._quoteIdentifier(table)}`;
                    }
                    editor.setValue(sql);
                })
                .fail(() => {
                    const tableName = DB_TYPE === 'mysql' ? `${this._quoteIdentifier(db)}.${this._quoteIdentifier(table)}` : `${this._quoteIdentifier(db)}.${this._quoteIdentifier(schema)}.${this._quoteIdentifier(table)}`;
                    editor.setValue(`-- ${LANG.error_loading_definition}\n\nSELECT * FROM ${tableName} LIMIT 1000;`);
                });
        },

        /**
         * Gera um script EXEC para uma procedure ou function.
         * @param {object} node - O nó completo da árvore (necessário para o ID).
         */
        execute: function(node) {
            const { db, schema, routine } = node.data;
            const routineName = `${this._quoteIdentifier(db)}.${this._quoteIdentifier(schema)}.${this._quoteIdentifier(routine)}`;
            
            $.get('<?= site_url(
                'api/objects/children',
            ) ?>', { id: node.id }, params => {
                let script = `EXEC ${routineName}\n`;
                if (params?.length && params[0].id) {
                    script += $.map(params, p => `    ${p.text.split(" ")[0]} = ?`).join(",\n");
                }
                editor.setValue(script);
            });
        },

        /**
         * Obtém o script ALTER para um objeto.
         * @param {object} nodeData - Os dados do nó da árvore.
         */
        alter: function(nodeData) {
            const { db, schema, routine, type, table } = nodeData;
            const objectName = routine || table; // Pega o nome do objeto (seja rotina ou tabela)
            const objectFullName = DB_TYPE === 'mysql' ? `${objectName}` : `${schema}.${objectName}`;

            editor.setValue(`-- ${LANG.loading_definition_for.replace("{0}", objectFullName)}`);
            
            $.get('<?= site_url(
                'api/objects/source',
            ) ?>', { db, schema, object: objectName, type })
                .done(data => {
                    let script = data.sql;
                    // A API já retorna o script ALTER, mas podemos garantir aqui
                    if (DB_TYPE !== 'mysql' && script.trim().toUpperCase().startsWith('CREATE')) {
                        script = script.replace(/CREATE/i, 'ALTER');
                    }
                    editor.setValue(script);
                })
                .fail(() => editor.setValue(`-- ${LANG.error_loading_definition}`));
        },

        /**
         * Coloca o identificador entre aspas corretas para o SGBD.
         * @param {string} identifier - O nome do objeto (tabela, coluna, etc.).
         */
        _quoteIdentifier: function(identifier) {
            if (!identifier) return '';
            if (DB_TYPE === 'mysql') {
                return `\`${identifier}\``;
            }
            // Padrão para sqlsrv
            return `[${identifier}]`;
        }
    };

    const editableGrid = {
        enabled: false,
        pkColumn: null,
        dbName: null,
        schemaName: null,
        tableName: null,
        changedData: {},

        init: function(sql, sessionDb) {
            this.reset();

            if (!sessionDb) {
                console.warn(LANG.error_no_db_selected_for_edit);
                console.warn("Editing disabled: No database selected.");
                return;
            }

            const tableInfo = this.parseTableName(sql, sessionDb);
            
            //console.log("Table Info Parsed:", tableInfo);

            if (!tableInfo) {
                console.warn(LANG.no_table_detected);
                return;
            }

            this.dbName = tableInfo.db;
            this.schemaName = tableInfo.schema;
            this.tableName = tableInfo.table;
            
            $.get(`<?= site_url(
                'api/editor/pk',
            ) ?>/${encodeURIComponent(this.dbName)}/${encodeURIComponent(this.tableName)}`)
                .done(data => {
                    this.pkColumn = data.primaryKey;
                    this.enabled = true;
                    console.log(`Editing enabled for table '${this.tableName}' with PK '${this.pkColumn}'`);
                })
                .fail(() => {
                    console.warn(LANG.no_primary_key);
                    this.enabled = false;
                });
        },

        reset: async function() {
            if (Object.keys(this.changedData).length > 0) {
                if (!await showConfirmModal(LANG.confirm_discard_changes)) {
                    return false;
                }
            }
            this.enabled = false;
            this.pkColumn = null;
            this.dbName = null;
            this.schemaName = null;
            this.tableName = null;
            this.changedData = {};
            $('#save-changes-btn').hide();
            $('.datatable-row-changed').removeClass('datatable-row-changed');
            return true;
        },

        parseTableName: function(sql, sessionDb) {
            const cleanSql = sql.replace(/--.*$/gm, '').replace(/\s+/g, ' ').trim();
            
            if (/JOIN\s+/i.test(cleanSql)) {
                console.warn(LANG.multiple_tables_not_supported);
                return null;
            }

            const fromMatch = /FROM\s+([^\s;]+)/i.exec(cleanSql);
            if (!fromMatch || !fromMatch[1]) {
                return null;
            }

            const fullName = fromMatch[1].replace(/[`\[\]]/g, '');
            const parts = fullName.split('.');
            
            let db, schema, table;

            if (DB_TYPE === 'mysql') {
                table = parts[parts.length - 1];
                db = parts.length > 1 ? parts[0] : sessionDb;
                schema = db; // Em MySQL, schema e db são o mesmo.
            } else { // sqlsrv
                table = parts[parts.length - 1];
                schema = parts.length > 1 ? parts[parts.length - 2] : 'dbo';
                db = parts.length > 2 ? parts[0] : sessionDb;
            }

            return { db, schema, table };
        },

        cellDoubleClicked: function(cell) {
            if (!this.enabled) return;
            const td = $(cell);
            if (td.find('input').length > 0) return;

            const originalValue = td.text();
            const input = $('<input type="text" class="form-control form-control-sm">').val(originalValue);
            td.html(input);
            input.focus();

            const finishEditing = () => {
                const newValue = input.val();
                td.text(newValue);

                if (newValue !== originalValue) {
                    const row = td.closest('tr');
                    const rowData = resultsDataTable.row(row).data();
                    const pkValue = rowData[this.pkColumn];
                    const columnName = resultsDataTable.column(td.index()).header().textContent;

                    if (columnName === this.pkColumn) {
                        notifier.show(LANG.no_pk_edit, 'warning');
                        td.text(originalValue);
                        return;
                    }

                    if (!this.changedData[pkValue]) {
                        this.changedData[pkValue] = { changes: {} };
                    }
                    this.changedData[pkValue].changes[columnName] = newValue;
                    
                    row.addClass('datatable-row-changed');
                    $('#save-changes-btn').show();
                }
            };

            input.on('blur', finishEditing);
            input.on('keydown', e => {
                if (e.key === 'Enter') input.blur();
                if (e.key === 'Escape') td.text(originalValue);
            });
        },

        save: function() {
            const btn = $('#save-changes-btn');
            btn.prop('disabled', true).html(`<span class="spinner-border spinner-border-sm"></span> ${LANG.saving_changes}`);

            const promises = Object.entries(this.changedData).map(([pkValue, data]) => {
                const payload = {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
                    database: this.dbName,
                    schema: this.schemaName,
                    table: this.tableName,
                    pkColumn: this.pkColumn,
                    pkValue: pkValue,
                    changes: data.changes
                };

                return $.post('<?= site_url('api/editor/update') ?>', payload);
            });

            Promise.all(promises)
                .then(() => {
                    notifier.show(LANG.data_saved, 'success');
                    this.changedData = {};
                    $('#save-changes-btn').hide();
                    $('.datatable-row-changed').removeClass('datatable-row-changed');
                })
                .catch(err => {
                    const errorMsg = err.responseJSON?.messages?.error || LANG.error_saving;
                    notifier.show(errorMsg, 'error');
                })
                .finally(() => {
                    btn.prop('disabled', false).html(`<i class="fa fa-save me-1"></i> ${LANG.save_changes}`);
                });
        }
    };

    const schemaEditor = {
        modal: new bootstrap.Modal(document.getElementById('schema-editor-modal')),
        mode: 'create',
        dbName: null,
        schemaName: null,

        open: function(mode, nodeData) {
            this.mode = mode;
            this.dbName = nodeData?.db || sessionDb;
            this.schemaName = nodeData?.schema || (DB_TYPE === 'sqlsrv' ? 'dbo' : this.dbName);

            const form = $('#schema-editor-form')[0];
            form.reset();
            $('#columns-container').empty();
            $('#save-table-btn').show();

            if (mode === 'create') {
                $('#schemaEditorModalLabel').text(LANG.new_table);
                $('#table-name').val('').prop('disabled', false);
                this.addColumnRow();
            } else { 
                $('#schemaEditorModalLabel').text(`${LANG.design_table}: ${nodeData.table}`);
                $('#table-name').val(nodeData.table).prop('disabled', true);
                

                const { db, schema, table } = nodeData;
                $.get(`<?= site_url(
                    'api/schema/structure',
                ) ?>/${encodeURIComponent(db)}/${encodeURIComponent(schema)}/${encodeURIComponent(table)}`)
                    .done(columns => {
                        if (columns && columns.length > 0) {
                            columns.forEach(col => this.addColumnRow(col));
                        } else {
                            this.addColumnRow();
                        }
                    })
                    .fail(() => {
                        notifier.show('Error: Could not load table structure.', 'error');
                        this.modal.hide();
                    });
            }
            
            this.modal.show();
        },

        addColumnRow: function(column = {}) {
            const isNullable = column.nullable == 1;
            const isPk = column.is_pk == 1;

            const checked = isNullable ? 'checked' : '';
            const pkChecked = isPk ? 'checked' : '';

            const newRow = `
                <tr>
                    <td class="text-center align-middle"><input class="form-check-input pk-radio" type="radio" name="primary_key" ${pkChecked}></td>
                    <td><input type="text" class="form-control form-control-sm" name="col_name" value="${column.name || ''}" required></td>
                    <td><input type="text" class="form-control form-control-sm" name="col_type" value="${column.type || 'VARCHAR'}" required></td>
                    <td><input type="text" class="form-control form-control-sm" name="col_size" value="${column.size || '255'}"></td>
                    <td class="text-center align-middle"><input class="form-check-input" type="checkbox" name="col_nullable" ${checked}></td>
                    <td class="text-center align-middle"><button type="button" class="btn btn-sm btn-outline-danger remove-column-btn"><i class="fa fa-trash"></i></button></td>
                </tr>
            `;
            $('#columns-container').append(newRow);
        },

        save: function() {
            const tableName = $('#table-name').val();
            if (!tableName) {
                notifier.show('Table name is required.', 'warning');
                return;
            }

            const columns = [];
            let primaryKey = null;
            $('#columns-container tr').each(function(index, row) {
                const $row = $(row);
                const colName = $row.find('input[name="col_name"]').val();
                if (colName) {
                    columns.push({
                        name: colName,
                        type: $row.find('input[name="col_type"]').val(),
                        size: $row.find('input[name="col_size"]').val(),
                        nullable: $row.find('input[name="col_nullable"]').is(':checked')
                    });
                    if ($row.find('.pk-radio').is(':checked')) {
                        primaryKey = colName;
                    }
                }
            });

            if (columns.length === 0) {
                notifier.show('A table must have at least one column.', 'warning');
                return;
            }

            const payload = {
                database: this.dbName,
                schema: this.schemaName,
                table: tableName,
                columns: columns,
                primaryKey: primaryKey
            };

            $.ajax({
                url: '<?= site_url('api/schema/create') ?>',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(payload),
                headers: { 'X-CSRF-TOKEN': '<?= csrf_hash() ?>' },
                success: (response) => {
                    notifier.show(LANG.table_created_successfully.replace('{0}', tableName), 'success');
                    this.modal.hide();
                    $('#object-explorer-tree').jstree(true).refresh();
                },
                error: (xhr) => {
                    const errorMsg = xhr.responseJSON?.messages?.error || LANG.table_creation_failed;
                    notifier.show(errorMsg, 'error');
                }
            });
        }
    };

    const notifier = {
        toastContainer: $('.toast-container'),
        show: function(message, type = 'info', delay = 5000) {
            const iconMap = {
                success: 'fa-check-circle',
                error: 'fa-times-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };
            const bgMap = {
                success: 'bg-success',
                error: 'bg-danger',
                warning: 'bg-warning',
                info: 'bg-info'
            };

            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white ${bgMap[type]}" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="${delay}">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fa ${iconMap[type]} me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;

            this.toastContainer.append(toastHtml);
            const toastElement = new bootstrap.Toast(document.getElementById(toastId));
            
            // Remove o elemento do DOM depois de o toast ser escondido
            document.getElementById(toastId).addEventListener('hidden.bs.toast', function () {
                this.remove();
            });

            toastElement.show();
        }
    };

    const confirmModal = {
        modal: new bootstrap.Modal(document.getElementById('confirmModal')),
        show: function(message, title = 'Confirmation') {
            return new Promise((resolve, reject) => {
                const modalTitle = document.getElementById('confirmModalLabel');
                const modalBody = document.getElementById('confirmModalBody');
                const okBtn = document.getElementById('confirmModalOkBtn');
                const cancelBtn = document.getElementById('confirmModalCancelBtn');

                modalTitle.textContent = title;
                modalBody.textContent = message;

                const onOk = () => {
                    cleanup();
                    resolve(true);
                };

                const onCancel = () => {
                    cleanup();
                    reject(false);
                };

                const cleanup = () => {
                    this.modal.hide();
                    okBtn.removeEventListener('click', onOk);
                    cancelBtn.removeEventListener('click', onCancel);
                };

                okBtn.addEventListener('click', onOk, { once: true });
                cancelBtn.addEventListener('click', onCancel, { once: true });
                
                this.modal.show();
            });
        }
    };

    // Alias
    const showConfirmModal = (message, title) => confirmModal.show(message, title);

    // Render functions
    function renderAgentJobs() {
        const $container = $('#agent-jobs-container').html(
            `<div class="text-center p-3"><div class="spinner-border" role="status"><span class="visually-hidden">${LANG.loading}</span></div></div>`
        );

        $.get('<?= site_url('api/agent/jobs') ?>', jobs => {
            $container.empty();
            if (jobs?.length) {
                const $table = $('<table class="table table-sm table-hover"></table>')
                    .append(`<thead><tr><th>${LANG.job_name}</th><th>${LANG.status}</th><th>${LANG.last_run}</th><th>${LANG.last_run_status}</th><th>${LANG.next_run}</th><th>${LANG.actions}</th></tr></thead>`)
                    .append('<tbody></tbody>');

                $.each(jobs, (i, job) => {
                    const status = job.enabled ? 
                        (job.last_run_step === 'Running' ? 
                            `<span class="badge bg-primary">${LANG.running}</span>` : 
                            `<span class="badge bg-success">${LANG.enabled}</span>`) : 
                        `<span class="badge bg-secondary">${LANG.disabled}</span>`;
                    const outcome = job.run_status === 1 ? 
                        `<span class="text-success">${LANG.success}</span>` : 
                        (job.run_status === 0 ? `<span class="text-danger">${LANG.failed}</span>` : LANG.unknown);
                    const lastRun = job.last_run_datetime ? new Date(job.last_run_datetime.date).toLocaleString() : 'N/A';
                    const nextRun = job.next_run_date && job.next_run_date !== '1900-01-01 00:00:00.000' ? 
                        new Date(job.next_run_date + ' ' + job.next_run_time).toLocaleString() : 'N/A';

                    $table.find('tbody').append(`
                        <tr>
                            <td><a href="#" class="view-job-history" data-job-name="${job.job_name}">${job.job_name}</a></td>
                            <td>${status}</td>
                            <td>${lastRun}</td>
                            <td>${outcome}</td>
                            <td>${nextRun}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-success start-job" data-job-name="${job.job_name}" title="${LANG.start_job}"><i class="fa fa-play"></i></button>
                                <button class="btn btn-sm btn-outline-danger stop-job" data-job-name="${job.job_name}" title="${LANG.stop_job}"><i class="fa fa-stop"></i></button>
                            </td>
                        </tr>
                    `);
                });
                $container.append($table);
            } else {
                $container.html(`<p class="text-muted p-2">${LANG.no_jobs_found}</p>`);
            }
        });
    }

    function displayJobHistory(jobName) {
        const $tabContainer = $('#resultsTab');
        const $contentContainer = $('#resultsTabContent');
        const tabId = `job-history-tab-${jobName.replace(/\s/g, '-')}`;
        const paneId = `job-history-pane-${jobName.replace(/\s/g, '-')}`;

        $(`#${tabId}`).closest('.nav-item').remove();
        $(`#${paneId}`).remove();

        $tabContainer.append(`
            <li class="nav-item dynamic-tab" role="presentation">
                <button class="nav-link" id="${tabId}" data-bs-toggle="tab" data-bs-target="#${paneId}" type="button" role="tab">
                    History: ${$('<div>').text(jobName).html()}
                </button>
            </li>
        `);

        $contentContainer.append(`
            <div class="tab-pane fade dynamic-tab-pane" id="${paneId}" role="tabpanel">
                <div class="p-2">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        `);

        new bootstrap.Tab($(`#${tabId}`)[0]).show();

        $.get(`<?= site_url(
            'api/agent/history',
        ) ?>/${encodeURIComponent(jobName)}`, history => {
            const $container = $(`#${paneId}`).empty();
            if (history?.length) {
                const $table = $('<table class="table table-sm table-bordered table-striped"></table>')
                    .append(`<thead><tr><th>Run Datetime</th><th>Step Name</th><th>Duration</th><th>Outcome</th><th>Message</th></tr></thead>`)
                    .append('<tbody></tbody>');

                $.each(history, (i, item) => {
                    const runDateTime = item.run_datetime ? new Date(item.run_datetime.date).toLocaleString() : 'N/A';
                    $table.find('tbody').append(`
                        <tr>
                            <td>${runDateTime}</td>
                            <td>${item.step_name}</td>
                            <td>${formatDuration(item.run_duration)}</td>
                            <td>${formatRunStatus(item.run_status)}</td>
                            <td><div style="max-height: 100px; overflow-y: auto;">${item.message}</div></td>
                        </tr>
                    `);
                });
                $container.append($table);
            } else {
                $container.html(`<p class="text-muted p-2">${LANG.no_history_found}</p>`);
            }
        }).fail(() => {
            $(`#${paneId}`).html(`<p class="text-danger p-2">${LANG.error_retrieving_history}</p>`);
        });
    }

    function renderMySqlEvents() {
        const $container = $('#mysql-events-container').html(
            `<div class="text-center p-3"><div class="spinner-border" role="status"><span class="visually-hidden">${LANG.loading}</span></div></div>`
        );

        $.get('<?= site_url('api/mysql/events') ?>', events => {
            $container.empty();
            if (events?.length) {
                const $table = $('<table class="table table-sm table-hover"></table>')
                    .append(`<thead><tr><th>${LANG.event_name}</th><th>${LANG.status}</th><th>${LANG.next_run}</th><th>${LANG.actions}</th></tr></thead>`)
                    .append('<tbody></tbody>');

                $.each(events, (i, event) => {
                    const status = event.status === 'ENABLED' ? 
                        `<span class="badge bg-success">${LANG.enabled}</span>` : 
                        `<span class="badge bg-secondary">${LANG.general.disabled}</span>`;
                    const nextRun = event.next_execution ? new Date(event.next_execution).toLocaleString() : 'N/A';
                    const toggleAction = event.status === 'ENABLED' ? LANG.disable : LANG.enable;
                    const toggleIcon = event.status === 'ENABLED' ? 'fa-stop-circle' : 'fa-play-circle';
                    const toggleTitle = event.status === 'ENABLED' ? LANG.disable_event : LANG.enable_event;

                    $table.find('tbody').append(`
                        <tr>
                            <td><a href="#" class="view-event-definition" data-event-name="${event.name}">${event.name}</a></td>
                            <td>${status}</td>
                            <td>${nextRun}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary toggle-event-status" 
                                    data-event-name="${event.name}" 
                                    data-status="${toggleAction}" 
                                    title="${toggleTitle}">
                                    <i class="fa ${toggleIcon}"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                });
                $container.append($table);
            } else {
                $container.html(`<p class="text-muted p-2">${LANG.no_events_found}</p>`);
            }
        });
    }

    function renderSavedScripts() {
        const scripts = getSavedScripts();
        const $list = $("#saved-scripts-list").empty();
        if (scripts?.length) {
            $.each(scripts, (index, script) => {
                $list.append(`
                    <li class="list-group-item list-group-item-action p-2 d-flex justify-content-between align-items-center">
                        <span class="load-script" data-index="${index}" title="${script.sql}" style="cursor:pointer; font-size: 0.8rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            ${script.name}
                        </span>
                        <button class="btn btn-sm btn-outline-danger delete-script" data-index="${index}" title="Apagar Script">
                            <i class="fa fa-trash"></i>
                        </button>
                    </li>
                `);
            });
        } else {
            $list.append('<li class="list-group-item text-muted">Nenhum script salvo.</li>');
        }
    }

    function refreshHistory() {
        $.get('<?= site_url('api/history/get') ?>', history => {
            const $list = $("#query-history-list").empty();
            if (history?.length) {
                $.each(history, (i, query) => {
                    $list.append(`
                        <li class="list-group-item list-group-item-action p-2" 
                            style="cursor:pointer; font-size: 0.8rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" 
                            title="${query}" 
                            data-query="${query}">
                            ${query}
                        </li>
                    `);
                });
            }
        });
    }

    function renderSharedScripts() {
        const $list = $('#shared-scripts-list').html(`<li class="list-group-item text-muted">${LANG.loading}</li>`);
        $.get('<?= site_url('api/shared-queries') ?>', scripts => {
            $list.empty();
            if (scripts?.length) {
                $.each(scripts, (i, script) => {
                    const itemDate = new Date(script.timestamp).toLocaleDateString('pt-BR');
                    $list.append(`
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
                        </li>
                    `);
                });
            } else {
                $list.append('<li class="list-group-item text-muted">Nenhuma query compartilhada.</li>');
            }
        });
    }

    function updateChartButtonAndOptions(resultIndex) {
        if (!lastResultData?.results?.[resultIndex]) {
            $("#show-chart-btn").prop("disabled", true);
            return;
        }

        const result = lastResultData.results[resultIndex];
        let numericCols = [];
        let categoryCols = [];

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

        $("#show-chart-btn").prop("disabled", !(numericCols.length && categoryCols.length));
        $("#chart-label-col").html($.map(categoryCols, col => `<option>${col}</option>`).join(""));
        $("#chart-value-col").html($.map(numericCols, col => `<option>${col}</option>`).join(""));
    }

    async function executeQuery(sql, page = 1) {
        if (typeof editableGrid !== 'undefined' && !editableGrid.reset()) {
            return; // Aborta a execução se o utilizador cancelar
        }

        currentSql = sql;
        const $btn = $('#execute-query-btn')
            .prop('disabled', true)
            .html(`<span class="spinner-border spinner-border-sm"></span> ${LANG.executing}`);
        
        $('#export-csv-btn, #export-json-btn, #show-chart-btn').prop('disabled', true);
        $('#pagination-controls').hide();

        if (resultsDataTable) resultsDataTable.destroy();
        $('#resultsTab .dynamic-tab, #resultsTabContent .dynamic-tab-pane').remove();
        $('#results-placeholder').hide();
        $('#messages-content').empty();

        $.ajax({
            url: '<?= site_url('api/query/execute') ?>',
            method: 'POST',
            data: {
                sql: sql,
                page: page,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: response => {
                $('#messages-content').html(`<pre class="p-2 m-0">${response.message}</pre>`);
                refreshHistory();

                if (!response.results?.length) {
                    $('#results-placeholder').html(LANG.no_results_found).show();
                    new bootstrap.Tab($('#messages-tab')[0]).show();
                    return;
                }

                lastResultData = response;
                $('#export-csv-btn, #export-json-btn').prop('disabled', false);

                $.each(response.results, (index, result) => {
                    const tabId = `result-tab-${index}`;
                    const paneId = `result-pane-${index}`;
                    const tableId = `result-table-${index}`;

                    $('#resultsTab').prepend(`
                        <li class="nav-item dynamic-tab" role="presentation">
                            <button class="nav-link" id="${tabId}" data-bs-toggle="tab" data-bs-target="#${paneId}" type="button" role="tab">
                                ${LANG.result} ${index + 1} <span class="badge bg-secondary ms-1">${result.rowCount}</span>
                            </button>
                        </li>
                    `);

                    $('#resultsTabContent').append(`
                        <div class="tab-pane fade dynamic-tab-pane" id="${paneId}" role="tabpanel">
                            <div id="results-table-container-${index}" class="table-responsive h-100"></div>
                        </div>
                    `);

                    if (result.data?.length) {
                        const $table = $(`<table id="${tableId}" class="table table-sm table-bordered table-striped table-hover" style="width:100%"></table>`);
                        const $thead = $('<thead><tr></tr></thead>');
                        const $tfoot = $('<tfoot><tr></tr></tfoot>');
                        $.each(result.headers, (i, h) => {
                            $thead.find('tr').append(`<th>${h}</th>`);
                            $tfoot.find('tr').append(`<th>${h}</th>`);
                        });
                        $table.append($thead).append($tfoot);
                        $(`#results-table-container-${index}`).append($table);

                        resultsDataTable = new DataTable(`#${tableId}`, {
                            data: result.data,
                            columns: $.map(result.headers, h => ({ data: h })),
                            destroy: true,
                            searching: true,
                            initComplete: function() {
                                this.api().columns().every(function() {
                                    const column = this;
                                    const title = column.footer().textContent;
                                    $(column.footer()).html(
                                        `<input type="text" class="form-control form-control-sm" placeholder="${LANG.search_placeholder.replace('{0}', title)}" />`
                                    ).on('keyup change clear', function() {
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

                if ($('#result-tab-0').length) {
                    new bootstrap.Tab($('#result-tab-0')[0]).show();
                }
                updateChartButtonAndOptions(0);

                if (response.results.length === 1 && response.results[0].data.length > 0) {
                    if (typeof editableGrid !== 'undefined') {
                        editableGrid.init(sql, '<?= $db_database ?>');
                    }
                }
            },
            error: xhr => {
                const errorMsg = xhr.responseJSON?.messages?.error?.message || xhr.responseText || "Ocorreu um erro.";
                $('#messages-content').html(`
                    <div class="alert alert-danger m-2">
                        <h5 class="alert-heading">${LANG.exec_error}</h5>
                        <hr>
                        <p class="mb-0">${$('<div>').text(errorMsg).html()}</p>
                    </div>
                `);
                new bootstrap.Tab($('#messages-tab')[0]).show();
            },
            complete: () => $btn.prop('disabled', false).html(`<i class="fa fa-play me-1"></i> ${LANG.execute} (Ctrl+Enter)`)
        });
    }

    function renderQueryTemplates() {
        const $accordion = $('#query-templates-accordion').html(`<div class="p-2 text-muted">${LANG.loading}</div>`);
        $.get('<?= site_url('api/templates') ?>', categories => {
            $accordion.empty();
            if (categories?.length) {
                $.each(categories, (index, cat) => {
                    const categoryId = `category-${index}`;
                    $accordion.append(`
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-${categoryId}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${categoryId}">
                                    ${$('<div>').text(cat.category).html()}
                                </button>
                            </h2>
                            <div id="collapse-${categoryId}" class="accordion-collapse collapse" data-bs-parent="#query-templates-accordion">
                                <div class="list-group list-group-flush">
                                    ${$.map(cat.scripts, script => `
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
                    `);
                });
            } else {
                $accordion.html(`<div class="p-2 text-muted">${LANG.no_templates}</div>`);
            }
        });
    }

    function initializeIntellisense() {
        $.get('<?= site_url('api/intellisense') ?>', data => {
            if (Object.keys(data).length) {
                editor.setOption("hintOptions", { tables: data });
            }
        }).fail(() => console.error(LANG.intellisense_error));
    }

    // Initialize UI components
    if ($('#object-explorer-panel').length) {
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
            onDragEnd: () => editor.refresh()
        });
    }

    $('#object-explorer-tree').jstree({
        core: {
            data: {
                url: node => node.id === '#' ? '<?= site_url(
                    'api/objects/databases',
                ) ?>' : '<?= site_url('api/objects/children') ?>',
                data: node => ({ id: node.id })
            }
        },
        plugins: ["contextmenu", "search"],
        search: {
            ajax: {
                url: '<?= site_url('api/objects/search') ?>',
                data: str => ({ str })
            },
            show_only_matches: true,
            close_opened_onclear: true
        },
        contextmenu: {
            items: node => {
                const menu = {};
                const nodeData = node.data;
                if (!nodeData) return menu;

                if (nodeData?.type === 'database' || nodeData?.type === 'folder_tables') {
                    menu.createTable = {
                        label: LANG.new_table,
                        icon: "fa fa-plus-square",
                        action: () => {
                            let dbName;
                            if (nodeData.type === 'database') {
                                dbName = node.text;
                            } else {
                                const parentNode = $('#object-explorer-tree').jstree(true).get_node(node.parent);
                                dbName = parentNode.text;
                            }
                            schemaEditor.open('create', { db: dbName });
                        }
                    };
                }

                if (nodeData.type === "table" || nodeData.type === "view") {
                    menu.selectTop1000 = {
                        label: DB_TYPE === 'mysql' ? "SELECT LIMIT 1000 Rows" : "SELECT TOP 1000 Rows",
                        icon: "fa fa-bolt",
                        action: () => scriptGenerator.selectTop1000(nodeData)
                    };
                }

                if (nodeData.type === "table") {
                    menu.designTable = {
                        label: LANG.design_table,
                        icon: "fa fa-edit",
                        action: () => schemaEditor.open('design', nodeData)
                    };
                    menu.dropTable = {
                        label: LANG.drop_table,
                        icon: "fa fa-trash-alt",
                        _separator_before: true,
                        action: async () => {
                            const { db, schema, table } = nodeData;
                            try {
                                await showConfirmModal(LANG.schema_editor.confirm_drop_table.replace('{0}', table));
                                
                                const payload = { database: db, schema: schema, table: table };
                                $.ajax({
                                    url: '<?= site_url('api/schema/drop') ?>',
                                    method: 'DELETE',
                                    contentType: 'application/json',
                                    data: JSON.stringify(payload),
                                    headers: { 'X-CSRF-TOKEN': '<?= csrf_hash() ?>' },
                                    success: () => {
                                        notifier.show(`Table "${table}" dropped successfully.`, 'success');
                                        $('#object-explorer-tree').jstree(true).refresh_node(node.parent);
                                    },
                                    error: (xhr) => {
                                        const errorMsg = xhr.responseJSON?.messages?.error || 'Failed to drop table.';
                                        notifier.show(errorMsg, 'error');
                                    }
                                });
                            } catch (e) {
                                console.log('Drop table operation canceled by user.');
                            }
                        }
                    };
                }

                if (nodeData.type === "procedure" || nodeData.type === "function") {
                    if (DB_TYPE === 'sqlsrv') {
                        menu.scriptAsExecute = {
                            label: LANG.script_execute,
                            icon: "fa fa-play-circle",
                            action: () => scriptGenerator.execute(node)
                        };
                    }
                }
                
                if (nodeData.type !== 'database' && !nodeData.type.startsWith('folder')) {
                    menu.scriptAsAlterOrCreate = {
                        label: DB_TYPE === 'mysql' ? LANG.script_create : LANG.script_alter,
                        icon: "fa fa-pencil-alt",
                        _separator_before: true,
                        action: () => scriptGenerator.alter(nodeData)
                    };
                }

                return menu;
            }
        }
    });

    // Event handlers
    $('#agent-jobs-container').on('click', '.start-job', function() {
        const jobName = $(this).data('job-name');
        $.post('<?= site_url('api/agent/start') ?>', {
            job_name: jobName,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }).done(() => {
            notifier.show(`${jobName}: ${LANG.job_started}`, 'success');
            renderAgentJobs();
        }).fail(() => notifier.show(`${jobName}: ${LANG.job_start_failed}`, 'error'));
    });

    $('#agent-jobs-container').on('click', '.stop-job', function() {
        const jobName = $(this).data('job-name');
        $.post('<?= site_url('api/agent/stop') ?>', {
            job_name: jobName,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }).done(() => {
            notifier.show(`${jobName}: ${LANG.job_stopped}`, 'success');
            renderAgentJobs();
        }).fail(() => notifier.show(`${jobName}: ${LANG.job_stop_failed}`, 'error'));
    });

    $('#agent-jobs-container').on('click', '.view-job-history', function(e) {
        e.preventDefault();
        displayJobHistory($(this).data('job-name'));
    });

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
                    notifier.show(LANG.error_alter_database, 'error');
                    $('#active-database-name').text(currentDbName);
                }
            });
        }
    });

    let searchTimeout = false;
    $('#object-search-input').on('keyup', function() {
        if (searchTimeout) clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const searchTerm = $(this).val();
            const tree = $("#object-explorer-tree").jstree(true);
            if (searchTerm.length >= 3) {
                tree.search(searchTerm);
            } else {
                tree.clear_search();
            }
        }, 300);
    });

    $('#theme-toggle-btn').on('click', () => {
        themeManager.applyTheme($('body').hasClass("light-theme") ? "dark" : "light");
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

    $('#saved-scripts-list').on('click', '.delete-script', async function () {
        const index = $(this).data("index");
        const scripts = getSavedScripts();
        try {
            await showConfirmModal(LANG.scripts.confirm_delete_script.replace('{0}', scripts[index].name));

            scripts.splice(index, 1);
            localStorage.setItem("querysphere_scripts", JSON.stringify(scripts));
            renderSavedScripts();
        } catch (e) {
            console.log('Delete script operation canceled.');
        }
    });

    $('#save-script-btn').on('click', () => {
        const sql = editor.getValue();
        if (!sql.trim()) return notifier.show(LANG.empty_script_alert, 'error');
        const name = prompt(LANG.prompt_script_name, LANG.script_name_default);
        if (name) {
            const scripts = getSavedScripts();
            scripts.unshift({ name, sql });
            localStorage.setItem("querysphere_scripts", JSON.stringify(scripts));
            renderSavedScripts();
        }
    });

    $('#share-script-btn').on('click', function() {
        const sql = editor.getValue();
        if (!sql.trim()) return notifier.show(LANG.empty_shared_script_alert, 'error');
        const name = prompt(LANG.prompt_shared_name, LANG.shared_name_default);
        if (!name) return;
        const author = prompt(LANG.prompt_author, LANG.author_default);
        if (!author) return;

        $.post('<?= site_url('api/shared-queries') ?>', {
            name, author, sql,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }).done(() => renderSharedScripts()).fail(() => notifier.show(LANG.share_fail, 'error'));
    });

    $('#shared-scripts-list').on('click', '.load-shared-script', function() {
        editor.setValue($(this).data('sql'));
    });

    $('#shared-scripts-list').on('click', '.delete-shared-script', async function () {
    const queryId = $(this).data('id');
    try {
        await showConfirmModal(LANG.scripts.confirm_delete_shared);
        $.ajax({
            url: `<?= site_url('api/shared-queries') ?>/${queryId}`,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '<?= csrf_hash() ?>' },
            success: () => {
                notifier.show(LANG.feedback.delete_shared_success, 'success');
                renderSharedScripts();
            },
            error: () => notifier.show(LANG.feedback.delete_shared_fail, 'error')
        });
    } catch (e) {
        console.log('Delete shared query operation canceled.');
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
            notifier.show(LANG.format_fail, 'error');
        }
    });

    $('#resultsTab').on('shown.bs.tab', 'button.dynamic-tab', function(event) {
        updateChartButtonAndOptions(parseInt(event.target.id.split("-")[2]));
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

        const labels = $.map(activeResult.data, row => row[labelCol]);
        const values = $.map(activeResult.data, row => parseFloat(row[valueCol]));

        const ctx = $('#myChart').get(0).getContext('2d');
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
        if (!sql.trim()) return;

        const $btn = $(this).prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            url: '<?= site_url('api/query/explain') ?>',
            method: 'POST',
            data: {
                sql: sql,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: response => {
                const $planContainer = $('#execution-plan').empty();
                if (response.db_type === 'mysql') {
                    $planContainer.append(
                        $('<pre style="white-space: pre-wrap; word-wrap: break-word;"></pre>')
                            .text(JSON.stringify(JSON.parse(response.plan), null, 2))
                    );
                } else {
                    QP.showPlan($planContainer.get(0), response.plan);
                }
                new bootstrap.Tab($('#plan-tab')[0]).show();
            },
            error: xhr => {
                const errorMsg = xhr.responseJSON?.messages?.error?.message || xhr.responseText || "Ocorreu um erro.";
                $('#messages-content').html(`
                    <div class="alert alert-danger m-2">
                        <h5 class="alert-heading">${LANG.exec_error}</h5>
                        <hr>
                        <p class="mb-0">${$('<div>').text(errorMsg).html()}</p>
                    </div>
                `);
                new bootstrap.Tab($('#messages-tab')[0]).show();
            },
            complete: () => $btn.prop('disabled', false).html(
                `<i class="fa fa-sitemap me-1"></i> <?= lang(
                    'App.workspace.explain',
                ) ?>`
            )
        });
    });

    $('body').on('click', '.toggle-event-status', function() {
        const eventName = $(this).data('event-name');
        const status = $(this).data('status');
        
        $.post('<?= site_url('api/mysql/events/toggle') ?>', { 
            event_name: eventName, 
            status: status, 
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }).done(() => renderMySqlEvents()).fail(() => notifier.show(`Failed to update event ${eventName}`, 'error'));
    });

    $('body').on('click', '.view-event-definition', function(e) {
        e.preventDefault();
        const eventName = $(this).data('event-name');
        editor.setValue(`-- Loading definition for event ${eventName}...`);
        
        $.get(`<?= site_url(
            'api/mysql/events/definition',
        ) ?>/${encodeURIComponent(eventName)}`, response => {
            editor.setValue(response.sql || `-- Could not retrieve definition for event ${eventName}.`);
        }).fail(() => editor.setValue(`-- Error loading definition for event ${eventName}.`));
    });

    $('#templates-tab').on('click', '.load-template', function(e) {
        e.preventDefault();
        const categoryKey = $(this).data('category');
        const filename = $(this).data('filename');

        $.get(`<?= site_url(
            'api/templates/get',
        ) ?>/${categoryKey}/${filename}`, response => {
            let finalSql = response.sql;
            const placeholders = finalSql.match(/'NOME_DA_SUA_TABELA'|'schema.NomeDoObjeto'/g) || [];

            if (placeholders.length) {
                const objectName = prompt("Este script requer um nome de objeto (ex: dbo.MinhaTabela):");
                if (objectName) {
                    finalSql = finalSql.replace(/'NOME_DA_SUA_TABELA'|'schema.NomeDoObjeto'/g, objectName);
                } else {
                    return;
                }
            }
            editor.setValue(finalSql);
            isTemplateQuery = true;
        });
    });

    $(document).on('dblclick', '#resultsTabContent .table-responsive table tbody td', function() {
        editableGrid.cellDoubleClicked(this);
    });

    $('#save-changes-btn').on('click', function() {
        editableGrid.save();
    });

    $('<style>.datatable-row-changed > td { background-color: #fff3cd !important; }</style>').appendTo('head');

    $('#add-column-btn').on('click', () => schemaEditor.addColumnRow());
    $('#schema-editor-modal').on('click', '.remove-column-btn', function() {
        $(this).closest('tr').remove();
    });
    $('#save-table-btn').on('click', () => schemaEditor.save());


    // Initial setup
    refreshHistory();
    renderSavedScripts();
    initializeIntellisense();
    renderQueryTemplates();
    renderSharedScripts();
    if (DB_TYPE === 'sqlsrv') {
        renderAgentJobs();
    } else if (DB_TYPE === 'mysql') {
        renderMySqlEvents();
    }
});
</script>

</body>

</html>