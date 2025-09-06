<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SqlServerModel;
use CodeIgniter\API\ResponseTrait;

/**
 * Controlador responsável pela execução de consultas SQL e explicação de planos de execução via API.
 *
 * Permite executar queries, salvar histórico e obter o plano de execução.
 */
class Query extends BaseController
{
    use ResponseTrait;

    /**
     * Instância do modelo para acesso ao SQL Server.
     * @var SqlServerModel
     */
    private $model;

    /**
     * Construtor: inicializa o modelo SqlServerModel.
     */
    public function __construct()
    {
        $this->model = new SqlServerModel();
    }

    /**
     * Executa uma consulta SQL enviada via POST e retorna o resultado.
     *
     * Salva a query no histórico da sessão e armazena a última consulta bem-sucedida.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function execute()
    {
        $sql = $this->request->getPost('sql');

        $page = $this->request->getPost('page') ?: 1;


        if (empty(trim($sql))) {
            return $this->fail('A consulta SQL não pode estar vazia.', 400);
        }

        $result = $this->model->executeQuery($sql, (int)$page);
        
        if ($result['status'] === 'error') {
            return $this->fail($result, 400);
        }
        
        $message = lang('App.commands_executed_successfully');
        if ($result['resultSetCount'] > 0) {
            $message .= "\n" . $result['resultSetCount'] . ' ' . lang('App.result_sets_returned');
        }
        if ($result['totalRowsAffected'] > 0) {
            $message .= "\n" . $result['totalRowsAffected'] . ' ' . lang('App.rows_affected');
        }
        $message .= "\n" . lang('App.execution_time') . ": " . $result['executionTime'] . "s";

        $result['message'] = $message;
        
        $history = session()->get('query_history') ?? [];
        if (empty($history) || $history[0] !== $sql) {
            array_unshift($history, $sql);
        }
        if (count($history) > 30) {
            $history = array_slice($history, 0, 30);
        }
        session()->set('query_history', $history);
        
        if (!empty($result['results'])) {
            session()->set('last_successful_query', $sql);
        }

        return $this->respond($result);
    }

    /**
     * Retorna o plano de execução da consulta SQL enviada via POST.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function explain()
    {
        $sql = $this->request->getPost('sql');
        if (empty(trim($sql))) {
            return $this->fail(lang('query_empty'), 400);
        }
        $result = $this->model->getExecutionPlan($sql);
        if ($result['status'] === 'error') {
            return $this->fail($result, 400);
        }
        return $this->respond($result);
    }
}