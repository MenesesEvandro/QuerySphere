<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuerySphere</title>

    <link href="<?= base_url('dist/bundle.css') ?>" rel="stylesheet">

   <style>
        
    </style>
</head>

<body>
    <div class="main-wrapper">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-2">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <i class="fa-solid fa-database"></i> QuerySphere
                </a>

                <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fa-solid fa-language"></i>
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

                <div class="dropdown me-3">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-database me-1"></i>
                        <span id="active-database-name">
                            <?= esc(
                                $db_database ?: lang('App.general.select'),
                            ) ?>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark" id="db-selector-list">
                        <?php foreach ($databases as $db): ?>
                            <li>
                                <a class="dropdown-item" href="#" data-dbname="<?= esc(
                                    $db['name'],
                                ) ?>">
                                    <?= esc($db['name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="d-flex align-items-center">
                    <button id="theme-toggle-btn" class="btn btn-outline-secondary btn-sm me-3"
                        title="<?= lang('App.general.toggleTheme') ?>">
                        <i class="fa-solid fa-sun"></i> </button>

                    <span class="navbar-text text-white-50 me-3">
                        <?= lang(
                            'App.connection.connectedTo',
                        ) ?>: <strong><?= esc($db_host) ?></strong> (<?= esc(
                            $db_user,
                        ) ?>)
                    </span>
                    <a href="<?= site_url(
                        'logout',
                    ) ?>" class="btn btn-outline-danger btn-sm">
                        <i class="fa-solid fa-sign-out-alt"></i> <?= lang(
                            'App.connection.disconnect',
                        ) ?>
                    </a>
                </div>
            </div>
        </nav>