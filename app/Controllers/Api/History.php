<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

/**
 * Controlador responsável pelo histórico de consultas SQL do usuário via API.
 *
 * Permite recuperar o histórico de queries executadas na sessão.
 */
class History extends BaseController
{
    use ResponseTrait;

    /**
     * Retorna o histórico de consultas SQL da sessão do usuário.
     *
     * Requer que o usuário esteja autenticado/conectado.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function get()
    {
        if (! session()->get('is_connected')) {
            return $this->failUnauthorized();
        }
        return $this->respond(session()->get('query_history') ?? []);
    }
}