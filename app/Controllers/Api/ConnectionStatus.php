<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Factories\DatabaseModelFactory;
use CodeIgniter\API\ResponseTrait;
use Throwable;

/**
 * Controller to check the health of the current database connection.
 */
class ConnectionStatus extends BaseController
{
    use ResponseTrait;

    /**
     * Executes a lightweight query to check if the database connection is still active.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function ping()
    {
        try {
            $model = DatabaseModelFactory::create();

            // Use a dedicated, lightweight method on the model to check the connection.
            if (method_exists($model, 'checkConnection') && $model->checkConnection()) {
                return $this->respond(['status' => 'ok', 'message' => lang('App.feedback.connection_active')]);
            }

            return $this->failServerError('Connection is lost.');
        } catch (Throwable $e) {
            // Catch any exceptions during model creation or query execution.
            return $this->failServerError(lang('App.feedback.session_lost') . ' - ' . $e->getMessage());
        }
    }
}