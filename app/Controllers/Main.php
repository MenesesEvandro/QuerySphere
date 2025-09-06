<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * Controlador principal da aplicação.
 *
 * Responsável por exibir a tela principal com informações da conexão.
 */
class Main extends BaseController
{
    /**
     * Exibe a tela principal da aplicação com dados da conexão atual.
     *
     * @return mixed Renderiza a view 'main/index' com os dados da sessão.
     */
    public function index()
    {
        $model = new \App\Models\SqlServerModel();

        $data = [
            'db_host'       => $this->session->get('db_host'),
            'db_user'       => $this->session->get('db_user'),
            'db_database'   => $this->session->get('db_database'),
            'databases'     => $model->getDatabases()
        ];
        return view('main/index', $data);
    }
}