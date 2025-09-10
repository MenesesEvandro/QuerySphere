<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>QuerySphere - <?= lang('App.connect') ?></title>
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
            <?= lang('App.https_warning') ?>
        </div>
        <div class="alert alert-danger crypto-warning" role="alert">
            <?= lang('App.crypto_warning') ?>
        </div>
        <h2 class="card-title text-center mb-1"><i class="fa fa-database text-primary" aria-hidden="true"></i> QuerySphere</h2>
        <p class="card-subtitle mb-4 text-center text-muted"><?= lang(
            'App.connectionScreenTitle',
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
                'App.saved_connections',
            ) ?></label>
            <div class="input-group">
                <select class="form-select" id="connection-select" aria-describedby="connection-select-help">
                    <option value="new">-- <?= lang(
                        'App.new_connection',
                    ) ?> --</option>
                </select>
                <button class="btn btn-outline-secondary" type="button" id="manage-connections-btn" title="<?= lang(
                    'App.manage_connections',
                ) ?>" aria-label="<?= lang('App.manage_connections') ?>">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                </button>
            </div>
            <small id="connection-select-help" class="form-text text-muted"><?= lang(
                'App.select_connection',
            ) ?></small>
        </div>

        <hr>

        <?= form_open('connect') ?>
            <div id="new-connection-form">
                <div class="mb-3">
                    <label for="host" class="form-label"><?= lang(
                        'App.host',
                    ) ?></label>
                    <input type="text" class="form-control" id="host" name="host" value="<?= old(
                        'host',
                        'localhost',
                    ) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="port" class="form-label"><?= lang(
                        'App.port',
                    ) ?></label>
                    <input type="number" class="form-control" id="port" name="port" value="<?= old(
                        'port',
                        '1433',
                    ) ?>" required min="1" max="65535">
                </div>
                <div class="mb-3">
                    <label for="database" class="form-label"><?= lang(
                        'App.database',
                    ) ?></label>
                    <input type="text" class="form-control" id="database" name="database" value="<?= old(
                        'database',
                    ) ?>">
                </div>
                <div class="mb-3">
                    <label for="user" class="form-label"><?= lang(
                        'App.user',
                    ) ?></label>
                    <input type="text" class="form-control" id="user" name="user" value="<?= old(
                        'user',
                    ) ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label"><?= lang(
                    'App.password',
                ) ?></label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="trust-cert" name="trust_cert" value="1">
                <label class="form-check-label" for="trust-cert"><?= lang(
                    'App.trust_server_certificate',
                ) ?></label>
            </div>
            
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="save-connection-checkbox">
                <label class="form-check-label" for="save-connection-checkbox"><?= lang(
                    'App.rememberConnection',
                ) ?></label>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg"><?= lang(
                    'App.connect',
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
                    'App.server_compatibility_check',
                ) ?>
            </a>
        </div>
        <div>
            <a href="<?= site_url(
                'lang/pt-BR',
            ) ?>" class="text-decoration-none me-3">Português (BR)</a>
            <a href="<?= site_url(
                'lang/en-US',
            ) ?>" class="text-decoration-none">English (US)</a>
        </div>
    </div>
</div>

