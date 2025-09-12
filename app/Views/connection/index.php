<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>QuerySphere - <?= lang('App.connection.connect') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; background-color: #f8f9fa; }
        .connection-card { max-width: 480px; width: 100%; }
        .https-warning, .crypto-warning { display: none; }
    </style>
</head>
<body>


<div class="card shadow-lg border-0 connection-card">
    <div class="card-body p-5">
        <div class="alert alert-danger https-warning" role="alert">
            <?= lang('App.general.https_warning') ?>
        </div>
        <div class="alert alert-danger crypto-warning" role="alert">
            <?= lang('App.general.crypto_warning') ?>
        </div>
        <h2 class="card-title text-center mb-1"><i class="fa fa-database text-primary" aria-hidden="true"></i> QuerySphere</h2>
        <p class="card-subtitle mb-4 text-center text-muted"><?= lang(
            'App.connection.screenTitle',
        ) ?></p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert"><?= session()->getFlashdata(
                'error',
            ) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert"><?= session()->getFlashdata(
                'success',
            ) ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="connection-select" class="form-label"><?= lang(
                'App.connection.saved_connections',
            ) ?></label>
            <div class="input-group">
                <select class="form-select" id="connection-select" aria-describedby="connection-select-help">
                    <option value="new">-- <?= lang(
                        'App.connection.new_connection',
                    ) ?> --</option>
                </select>
                <button class="btn btn-outline-secondary" type="button" id="manage-connections-btn" title="<?= lang(
                    'App.connection.manage_connections',
                ) ?>" aria-label="<?= lang(
    'App.connection.manage_connections',
) ?>">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                </button>
            </div>
            <small id="connection-select-help" class="form-text text-muted"><?= lang(
                'App.connection.select_connection',
            ) ?></small>
        </div>

        <hr>

        <?= form_open('connect') ?>
            <div id="new-connection-form">
                <div class="mb-3">
                    <label for="db_type" class="form-label"><?= lang(
                        'App.connection.db_type',
                    ) ?></label>
                    <select class="form-select" id="db_type" name="db_type">
                        <option value="sqlsrv" selected><?= lang(
                            'App.connection.sql_server',
                        ) ?></option>
                        <option value="mysql"><?= lang(
                            'App.connection.mysql',
                        ) ?></option>
                        <option value="pgsql" disabled><?= lang(
                            'App.connection.postgresql',
                        ) ?></option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="host" class="form-label"><?= lang(
                        'App.connection.host',
                    ) ?></label>
                    <input type="text" class="form-control" id="host" name="host" value="<?= old(
                        'host',
                        'localhost',
                    ) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="port" class="form-label"><?= lang(
                        'App.connection.port',
                    ) ?></label>
                    <input type="number" class="form-control" id="port" name="port" value="<?= old(
                        'port',
                        '1433',
                    ) ?>" required min="1" max="65535">
                </div>
                <div class="mb-3">
                    <label for="database" class="form-label"><?= lang(
                        'App.connection.database',
                    ) ?></label>
                    <input type="text" class="form-control" id="database" name="database" value="<?= old(
                        'database',
                    ) ?>">
                </div>
                <div class="mb-3">
                    <label for="user" class="form-label"><?= lang(
                        'App.connection.user',
                    ) ?></label>
                    <input type="text" class="form-control" id="user" name="user" value="<?= old(
                        'user',
                    ) ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label"><?= lang(
                    'App.connection.password',
                ) ?></label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="trust-cert" name="trust_cert" value="1">
                <label class="form-check-label" for="trust-cert"><?= lang(
                    'App.connection.trust_cert',
                ) ?></label>
            </div>
            
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="save-connection-checkbox">
                <label class="form-check-label" for="save-connection-checkbox"><?= lang(
                    'App.connection.rememberConnection',
                ) ?></label>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg"><?= lang(
                    'App.connection.connect',
                ) ?></button>
            </div>
        <?= form_close() ?>
    </div>
    <div class="card-footer text-center py-3 bg-light d-flex justify-content-between align-items-center">
        <div>
            <a href="<?= site_url(
                'check',
            ) ?>" class="text-decoration-none text-muted" style="font-size: 0.9em;">
                <i class="fa fa-check-circle me-1" aria-hidden="true"></i> <?= lang(
                    'App.server_check.title',
                ) ?>
            </a>
        </div>
        <div>
            <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa fa-language"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= site_url(
                                'lang/pt-BR',
                            ) ?>">Português (BR)</a></li>
                            <li><a class="dropdown-item" href="<?= site_url(
                                'lang/en-US',
                            ) ?>">English (US)</a></li>
                            <li><a class="dropdown-item" href="<?= site_url(
                                'lang/es-ES',
                            ) ?>">Español (ES)</a></li>
                        </ul>
                    </li>
                </ul>
        </div>
    </div>
