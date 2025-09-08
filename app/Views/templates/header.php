<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuerySphere</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/material-darker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/hint/show-hint.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <link href="<?= base_url('libs/qp/qp.css') ?>" rel="stylesheet">


   <style>
        :root{ --bg-color-main: #212529; --bg-color-panel: #2b3035; --text-color-primary: #dee2e6; --text-color-secondary: #adb5bd; --border-color: #495057; --accent-color: #0d6efd; --tab-bg-active: var(--bg-color-main); --tab-bg: #343a40;} body.light-theme{ --bg-color-main: #ffffff; --bg-color-panel: #f8f9fa; --text-color-primary: #212529; --text-color-secondary: #6c757d; --border-color: #dee2e6; --accent-color: #0d6efd; --tab-bg-active: var(--bg-color-main); --tab-bg: #e9ecef;} body, html{ height: 100vh; margin: 0; overflow: hidden; font-size: 0.95rem;} body{ background-color: var(--bg-color-panel); color: var(--text-color-primary);} .main-wrapper{ display: flex; flex-direction: column; height: 100%;} .main-content{ flex-grow: 1; display: flex; overflow: hidden;} #object-explorer-panel{ overflow-y: auto; background-color: var(--bg-color-panel);} .main-panel{ display: flex; flex-direction: column;} #query-editor-panel{ background-color: var(--bg-color-main);} #results-panel{ overflow: auto; background-color: var(--bg-color-panel);} .CodeMirror{ height: 100%;} .gutter{ background-color: var(--border-color); background-repeat: no-repeat; background-position: 50%; transition: background-color 0.2s ease-in-out;} .gutter:hover{ background-color: var(--accent-color);} .gutter.gutter-horizontal{ cursor: col-resize;} .gutter.gutter-vertical{ cursor: row-resize;} .nav-tabs .nav-link{ background-color: var(--tab-bg); border-color: var(--border-color); color: var(--text-color-secondary);} .nav-tabs .nav-link.active{ background-color: var(--tab-bg-active); border-bottom-color: var(--tab-bg-active); color: var(--text-color-primary);} .light-theme #object-explorer-tree{ background: var(--bg-color-panel);} .jstree-default .jstree-anchor{ color: var(--text-color-primary);} .jstree-default .jstree-hovered{ background-color: rgba(13, 110, 253, 0.1);} .jstree-default .jstree-clicked{ background-color: rgba(13, 110, 253, 0.2);} .jstree-icon{ color: var(--text-color-secondary);} #query-editor-panel .bg-light{ background-color: var(--bg-color-panel) !important; border-color: var(--border-color) !important;} #results-table-container tfoot input{ width: 100%; padding: 3px; box-sizing: border-box; background-color: var(--bg-color-main); color: var(--text-color-primary); border: 1px solid var(--border-color); border-radius: 3px;}
    </style>
</head>

<body>
    <div class="main-wrapper">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-2">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <i class="fa fa-database"></i> QuerySphere
                </a>

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
                        </ul>
                    </li>
                </ul>

                <div class="dropdown me-3">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-database me-1"></i>
                        <span id="active-database-name">
                            <?= esc($db_database ?: lang('App.select')) ?>
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
                        title="<?= lang('App.toggletheme') ?>">
                        <i class="fa fa-sun"></i> </button>

                    <span class="navbar-text text-white-50 me-3">
                        <?= lang('App.connectedTo') ?>: <strong><?= esc(
    $db_host,
) ?></strong> (<?= esc($db_user) ?>)
                    </span>
                    <a href="<?= site_url(
                        'logout',
                    ) ?>" class="btn btn-outline-danger btn-sm">
                        <i class="fa fa-sign-out-alt"></i> <?= lang(
                            'App.disconnect',
                        ) ?>
                    </a>
                </div>
            </div>
        </nav>