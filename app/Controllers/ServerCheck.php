<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * Controller responsible for validating the server environment.
 *
 * This controller provides a diagnostic tool to check if the server meets the
 * application's requirements. It verifies the PHP version, required extensions,
 * file permissions, and the existence of the .env file, providing a
 * user-friendly report view.
 *
 * @package App\Controllers
 */
class ServerCheck extends BaseController
{
    /**
     * Performs the server validation checks and displays the results view.
     *
     * It systematically checks various server configurations against a set of
     * predefined requirements. For each check, it records the requirement, the
     * current server value, the status (pass/fail), and a descriptive, localized note.
     * An overall status is calculated based on the severity of any failures.
     * Finally, it passes the structured results to the 'server_check' view for rendering.
     *
     * @return string Renders the 'server_check' view with the validation data.
     */
    public function index()
    {
        $checks = [];
        $overall_status = 'success';

        // 1. PHP Version Check
        $php_version_required = '8.0.0';
        $php_version_ok = version_compare(
            PHP_VERSION,
            $php_version_required,
            '>=',
        );
        if (!$php_version_ok) {
            $overall_status = 'danger';
        }
        $checks[] = [
            'item' => lang('App.server_check.php_version'),
            'required' => '>= ' . $php_version_required,
            'current' => PHP_VERSION,
            'status' => $php_version_ok,
            'notes' => lang('App.server_check.php_version_note'),
        ];

        // 2. PHP Extensions Check
        $required_extensions = [
            'sqlsrv' => lang('App.server_check.note_sqlsrv'),
            'intl' => lang('App.server_check.note_intl'),
            'mbstring' => lang('App.server_check.note_mbstring'),
            'json' => lang('App.server_check.note_json'),
            'xml' => lang('App.server_check.note_xml'),
        ];
        foreach ($required_extensions as $ext => $note) {
            $is_loaded = extension_loaded($ext);
            if (!$is_loaded) {
                $overall_status = 'danger';
            }
            $checks[] = [
                'item' => lang('App.server_check.php_extension_item', [$ext]),
                'required' => lang('App.general.enabled'),
                'current' => $is_loaded
                    ? lang('App.general.enabled')
                    : lang('App.general.not_found'),
                'status' => $is_loaded,
                'notes' => $note,
            ];
        }

        // 3. Writable Permissions Check
        $is_writable = is_writable(WRITEPATH);
        if (!$is_writable) {
            $overall_status = 'danger';
        }
        $checks[] = [
            'item' => lang('App.server_check.writable_folder'),
            'required' => lang('App.server_check.writable'),
            'current' => $is_writable
                ? lang('App.server_check.writable')
                : lang('App.server_check.not_writable'),
            'status' => $is_writable,
            'notes' => lang('App.server_check.writable_note'),
        ];

        // 4. .env File Check
        $env_exists = file_exists(ROOTPATH . '.env');
        if (!$env_exists && $overall_status !== 'danger') {
            $overall_status = 'warning';
        }
        $checks[] = [
            'item' => lang('App.server_check.env_file'),
            'required' => lang('App.server_check.found'),
            'current' => $env_exists
                ? lang('App.server_check.found')
                : lang('App.server_check.not_found'),
            'status' => $env_exists,
            'notes' => lang('App.server_check.env_file_note'),
        ];

        $data['checks'] = $checks;
        $data['overall_status'] = $overall_status;
        $data['title'] = lang('App.server_check.title');

        return view('server_check', $data);
    }
}
