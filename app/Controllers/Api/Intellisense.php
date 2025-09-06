<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SqlServerModel;
use CodeIgniter\API\ResponseTrait;

/**
 * Controlador responsável por fornecer dados para autocompletar SQL (Intellisense) via API.
 *
 * Retorna o esquema de autocompletar para o banco de dados.
 */
class Intellisense extends BaseController
{
    use ResponseTrait;

    /**
     * Retorna o esquema para autocompletar SQL (Intellisense).
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function index()
    {
        $model = new SqlServerModel();
        $schema = $model->getAutocompletionSchema();
        return $this->respond($schema);
    }
}