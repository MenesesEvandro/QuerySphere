<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SqlServerModel;
use CodeIgniter\API\ResponseTrait;

/**
 * Controller responsible for executing SQL queries and explaining execution plans via the API.
 *
 * This class is the central hub for user-submitted queries. It handles the execution logic,
 * server-side pagination, session history management, and retrieval of query execution plans.
 *
 * @package App\Controllers\Api
 */
class Query extends BaseController
{
    use ResponseTrait;

    /**
     * Instance of the model for accessing the SQL Server.
     * @var SqlServerModel
     */
    private $model;

    /**
     * Constructor: initializes the SqlServerModel.
     */
    public function __construct()
    {
        $this->model = new SqlServerModel();
    }

    /**
     * Executes an SQL query sent via POST and returns the result.
     *
     * This method retrieves the SQL and an optional page number from the request.
     * It passes the query to the model for execution, which supports server-side pagination.
     * After execution, it builds a localized status message, updates the user's session history,
     * and returns a comprehensive JSON object with the results and execution metadata.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface The JSON response containing status, results, messages, etc.
     */
    public function execute()
    {
        $sql = $this->request->getPost('sql');
        $page = $this->request->getPost('page') ?: 1;

        if (empty(trim($sql))) {
            return $this->fail(lang('App.query_empty'), 400);
        }

        $result = $this->model->executeQuery($sql, (int) $page);

        if ($result['status'] === 'error') {
            return $this->fail($result, 400);
        }

        // Build the localized status message in the controller
        $message = lang('App.commands_executed_successfully');
        if ($result['resultSetCount'] > 0) {
            $message .= "\n" . $result['resultSetCount'] . ' ' . lang('App.result_sets_returned');
        }
        if ($result['totalRowsAffected'] > 0) {
            $message .= "\n" . $result['totalRowsAffected'] . ' ' . lang('App.rows_affected');
        }
        $message .= "\n" . lang('App.execution_time') . ": " . $result['executionTime'] . "s";
        $result['message'] = $message;

        // Update session history
        $history = session()->get('query_history') ?? [];
        if (empty($history) || $history[0] !== $sql) {
            array_unshift($history, $sql);
        }
        if (count($history) > 30) {
            $history = array_slice($history, 0, 30);
        }
        session()->set('query_history', $history);

        // Save last query for features like export
        if (!empty($result['results'])) {
            session()->set('last_successful_query', $sql);
        }

        return $this->respond($result);
    }

    /**
     * Retrieves the execution plan for an SQL query sent via POST.
     *
     * It passes the SQL to the model's getExecutionPlan method and returns the
     * resulting XML plan within a JSON response.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface The JSON response with the execution plan XML or an error.
     */
    public function explain()
    {
        $sql = $this->request->getPost('sql');
        if (empty(trim($sql))) {
            return $this->fail(lang('App.query_empty'), 400);
        }
        $result = $this->model->getExecutionPlan($sql);
        if ($result['status'] === 'error') {
            return $this->fail($result, 400);
        }
        return $this->respond($result);
    }
}