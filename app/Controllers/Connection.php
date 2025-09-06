<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\ConnectionManager;
use App\Models\SqlServerModel;

/**
 * Controlador responsável pela gestão de conexões com o banco de dados.
 *
 * Permite conectar, desconectar e exibir a tela de conexão.
 */
class Connection extends BaseController
{
    /**
     * Exibe a tela de conexão e destrói a sessão atual.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface|string
     */
    public function index()
    {
        session()->remove([
            'is_connected',
            'db_host',
            'db_database',
            'db_user',
            'db_password',
            'last_successful_query',
            'query_history'
        ]);
        
        return view('connection/index');
    }

    /**
     * Realiza a tentativa de conexão ao banco de dados com as credenciais informadas.
     *
     * Valida os dados, armazena credenciais e redireciona conforme o resultado.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function connect()
    {
        $rules = [
            'host'     => 'required',
            'user'     => 'required',
            'password' => 'permit_empty',
            'port'     => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[65535]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $credentials = [
            'host'     => $this->request->getPost('host'),
            'database' => $this->request->getPost('database'),
            'user'     => $this->request->getPost('user'),
            'password' => $this->request->getPost('password'),
            'port'     => $this->request->getPost('port')
        ];

        $sqlServerModel = new SqlServerModel();
        $connectionResult = $sqlServerModel->tryConnect($credentials);

        if ($connectionResult['status'] === true) {
            $connManager = new ConnectionManager();
            $connManager->storeCredentials($credentials);
            return redirect()->to('/main')->with('success', lang('connection_success'));
        } else {
            return redirect()->back()->withInput()->with('error', $connectionResult['message']);
        }
    }
    
    /**
     * Encerra a sessão do usuário e redireciona para a tela inicial.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', lang('logout_success'));
    }
}