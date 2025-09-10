<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuerySphere - <?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <style>
        body { background-color: #f4f6f9; }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h3 class="mb-0"><i class="fa fa-server me-2"></i> <?= esc(
                $title,
            ) ?></h3>
        </div>
        <div class="card-body p-4">

            <?php if ($overall_status === 'success'): ?>
                <div class="alert alert-success">
                    <h4><i class="fa fa-check-circle me-2"></i><?= lang(
                        'App.check_ok_title',
                    ) ?></h4>
                    <p class="mb-0"><?= lang('App.check_ok_message') ?></p>
                </div>
            <?php elseif ($overall_status === 'warning'): ?>
                <div class="alert alert-warning">
                    <h4><i class="fa fa-exclamation-triangle me-2"></i><?= lang(
                        'App.check_warn_title',
                    ) ?></h4>
                    <p class="mb-0"><?= lang('App.check_warn_message') ?></p>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <h4><i class="fa fa-times-circle me-2"></i><?= lang(
                        'App.check_fail_title',
                    ) ?></h4>
                    <p class="mb-0"><?= lang('App.check_fail_message') ?></p>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped mt-4">
                    <thead class="table-light">
                        <tr>
                            <th><?= lang('App.check_header_item') ?></th>
                            <th><?= lang('App.check_header_status') ?></th>
                            <th><?= lang('App.check_header_current') ?></th>
                            <th><?= lang('App.check_header_required') ?></th>
                            <th><?= lang('App.check_header_notes') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($checks as $check): ?>
                            <tr>
                                <td class="fw-bold"><?= esc(
                                    $check['item'],
                                ) ?></td>
                                <td class="text-center">
                                    <?php if ($check['status']): ?>
                                        <span class="badge bg-success fs-6"><?= lang(
                                            'App.check_status_ok',
                                        ) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger fs-6"><?= lang(
                                            'App.check_status_fail',
                                        ) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($check['current']) ?></td>
                                <td><?= esc($check['required']) ?></td>
                                <td class="text-muted" style="font-size: 0.9em;"><?= esc(
                                    $check['notes'],
                                ) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="text-center mt-4">
                <a href="<?= site_url(
                    '/',
                ) ?>" class="btn btn-primary"><i class="fa fa-home me-2"></i><?= lang(
    'App.check_go_to_app',
) ?></a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>