<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Lang;

/**
 * Controlador responsável pela validação do ambiente do servidor.
 *
 * Realiza verificações de versão do PHP, extensões necessárias, permissões e existência do arquivo .env.
 */
class ServerCheck extends BaseController
{
    /**
     * Executa as validações do servidor e retorna a view com o resultado das verificações.
     *
     * @return mixed Renderiza a view 'server_check' com os dados das validações.
     */
    public function index()
    {
        $checks = [];
        $overall_status = 'success'; 

        $php_version_required = '8.0.0';
        $php_version_ok = version_compare(PHP_VERSION, $php_version_required, '>=');
        if (!$php_version_ok) $overall_status = 'danger';
        $checks[] = [
            'item' => lang('App.check_php_version'),
            'required' => '>= ' . $php_version_required,
            'current' => PHP_VERSION,
            'status' => $php_version_ok,
            'notes' => lang('App.check_php_version_note')
        ];

        $required_extensions = [
            'sqlsrv' => lang('App.check_note_sqlsrv'),
            'intl'   => lang('App.check_note_intl'),
            'mbstring' => lang('App.check_note_mbstring'),
            'json'   => lang('App.check_note_json'),
            'xml'    => lang('App.check_note_xml')
        ];
        foreach ($required_extensions as $ext => $note) {
            $is_loaded = extension_loaded($ext);
            if (!$is_loaded) $overall_status = 'danger';
            $checks[] = [
                'item' => lang('App.check_item_extension', [$ext]),
                'required' => lang('App.check_enabled'),
                'current' => $is_loaded ? lang('App.check_enabled') : lang('App.check_not_found'),
                'status' => $is_loaded,
                'notes' => $note
            ];
        }

        $is_writable = is_writable(WRITEPATH);
        if (!$is_writable) $overall_status = 'danger';
        $checks[] = [
            'item' => lang('App.check_writable_folder'),
            'required' => lang('App.check_writable'),
            'current' => $is_writable ? lang('App.check_writable') : lang('App.check_not_writable'),
            'status' => $is_writable,
            'notes' => lang('App.check_writable_note')
        ];
        
        $env_exists = file_exists(ROOTPATH . '.env');
        if (!$env_exists && $overall_status !== 'danger') $overall_status = 'warning';
        $checks[] = [
            'item' => lang('App.check_env_file'),
            'required' => lang('App.check_found'),
            'current' => $env_exists ? lang('App.check_found') : lang('App.check_not_found'),
            'status' => $env_exists,
            'notes' => lang('App.check_env_file_note')
        ];

        $data['checks'] = $checks;
        $data['overall_status'] = $overall_status;
        $data['title'] = lang('App.check_title');

        return view('server_check', $data);
    }
}