</div>

<div class="modal fade" id="manageConnectionsModal" tabindex="-1" aria-labelledby="manageConnectionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageConnectionsModalLabel"><?= lang(
                    'App.connection.manage_connections',
                ) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= lang(
                    'App.general.close',
                ) ?>"></button>
            </div>
            <div class="modal-body">
                <div id="connections-list-container"></div>
                <button class="btn btn-warning mt-3" id="clear-connections-btn" aria-label="<?= lang(
                    'App.connection.clear_all_connections',
                ) ?>"><?= lang(
    'App.connection.clear_all_connections',
) ?></button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang(
                    'App.general.close',
                ) ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="masterPasswordModal" tabindex="-1" aria-labelledby="masterPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="masterPasswordModalLabel"><?= lang(
                    'App.master_password.title',
                ) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= lang(
                    'App.general.close',
                ) ?>"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="master-password-input" class="form-label"><?= lang(
                        'App.master_password.title',
                    ) ?></label>
                    <input type="password" class="form-control" id="master-password-input" required>
                    <small class="form-text text-muted"><?= lang(
                        'App.master_password.hint',
                    ) ?></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang(
                    'App.general.close',
                ) ?></button>
                <button type="button" class="btn btn-primary" id="master-password-submit"><?= lang(
                    'App.general.submit',
                ) ?></button>
            </div>
        </div>
    </div>
