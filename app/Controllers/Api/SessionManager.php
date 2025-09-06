<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class SessionManager extends BaseController {
    use ResponseTrait;

    /**
     * Define o banco de dados ativo na sessão do usuário.
     */
    public function setActiveDatabase()
    {
        if (! $this->request->is('post')) {
            return $this->fail('Método não permitido.', 405); // 405 Method Not Allowed
        }

        $dbName = $this->request->getVar('database');

        if (empty($dbName)) {
            return $this->fail('Nome do banco de dados não fornecido. Verifique os dados enviados.', 400);
        }

        session()->set('db_database', $dbName);

        return $this->respond([
            'status' => 'success', 
            'message' => 'Contexto do banco de dados alterado para ' . $dbName
        ]);
    }
}