<div class="modal fade" id="manageConnectionsModal" tabindex="-1" aria-labelledby="manageConnectionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manageConnectionsModalLabel"><?= lang(
                    'App.manage_connections',
                ) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= lang(
                    'App.close',
                ) ?>"></button>
            </div>
            <div class="modal-body">
                <div id="connections-list-container"></div>
                <button class="btn btn-warning mt-3" id="clear-connections-btn" aria-label="<?= lang(
                    'App.clear_all_connections',
                ) ?>"><?= lang('App.clear_all_connections') ?></button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang(
                    'App.close',
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
                    'App.new_master_password_text',
                ) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= lang(
                    'App.close',
                ) ?>"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="master-password-input" class="form-label"><?= lang(
                        'App.master_password',
                    ) ?></label>
                    <input type="password" class="form-control" id="master-password-input" required>
                    <small class="form-text text-muted"><?= lang(
                        'App.master_password_hint',
                    ) ?></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= lang(
                    'App.close',
                ) ?></button>
                <button type="button" class="btn btn-primary" id="master-password-submit"><?= lang(
                    'App.submit',
                ) ?></button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<script>
    const LANG = {
        new_connection: "<?= lang('App.new_connection') ?>",
        manage_connections: "<?= lang('App.manage_connections') ?>",
        select_connection: "<?= lang('App.select_connection') ?>",
        confirm_delete_connection: "<?= lang(
            'App.confirm_delete_connection',
        ) ?>",
        invalid_number: "<?= lang('App.invalid_number') ?>",
        new_master_password: "<?= lang('App.new_master_password') ?>",
        ask_master_password: "<?= lang('App.ask_master_password') ?>",
        prompt_connection_name: "<?= lang('App.prompt_connection_name') ?>",
        error_decrypting_password: "<?= lang(
            'App.error_decrypting_password',
        ) ?>",
        no_saved_connections: "<?= lang('App.no_saved_connections') ?>",
        connection_deleted: "<?= lang('App.connection_deleted') ?>",
        connection: "<?= lang('App.connection') ?>",
        actions: "<?= lang('App.actions') ?>",
        delete: "<?= lang('App.delete') ?>",
        invalid_master_password: "<?= lang('App.invalid_master_password') ?>",
        confirm_clear_connections: "<?= lang(
            'App.confirm_clear_connections',
        ) ?>"
    };

    document.addEventListener('DOMContentLoaded', async function () {
        // Check for HTTPS and Web Crypto API support
        if (window.location.protocol !== 'https:') {
            document.querySelector('.https-warning').style.display = 'block';
        }
        if (!window.crypto || !window.crypto.subtle) {
            document.querySelector('.crypto-warning').style.display = 'block';
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
        const form = document.querySelector('form');
        const connectionSelect = document.getElementById('connection-select');
        const saveCheckbox = document.getElementById('save-connection-checkbox');
        const hostInput = document.getElementById('host');
        const portInput = document.getElementById('port');
        const databaseInput = document.getElementById('database');
        const userInput = document.getElementById('user');
        const passwordInput = document.getElementById('password');
        const trustCertCheckbox = document.getElementById('trust-cert');
        const manageModalElement = document.getElementById('manageConnectionsModal');
        const newConnectionForm = document.getElementById('new-connection-form');
        const manageModal = new bootstrap.Modal(manageModalElement);
        const masterPasswordModal = new bootstrap.Modal(document.getElementById('masterPasswordModal'));
        let masterPassword = null;

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
            return window.crypto.subtle.deriveKey(
                {
                    name: 'PBKDF2',
                    salt: salt,
                    iterations: 100000,
                    hash: 'SHA-256'
                },
                keyMaterial,
                { name: 'AES-GCM', length: 256 },
                false,
                ['encrypt', 'decrypt']
            );
        }

        // Encrypt data with AES-GCM
        async function encryptData(data, masterPassword) {
            const enc = new TextEncoder();
            const salt = window.crypto.getRandomValues(new Uint8Array(16));
            const iv = window.crypto.getRandomValues(new Uint8Array(12));
            const key = await deriveKey(masterPassword, salt);
            const encrypted = await window.crypto.subtle.encrypt(
                { name: 'AES-GCM', iv: iv },
                key,
                enc.encode(JSON.stringify(data))
            );
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
                const decrypted = await window.crypto.subtle.decrypt(
                    { name: 'AES-GCM', iv: iv },
                    key,
                    ct
                );
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
            connectionSelect.innerHTML = `<option value="new">-- ${LANG.new_connection} --</option>`;
            const names = getSavedConnectionNames();
            names.forEach((name, index) => {
                const option = new Option(escapeHtml(name), index);
                connectionSelect.add(option);
            });
        }

        // Fill form with connection data
        function fillFormWithConnectionData(connection, includePassword = true) {
            hostInput.value = escapeHtml(connection.host || '');
            portInput.value = connection.port || '';
            databaseInput.value = escapeHtml(connection.db_database || '');
            userInput.value = escapeHtml(connection.user || '');
            trustCertCheckbox.checked = connection.trust_cert || false;
            passwordInput.value = includePassword ? escapeHtml(connection.password || '') : '';
            passwordInput.focus();
        }

        // Render connections in modal
        async function renderConnectionsInModal() {
            const connections = await getSavedConnections(masterPassword);
            const container = document.getElementById('connections-list-container');
            container.innerHTML = '';
            if (connections.length === 0) {
                const p = document.createElement('p');
                p.className = 'text-muted';
                p.textContent = LANG.no_saved_connections;
                container.appendChild(p);
                return;
            }
            const table = document.createElement('table');
            table.className = 'table table-hover';
            const thead = document.createElement('thead');
            thead.innerHTML = `<tr><th>${escapeHtml(LANG.connection)}</th><th class="text-end">${escapeHtml(LANG.actions)}</th></tr>`;
            const tbody = document.createElement('tbody');
            connections.forEach((conn, index) => {
                const tr = document.createElement('tr');
                const tdName = document.createElement('td');
                tdName.textContent = conn.name;
                const tdActions = document.createElement('td');
                tdActions.className = 'text-end';
                const button = document.createElement('button');
                button.className = 'btn btn-sm btn-danger delete-connection-btn';
                button.dataset.index = index;
                button.title = LANG.delete;
                button.setAttribute('aria-label', `Delete ${escapeHtml(conn.name)}`);
                button.innerHTML = '<i class="fa fa-trash" aria-hidden="true"></i>';
                tdActions.appendChild(button);
                tr.append(tdName, tdActions);
                tbody.appendChild(tr);
            });
            table.append(thead, tbody);
            container.appendChild(table);
        }

        // Prompt for master password
        function promptMasterPassword(callback) {
            const input = document.getElementById('master-password-input');
            input.value = '';
            document.getElementById('master-password-submit').onclick = async () => {
                const password = input.value;
                if (!validateMasterPassword(password)) {
                    alert(LANG.invalid_master_password);
                    masterPasswordModal.hide();
                    callback(false, password);
                    return;
                }
                masterPassword = password;
                masterPasswordModal.hide();
                callback(true, password);
            };
            masterPasswordModal.show();
            input.focus();
        }

        // Handle connection select change
        connectionSelect.addEventListener('change', async function () {
            const selectedIndex = this.value;
            if (selectedIndex === 'new') {
                newConnectionForm.style.display = 'block';
                saveCheckbox.disabled = false;
                saveCheckbox.checked = false;
                form.reset();
            } else {
                newConnectionForm.style.display = 'block';
                saveCheckbox.disabled = true;
                saveCheckbox.checked = false;
                promptMasterPassword(async (isValid, password) => {
                    const connections = await getSavedConnections(password);
                    const names = getSavedConnectionNames();
                    if (!names[selectedIndex]) return;
                    const connection = {
                        name: names[selectedIndex],
                        host: '',
                        port: '',
                        db_database: '',
                        user: '',
                        trust_cert: false,
                        password: ''
                    };
                    if (isValid && connections[selectedIndex]) {
                        // Correct password: fill all fields
                        fillFormWithConnectionData(connections[selectedIndex], true);
                    } else {
                        // Incorrect password: fill all fields except password
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
        form.addEventListener('submit', async function (e) {
            const host = hostInput.value.trim();
            const port = parseInt(portInput.value);
            const database = databaseInput.value.trim();
            const user = userInput.value.trim();
            const password = passwordInput.value;

            // Validate inputs
            if (!host || host.length > 255) {
                e.preventDefault();
                alert('Invalid host');
                return;
            }
            if (isNaN(port) || port < 1 || port > 65535) {
                e.preventDefault();
                alert(LANG.invalid_number);
                return;
            }
            if (database && database.length > 255) {
                e.preventDefault();
                alert('Invalid database name');
                return;
            }
            if (!user || user.length > 255) {
                e.preventDefault();
                alert('Invalid user');
                return;
            }

            if (saveCheckbox.checked && connectionSelect.value === 'new') {
                e.preventDefault();
                const connectionName = prompt(LANG.prompt_connection_name, hostInput.value);
                if (!connectionName || connectionName.length > 255) {
                    alert('Invalid connection name');
                    return;
                }

                if (!masterPassword) {
                    promptMasterPassword(async (isValid, password) => {
                        if (!isValid) {
                            alert(LANG.invalid_master_password);
                            return;
                        }
                        const newConnection = {
                            name: connectionName,
                            host: hostInput.value,
                            port: portInput.value,
                            db_database: databaseInput.value,
                            user: userInput.value,
                            trust_cert: trustCertCheckbox.checked,
                            password: passwordInput.value
                        };
                        const connections = await getSavedConnections(password);
                        connections.push(newConnection);
                        await saveConnections(connections, password);
                        form.submit();
                    });
                } else {
                    const newConnection = {
                        name: connectionName,
                        host: hostInput.value,
                        port: portInput.value,
                        db_database: databaseInput.value,
                        user: userInput.value,
                        trust_cert: trustCertCheckbox.checked,
                        password: passwordInput.value
                    };
                    const connections = await getSavedConnections(masterPassword);
                    connections.push(newConnection);
                    await saveConnections(connections, masterPassword);
                    form.submit();
                }
            }
        });

        // Manage connections modal
        document.getElementById('manage-connections-btn').addEventListener('click', function () {
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

        // Handle delete connection
        manageModalElement.addEventListener('click', async function (e) {
            if (e.target.classList.contains('delete-connection-btn') || e.target.parentElement.classList.contains('delete-connection-btn')) {
                const button = e.target.classList.contains('delete-connection-btn') ? e.target : e.target.parentElement;
                const index = parseInt(button.dataset.index);
                const connections = await getSavedConnections(masterPassword);
                if (confirm(LANG.confirm_delete_connection.replace('{0}', escapeHtml(connections[index].name)))) {
                    connections.splice(index, 1);
                    await saveConnections(connections, masterPassword);
                    await renderConnectionsInModal();
                    if (connectionSelect.value == index) {
                        connectionSelect.value = 'new';
                        form.reset();
                    }
                }
            }
        });

        // Clear all connections
        document.getElementById('clear-connections-btn').addEventListener('click', async function () {
            if (confirm(LANG.confirm_clear_connections)) {
                localStorage.removeItem(STORAGE_KEY);
                await renderConnectionsInModal();
                connectionSelect.value = 'new';
                form.reset();
                loadConnectionsIntoSelect();
            }
        });

        loadConnectionsIntoSelect();
    });
</script>
</body>
</html>