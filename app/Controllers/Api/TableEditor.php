<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Factories\DatabaseModelFactory;
use CodeIgniter\API\ResponseTrait;

/**
 * Controller to handle inline table editing operations.
 */
class TableEditor extends BaseController
{
    use ResponseTrait;

    /**
     * @var \App\Interfaces\DatabaseModelInterface
     */
    private $model;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->model = DatabaseModelFactory::create();
    }

    /**
     * Retrieves the primary key for a specific table.
     *
     * @param string $database The URL-encoded database name.
     * @param string $table The URL-encoded table name.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getPrimaryKey($database, $table)
    {
        $pk = $this->model->getPrimaryKey(
            urldecode($database),
            urldecode($table),
        );
        if ($pk) {
            return $this->respond(['primaryKey' => $pk]);
        }
        return $this->failNotFound(
            'Primary key not found or table does not support editing.',
        );
    }

    /**
     * Receives and processes a request to update a record.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function update()
    {
        $data = $this->request->getPost();

        $dbType = session()->get('db_type');

        if ($dbType === 'sqlsrv' && empty($data['schema'])) {
            return $this->fail(
                'Schema is required for SQL Server operations but was not provided by the client.',
            );
        }

        $schema = $data['schema'] ?? $data['database'];

        $result = $this->model->updateRecord(
            $data['database'],
            $schema,
            $data['table'],
            $data['pkColumn'],
            $data['pkValue'],
            $data['changes'],
        );

        if ($result['status'] === 'success') {
            return $this->respondUpdated([
                'message' => 'Record updated successfully',
            ]);
        }

        return $this->fail($result['message']);
    }
}
