<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Factories\DatabaseModelFactory;
use CodeIgniter\API\ResponseTrait;

/**
 * Controller to handle database schema manipulation (DDL) operations.
 * Provides endpoints for creating tables, altering tables, etc.
 */
class SchemaEditor extends BaseController
{
    use ResponseTrait;

    /**
     * @var \App\Interfaces\DatabaseModelInterface The database model instance.
     */
    private $model;

    /**
     * Constructor.
     * Initializes the database model using the factory.
     */
    public function __construct()
    {
        $this->model = DatabaseModelFactory::create();
    }

    /**
     * Retrieves the detailed structure of a given table, including columns and types.
     *
     * @param string $database The URL-encoded name of the database.
     * @param string $schema The URL-encoded name of the schema.
     * @param string $table The URL-encoded name of the table.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getTableStructure($database, $schema, $table)
    {
        $structure = $this->model->getTableStructure(
            urldecode($database),
            urldecode($schema),
            urldecode($table),
        );
        if (!empty($structure)) {
            return $this->respond($structure);
        }
        return $this->failNotFound('Could not retrieve table structure.');
    }

    /**
     * Handles the API request to create a new table.
     * Expects a JSON payload with database, schema, table name, columns, and primary key details.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function createTable()
    {
        $data = $this->request->getJSON(true);

        $result = $this->model->createTable(
            $data['database'],
            $data['schema'],
            $data['table'],
            $data['columns'],
            $data['primaryKey'] ?? null,
        );

        if ($result['status'] === 'success') {
            return $this->respondCreated([
                'message' => 'Table created successfully.',
            ]);
        }
        return $this->fail($result['message']);
    }

    /**
     * Handles the API request to drop (delete) a table.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function dropTable()
    {
        $data = $this->request->getJSON(true);
        $result = $this->model->dropTable(
            $data['database'],
            $data['schema'],
            $data['table'],
        );

        if ($result['status'] === 'success') {
            return $this->respondDeleted([
                'message' => 'Table dropped successfully.',
            ]);
        }
        return $this->fail($result['message']);
    }

    /**
     * Handles the API request to add a new column to an existing table.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function addColumn()
    {
        $data = $this->request->getJSON(true);
        $result = $this->model->addColumn(
            $data['database'],
            $data['schema'],
            $data['table'],
            $data['column'],
        );

        if ($result['status'] === 'success') {
            return $this->respondUpdated([
                'message' => 'Column added successfully.',
            ]);
        }
        return $this->fail($result['message']);
    }
}
