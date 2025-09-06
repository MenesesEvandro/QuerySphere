<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuerySphere - <?= lang('App.connect') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <style>
        body{ display: flex; align-items: center; justify-content: center; min-height: 100vh; background-color: #f8f9fa;} .connection-card{ max-width: 480px; width: 100%;}
    </style>
</head>

<body>

    <div class="card shadow-lg border-0 connection-card">
        <div class="card-body p-5">
            <h2 class="card-title text-center mb-1"><i class="fa fa-database text-primary"></i> QuerySphere</h2>
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

            <?= form_open('connect') ?>
            <div class="mb-3">
                <label for="host" class="form-label"><?= lang(
                    'App.host',
                ) ?></label>
                <input type="text" class="form-control" id="host" name="host" value="<?= old(
                    'host',
                    'localhost',
                ) ?>"
                    required>
            </div>
            <div class="mb-3">
                <label for="port" class="form-label"><?= lang(
                    'App.port',
                ) ?></label>
                <input type="number" class="form-control" id="port" name="port" value="<?= old(
                    'port',
                    '1433',
                ) ?>"
                    required>
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
            <div class="mb-3">
                <label for="password" class="form-label"><?= lang(
                    'App.password',
                ) ?></label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="remember-me">
                <label class="form-check-label" for="remember-me">
                    <?= lang('App.rememberConnection') ?>
                </label>
            </div>

            <div class="d-grid mt-4">
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
                    <i class="fa fa-check-circle me-1"></i> <?= lang(
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const rememberMeCheckbox = document.getElementById('remember-me');
            const hostInput = document.getElementById('host');
            const portInput = document.getElementById('port');
            const databaseInput = document.getElementById('database');
            const userInput = document.getElementById('user');
            const passwordInput = document.getElementById('password');

            const storageKey = 'querysphere_connection';

            const savedConnection = localStorage.getItem(storageKey);
            if (savedConnection) {
                const connectionData = JSON.parse(savedConnection);
                hostInput.value = connectionData.host || '';
                portInput.value = connectionData.port || '';
                databaseInput.value = connectionData.database || '';
                userInput.value = connectionData.user || '';
                
                rememberMeCheckbox.checked = true;
                passwordInput.focus();
            }

            form.addEventListener('submit', function () {
                if (rememberMeCheckbox.checked) {
                    const connectionData = {
                        host: hostInput.value,
                        port: portInput.value,
                        database: databaseInput.value,
                        user: userInput.value
                    };
                    localStorage.setItem(storageKey, JSON.stringify(connectionData));
                } else {
                    localStorage.removeItem(storageKey);
                }
            });
        });
    </script>
</body>

</html>