<?= view('templates/header', $this->data) ?>

<div class="main-content">
    <aside id="object-explorer-panel" class="p-2 d-flex flex-column">
        <div class="input-group input-group-sm mb-2">
            <span class="input-group-text"><i class="fa fa-search"></i></span>
            <input type="text" id="object-search-input" class="form-control"
                placeholder="<?= lang('App.objects_browser.search') ?>">
        </div>

        <ul class="nav nav-tabs nav-fill flex-shrink-0">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab"
                    href="#objects-tab"><?= lang(
                        'App.workspace.objects',
                    ) ?></a></li>

            <?php if ($db_type === 'sqlsrv'): ?>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#agent-tab">
                <?= lang('App.agent.title') ?></a></li>
            <?php endif; ?>

            <?php if ($db_type === 'mysql'): ?>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#events-tab">
                <?= lang('App.event.title') ?>    
                </a></li>
            <?php endif; ?>


            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                    href="#history-tab"><?= lang(
                        'App.workspace.history',
                    ) ?></a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#saved-tab"><?= lang(
                'App.workspace.saved',
            ) ?></a>
            </li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#shared-tab"><?= lang(
                'App.workspace.shared',
            ) ?></a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#templates-tab"><?= lang(
                'App.workspace.templates',
            ) ?></a></li>

        </ul>

        <div class="tab-content flex-grow-1" style="overflow-y: auto; overflow-x: hidden;">
            <div class="tab-pane active h-100" id="objects-tab">
                <div id="object-explorer-tree" class="h-100"></div>
            </div>
            <div class="tab-pane h-100" id="history-tab">
                <ul id="query-history-list" class="list-group list-group-flush"></ul>
            </div>
            <div class="tab-pane h-100" id="saved-tab">
                <ul id="saved-scripts-list" class="list-group list-group-flush"></ul>
            </div>
            <div class="tab-pane h-100" id="shared-tab">
                <ul id="shared-scripts-list" class="list-group list-group-flush"></ul>
            </div>
            <div class="tab-pane h-100" id="templates-tab">
                <div class="accordion" id="query-templates-accordion"></div>
            </div>
            <?php if ($db_type === 'sqlsrv'): ?>
                <div class="tab-pane h-100" id="agent-tab">
                <div id="agent-jobs-container" class="p-2"></div>
            </div>
            <?php endif; ?>
            <?php if ($db_type === 'mysql'): ?>
                <div class="tab-pane h-100" id="events-tab">
                    <div id="mysql-events-container" class="p-2"></div>
                </div>
            <?php endif; ?>
        </div>
    </aside>

    <main class="main-panel">
        <section id="query-editor-panel" class="d-flex flex-column">
            <div class="p-2 bg-light border-bottom d-flex align-items-center flex-wrap">
                <button id="execute-query-btn" class="btn btn-success btn-sm me-2 mb-1 mb-md-0">
                    <i class="fa fa-play me-1"></i> <?= lang(
                        'App.workspace.execute',
                    ) ?> (Ctrl+Enter)
                </button>
                <button id="explain-query-btn" class="btn btn-info btn-sm me-2 mb-1 mb-md-0">
                    <i class="fa fa-sitemap me-1"></i> <?= lang(
                        'App.workspace.explain',
                    ) ?>
                </button>
                <button id="format-sql-btn" class="btn btn-outline-secondary btn-sm me-3 mb-1 mb-md-0"
                    title="<?= lang('App.workspace.formatSQL') ?>">
                    <i class="fa fa-align-left"></i>
                </button>
                <div class="btn-group me-3 mb-1 mb-md-0">
                    <button id="export-csv-btn" class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="fa fa-file-csv me-1"></i> <?= lang(
                            'App.workspace.exportCSV',
                        ) ?>
                    </button>
                    <button id="export-json-btn" class="btn btn-outline-secondary btn-sm" disabled>
                        <i class="fa fa-file-code me-1"></i> <?= lang(
                            'App.workspace.exportJSON',
                        ) ?>
                    </button>
                </div>
                <button id="show-chart-btn" class="btn btn-outline-primary btn-sm mb-1 mb-md-0" disabled
                    data-bs-toggle="modal" data-bs-target="#chartModal">
                    <i class="fa fa-chart-bar me-1"></i> <?= lang(
                        'App.charts.title',
                    ) ?>
                </button>
                <button id="save-script-btn" class="btn btn-outline-info btn-sm ms-auto mb-1 mb-md-0">
                    <i class="fa fa-save me-1"></i> <?= lang(
                        'App.scripts.save',
                    ) ?>
                </button>
                <button id="share-script-btn" class="btn btn-outline-success btn-sm ms-2 mb-1 mb-md-0">
                    <i class="fa fa-users me-1"></i> <?= lang(
                        'App.scripts.share',
                    ) ?>
                </button>
            </div>
            <textarea id="query-editor" class="flex-grow-1"></textarea>
        </section>

        <section id="results-panel" class="p-2 d-flex flex-column">

            <div id="pagination-controls" class="pb-2 border-bottom d-flex justify-content-between align-items-center"
                style="display: none;">
                <div>
                    <button id="pagination-prev" class="btn btn-sm btn-outline-secondary">&laquo; <?= lang(
                        'App.general.previous',
                    ) ?></button>
                    <button id="pagination-next" class="btn btn-sm btn-outline-secondary"><?= lang(
                        'App.general.next',
                    ) ?> &raquo;</button>
                </div>
                <div id="pagination-info" class="" style="font-size: 0.9em;"></div>
            </div>

            <ul class="nav nav-tabs flex-shrink-0" id="resultsTab" role="tablist">
                <li class="nav-item static-tab" role="presentation">
                    <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages"
                        type="button" role="tab"><?= lang(
                            'App.workspace.messages',
                        ) ?></button>
                </li>
                <li class="nav-item static-tab" role="presentation">
                    <button class="nav-link" id="plan-tab" data-bs-toggle="tab" data-bs-target="#execution-plan"
                        type="button" role="tab"><?= lang(
                            'App.workspace.explain',
                        ) ?></button>
                </li>
            </ul>
            <div class="tab-content flex-grow-1" id="resultsTabContent" style="overflow: auto;">
                <div id="results-placeholder" class="p-3">
                    <?= lang('App.workspace.queryResultsPlaceholder') ?>
                </div>
                <div class="tab-pane fade h-100" id="messages" role="tabpanel">
                    <div id="messages-content" class="text-monospace h-100"></div>
                </div>
                <div class="tab-pane fade h-100 p-2" id="execution-plan" role="tabpanel"></div>
            </div>
        </section>
    </main>
</div>

<?= view('templates/footer') ?>
