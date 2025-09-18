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
                    href="#objects-tab"><?= lang('App.workspace.objects') ?></a></li>

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
                    href="#history-tab"><?= lang('App.workspace.history') ?></a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#saved-tab"><?= lang('App.workspace.saved') ?></a>
            </li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#shared-tab"><?= lang('App.workspace.shared') ?></a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#templates-tab"><?= lang('App.workspace.templates') ?></a></li>
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
        <div class="d-flex flex-column h-100">
            <ul class="nav nav-tabs" id="editor-tabs" role="tablist">
                <li class="nav-item" id="new-tab-btn-container">
                    <a class="nav-link" href="#" id="new-tab-btn" title="Nova Aba">+</a>
                </li>
            </ul>

            <div class="tab-content flex-grow-1" id="editor-panes">
                </div>
        </div>
        </main>
</div>

<?= view('templates/footer') ?>