</div>

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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<script>
    <?= view('templates/scripts/lang', $this->data) ?>
    
    $(async function () {
        // Check for HTTPS and Web Crypto API support
        if (window.location.protocol !== 'https:') {
            $('.https-warning').show();
        }
        if (!window.crypto || !window.crypto.subtle) {
            $('.crypto-warning').show();
            return;
        }

        // Utility to escape HTML
        function escapeHtml(str) {
            return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        }

        // Utility to convert array buffer to base64
        function arrayBufferToBase64(buffer) {
            return btoa(String.fromCharCode(...new Uint8Array(buffer)));
        }

        // Utility to convert base64 to array buffer
        function base64ToArrayBuffer(base64) {
            const binary = atob(base64);
            const len = binary.length;
            const bytes = new Uint8Array(len);
            for (let i = 0; i < len; i++) {
                bytes[i] = binary.charCodeAt(i);
            }
            return bytes.buffer;
        }

        const STORAGE_KEY = 'querysphere_connections';
        const $form = $('form');
        const $connectionSelect = $('#connection-select');
        const $saveCheckbox = $('#save-connection-checkbox');
        const $hostInput = $('#host');
        const $portInput = $('#port');
        const $databaseInput = $('#database');
        const $userInput = $('#user');
        const $passwordInput = $('#password');
        const $trustCertCheckbox = $('#trust-cert');
        const $manageModalElement = $('#manageConnectionsModal');
        const $newConnectionForm = $('#new-connection-form');
        const manageModal = new bootstrap.Modal($manageModalElement[0]);
        const masterPasswordModal = new bootstrap.Modal($('#masterPasswordModal')[0]);
        let masterPassword = null;
        const portInput = $('#port');

         $('#db_type').on('change', function() {
            let selectedValue = $(this).val();

            if (selectedValue === 'mysql') {
                portInput.val('3306');
            } else if (selectedValue === 'sqlsrv') {
                portInput.val('1433');
            }
        });

        // Validate master password
        function validateMasterPassword(password) {
            const minLength = 8;
            const hasLetter = /[a-zA-Z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            return password.length >= minLength && hasLetter && hasNumber && hasSymbol;
        }

        // Derive key from master password
        async function deriveKey(password, salt) {
            const enc = new TextEncoder();
            const keyMaterial = await window.crypto.subtle.importKey(
                'raw',
                enc.encode(password),
                'PBKDF2',
                false,
                ['deriveKey']
            );
            return window.crypto.subtle.deriveKey({
                name: 'PBKDF2',
                salt: salt,
                iterations: 100000,
                hash: 'SHA-256'
            }, keyMaterial, { name: 'AES-GCM', length: 256 }, false, ['encrypt', 'decrypt']);
        }

        // Encrypt data with AES-GCM
        async function encryptData(data, masterPassword) {
            const enc = new TextEncoder();
            const salt = window.crypto.getRandomValues(new Uint8Array(16));
            const iv = window.crypto.getRandomValues(new Uint8Array(12));
            const key = await deriveKey(masterPassword, salt);
            const encrypted = await window.crypto.subtle.encrypt({ name: 'AES-GCM', iv: iv }, key, enc.encode(JSON.stringify(data)));
            return {
                ct: arrayBufferToBase64(encrypted),
                iv: arrayBufferToBase64(iv),
                salt: arrayBufferToBase64(salt)
            };
        }

        // Decrypt data with AES-GCM
        async function decryptData(encryptedData, masterPassword) {
            try {
                const salt = base64ToArrayBuffer(encryptedData.salt);
                const iv = base64ToArrayBuffer(encryptedData.iv);
                const ct = base64ToArrayBuffer(encryptedData.ct);
                const key = await deriveKey(masterPassword, salt);
                const decrypted = await window.crypto.subtle.decrypt({ name: 'AES-GCM', iv: iv }, key, ct);
                return JSON.parse(new TextDecoder().decode(decrypted));
            } catch (e) {
                return null;
            }
        }

        // Get saved connection names (plaintext)
        function getSavedConnectionNames() {
            const storageData = localStorage.getItem(STORAGE_KEY);
            if (!storageData) return [];
            try {
                const parsed = JSON.parse(storageData);
                return parsed.names || [];
            } catch (e) {
                return [];
            }
        }

        // Get saved connections (requires master password)
        async function getSavedConnections(masterPassword) {
            const storageData = localStorage.getItem(STORAGE_KEY);
            if (!storageData) return [];
            try {
                const parsed = JSON.parse(storageData);
                if (!parsed.data || !masterPassword) return [];
                const decrypted = await decryptData(parsed.data, masterPassword);
                return decrypted || [];
            } catch (e) {
                return [];
            }
        }

        // Save connections
        async function saveConnections(connections, masterPassword) {
            if (!masterPassword) return;
            const names = connections.map(conn => conn.name);
            const encryptedData = await encryptData(connections, masterPassword);
            localStorage.setItem(STORAGE_KEY, JSON.stringify({
                names: names,
                data: encryptedData
            }));
            loadConnectionsIntoSelect();
        }

        // Load connections into select
        function loadConnectionsIntoSelect() {
            $connectionSelect.empty().append($('<option>', {
                value: 'new',
                text: `-- ${LANG.new_connection} --`
            }));
            const names = getSavedConnectionNames();
            $.each(names, function (index, name) {
                const $option = $('<option>', {
                    value: index,
                    text: escapeHtml(name)
                });
                $connectionSelect.append($option);
            });
        }

        // Fill form with connection data
        function fillFormWithConnectionData(connection, includePassword = true) {
            $hostInput.val(escapeHtml(connection.host || ''));
            $portInput.val(connection.port || '');
            $databaseInput.val(escapeHtml(connection.db_database || ''));
            $userInput.val(escapeHtml(connection.user || ''));
            $trustCertCheckbox.prop('checked', connection.trust_cert || false);
            $passwordInput.val(includePassword ? escapeHtml(connection.password || '') : '');
            $passwordInput.focus();
        }

        // Render connections in modal
        async function renderConnectionsInModal() {
            const connections = await getSavedConnections(masterPassword);
            const $container = $('#connections-list-container').empty();

            if (connections.length === 0) {
                $container.append(`<p class="text-muted">${LANG.no_saved_connections}</p>`);
                return;
            }

            const $table = $('<table>').addClass('table table-hover');
            const $thead = $('<thead>').html(`<tr><th>${escapeHtml(LANG.connection)}</th><th class="text-end">${escapeHtml(LANG.actions)}</th></tr>`);
            const $tbody = $('<tbody>');

            $.each(connections, function (index, conn) {
                const $button = $('<button>').addClass('btn btn-sm btn-danger delete-connection-btn')
                    .data('index', index)
                    .attr('title', LANG.delete)
                    .attr('aria-label', `Delete ${escapeHtml(conn.name)}`)
                    .html('<i class="fa fa-trash" aria-hidden="true"></i>');
                
                const $tr = $('<tr>').append(
                    $('<td>').text(conn.name),
                    $('<td>').addClass('text-end').append($button)
                );
                $tbody.append($tr);
            });

            $table.append($thead, $tbody);
            $container.append($table);
        }

        // Prompt for master password
        function promptMasterPassword(callback) {
            const $input = $('#master-password-input').val('');
            $('#master-password-submit').off('click').on('click', async () => {
                const password = $input.val();
                if (!validateMasterPassword(password)) {
                    alert(LANG.invalid_master_password);
                    masterPasswordModal.hide();
                    callback(false, password);
                    return;
                }
                masterPassword = password;
                masterPasswordModal.hide();
                callback(true, password);
            });
            masterPasswordModal.show();
            $input.focus();
        }

        // Handle connection select change
        $connectionSelect.on('change', async function () {
            const selectedIndex = $(this).val();
            if (selectedIndex === 'new') {
                $newConnectionForm.show();
                $saveCheckbox.prop('disabled', false).prop('checked', false);
                $form[0].reset();
            } else {
                $newConnectionForm.show();
                $saveCheckbox.prop('disabled', true).prop('checked', false);
                promptMasterPassword(async (isValid, password) => {
                    const connections = await getSavedConnections(password);
                    const names = getSavedConnectionNames();
                    if (!names[selectedIndex]) return;
                    const connection = {
                        name: names[selectedIndex],
                        host: '', port: '', db_database: '', user: '', trust_cert: false, password: ''
                    };
                    if (isValid && connections[selectedIndex]) {
                        fillFormWithConnectionData(connections[selectedIndex], true);
                    } else {
                        if (connections[selectedIndex]) {
                            fillFormWithConnectionData(connections[selectedIndex], false);
                        } else {
                            fillFormWithConnectionData(connection, false);
                        }
                        if (password) {
                            alert(LANG.invalid_master_password);
                        }
                    }
                });
            }
        });

        // Form submission with validation
        $form.on('submit', async function (e) {
            const host = $hostInput.val().trim();
            const port = parseInt($portInput.val());
            const database = $databaseInput.val().trim();
            const user = $userInput.val().trim();
            const password = $passwordInput.val();

            // Validate inputs
            if (!host || host.length > 255) { e.preventDefault(); alert('Invalid host'); return; }
            if (isNaN(port) || port < 1 || port > 65535) { e.preventDefault(); alert(LANG.invalid_number); return; }
            if (database && database.length > 255) { e.preventDefault(); alert('Invalid database name'); return; }
            if (!user || user.length > 255) { e.preventDefault(); alert('Invalid user'); return; }

            if ($saveCheckbox.prop('checked') && $connectionSelect.val() === 'new') {
                e.preventDefault();
                const connectionName = prompt(LANG.prompt_connection_name, $hostInput.val());
                if (!connectionName || connectionName.length > 255) {
                    alert('Invalid connection name');
                    return;
                }

                const saveAndSubmit = async (password) => {
                    const newConnection = {
                        name: connectionName,
                        host: $hostInput.val(),
                        port: $portInput.val(),
                        db_database: $databaseInput.val(),
                        user: $userInput.val(),
                        trust_cert: $trustCertCheckbox.prop('checked'),
                        password: $passwordInput.val()
                    };
                    const connections = await getSavedConnections(password);
                    connections.push(newConnection);
                    await saveConnections(connections, password);
                    $form[0].submit();
                };

                if (!masterPassword) {
                    promptMasterPassword(async (isValid, password) => {
                        if (!isValid) {
                            alert(LANG.invalid_master_password);
                            return;
                        }
                        await saveAndSubmit(password);
                    });
                } else {
                    await saveAndSubmit(masterPassword);
                }
            }
        });

        // Manage connections modal
        $('#manage-connections-btn').on('click', function () {
            if (!masterPassword) {
                promptMasterPassword(async (isValid, password) => {
                    if (!isValid) {
                        alert(LANG.invalid_master_password);
                        return;
                    }
                    await renderConnectionsInModal();
                    manageModal.show();
                });
            } else {
                renderConnectionsInModal();
                manageModal.show();
            }
        });

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

        // Handle delete connection using event delegation
        $manageModalElement.on('click', '.delete-connection-btn', async function () {
            const button = $(this);
            const index = parseInt(button.data('index'));
            
            try {
                const connections = await getSavedConnections(masterPassword);
                
                await showConfirmModal(LANG.confirm_delete_connection.replace('{0}', escapeHtml(connections[index].name)));

                connections.splice(index, 1);
                await saveConnections(connections, masterPassword);
                await renderConnectionsInModal();

                // Correção dos seletores
                if ($('#connection-select').val() == index) {
                    $('#connection-select').val('new');
                    $('form').trigger('reset');
                }
                
                notifier.show(LANG.connection_deleted, 'success');

            } catch (err) {
                console.log('Delete connection canceled.');
            }
        });

        // Clear all connections
        $('#clear-connections-btn').on('click', async function () {
            try {
                await showConfirmModal(LANG.confirm_clear_connections);

                localStorage.removeItem(STORAGE_KEY);
                masterPassword = null;
                
                await renderConnectionsInModal();
                
                $('#connection-select').val('new');
                $('form').trigger('reset');
                
                loadConnectionsIntoSelect();
                notifier.show(LANG.connections_cleared, 'success');

            } catch (err) {
                console.log('Clear connections canceled.');
            }
        });

        loadConnectionsIntoSelect();
    });
</script>
</body>
</html>