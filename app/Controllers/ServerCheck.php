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
            'item' => lang('App.check_php_version'),
            'required' => '>= ' . $php_version_required,
            'current' => PHP_VERSION,
            'status' => $php_version_ok,
            'notes' => lang('App.check_php_version_note'),
        ];

        // 2. PHP Extensions Check
        $required_extensions = [
            'sqlsrv' => lang('App.check_note_sqlsrv'),
            'intl' => lang('App.check_note_intl'),
            'mbstring' => lang('App.check_note_mbstring'),
            'json' => lang('App.check_note_json'),
            'xml' => lang('App.check_note_xml'),
        ];
        foreach ($required_extensions as $ext => $note) {
            $is_loaded = extension_loaded($ext);
            if (!$is_loaded) {
                $overall_status = 'danger';
            }
            $checks[] = [
                'item' => lang('App.check_item_extension', [$ext]),
                'required' => lang('App.check_enabled'),
                'current' => $is_loaded
                    ? lang('App.check_enabled')
                    : lang('App.check_not_found'),
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
            'item' => lang('App.check_writable_folder'),
            'required' => lang('App.check_writable'),
            'current' => $is_writable
                ? lang('App.check_writable')
                : lang('App.check_not_writable'),
            'status' => $is_writable,
            'notes' => lang('App.check_writable_note'),
        ];

        // 4. .env File Check
        $env_exists = file_exists(ROOTPATH . '.env');
        if (!$env_exists && $overall_status !== 'danger') {
            $overall_status = 'warning';
        }
        $checks[] = [
            'item' => lang('App.check_env_file'),
            'required' => lang('App.check_found'),
            'current' => $env_exists
                ? lang('App.check_found')
                : lang('App.check_not_found'),
            'status' => $env_exists,
            'notes' => lang('App.check_env_file_note'),
        ];

        $data['checks'] = $checks;
        $data['overall_status'] = $overall_status;
        $data['title'] = lang('App.check_title');

        return view('server_check', $data);
    }
}
