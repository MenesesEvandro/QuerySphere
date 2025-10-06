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

    /** @var \App\Interfaces\DatabaseModelInterface The database model instance. */
    private $model;

    /** Validation rules for common database object identifiers. */
    private const OBJECT_NAME_RULES = 'required|alpha_numeric_punct';

    /**
     * Constructor.
     * Initializes the database model using the factory.
     */
    public function __construct()
    {
        $this->model = DatabaseModelFactory::create();
    }

    /**
     * Validates input data against a given set of rules.
     *
     * @param array $rules The validation rules.
     * @param array $data  The data to validate.
     * @return bool True if validation passes, false otherwise.
     */
    private function _validateInput(array $rules, array $data): bool
    {
        if (! $this->validate($rules, $data)) {
            return false;
        }
        return true;
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
        $data = ['database' => $database, 'schema' => $schema, 'table' => $table];
        if (! $this->_validateInput(array_fill_keys(array_keys($data), self::OBJECT_NAME_RULES), $data)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $structure = $this->model->getTableStructure(urldecode($database), urldecode($schema), urldecode($table));
        if (!empty($structure)) {
            return $this->respond($structure);
        }
        return $this->failNotFound('Could not retrieve table structure.');
    }

    /**
     * Handles the API request to create a new table.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function createTable()
    {
        $data = $this->request->getJSON(true);
        $rules = [
            'database'       => self::OBJECT_NAME_RULES,
            'schema'         => self::OBJECT_NAME_RULES,
            'table'          => self::OBJECT_NAME_RULES,
            'columns.*.name' => self::OBJECT_NAME_RULES,
        ];

        if (! $this->_validateInput($rules, $data)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $result = $this->model->createTable($data['database'], $data['schema'], $data['table'], $data['columns'], $data['primaryKeys'] ?? []);
        if ($result['status'] === 'success') {
            return $this->respondCreated(['message' => 'Table created successfully.']);
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
        $rules = [
            'database' => self::OBJECT_NAME_RULES,
            'schema'   => self::OBJECT_NAME_RULES,
            'table'    => self::OBJECT_NAME_RULES,
        ];

        if (! $this->_validateInput($rules, $data)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $result = $this->model->dropTable($data['database'], $data['schema'], $data['table']);
        if ($result['status'] === 'success') {
            return $this->respondDeleted(['message' => 'Table dropped successfully.']);
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
        $rules = [
            'database'    => self::OBJECT_NAME_RULES,
            'schema'      => self::OBJECT_NAME_RULES,
            'table'       => self::OBJECT_NAME_RULES,
            'column.name' => self::OBJECT_NAME_RULES,
        ];

        if (! $this->_validateInput($rules, $data)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $result = $this->model->addColumn($data['database'], $data['schema'], $data['table'], $data['column']);
        if ($result['status'] === 'success') {
            return $this->respondUpdated(['message' => 'Column added successfully.']);
        }
        return $this->fail($result['message']);
    }

    /**
     * Retrieves the list of indexes for a specific table.
     *
     * @param string $database The URL-encoded database name.
     * @param string $schema The URL-encoded schema name.
     * @param string $table The URL-encoded table name.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getIndexes($database, $schema, $table)
    {
        $data = ['database' => $database, 'schema' => $schema, 'table' => $table];
        if (! $this->_validateInput(array_fill_keys(array_keys($data), self::OBJECT_NAME_RULES), $data)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $indexes = $this->model->getIndexes(urldecode($database), urldecode($schema), urldecode($table));
        return $this->respond($indexes);
    }

    /**
     * Handles the API request to create a new index on a table.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function createIndex()
    {
        $data = $this->request->getJSON(true);
        $rules = [
            'database'   => self::OBJECT_NAME_RULES,
            'schema'     => self::OBJECT_NAME_RULES,
            'table'      => self::OBJECT_NAME_RULES,
            'index_name' => self::OBJECT_NAME_RULES,
        ];

        if (! $this->_validateInput($rules, $data)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $result = $this->model->createIndex($data['database'], $data['schema'], $data['table'], $data['index_name'], $data['columns'], $data['is_unique']);
        if ($result['status'] === 'success') {
            return $this->respondCreated(['message' => 'Index created successfully.']);
        }
        return $this->fail($result['message']);
    }

    /**
     * Handles the API request to drop an index from a table.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function dropIndex()
    {
        $data = $this->request->getJSON(true);
        $rules = [
            'database'   => self::OBJECT_NAME_RULES,
            'schema'     => self::OBJECT_NAME_RULES,
            'table'      => self::OBJECT_NAME_RULES,
            'index_name' => self::OBJECT_NAME_RULES,
        ];

        if (! $this->_validateInput($rules, $data)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $result = $this->model->dropIndex($data['database'], $data['schema'], $data['table'], $data['index_name']);
        if ($result['status'] === 'success') {
            return $this->respondDeleted(['message' => 'Index dropped successfully.']);
        }
        return $this->fail($result['message']);
    }

    /**
     * Retrieves a list of all tables for a given database.
     *
     * @param string $database The URL-encoded database name.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getTables($database)
    {
        if (! $this->_validateInput(['database' => self::OBJECT_NAME_RULES], ['database' => $database])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        $tables = $this->model->getAllTables(urldecode($database));
        return $this->respond($tables);
    }

    /**
     * Retrieves all foreign key constraints for a specific table.
     *
     * @param string $database The URL-encoded database name.
     * @param string $schema   The URL-encoded schema name.
     * @param string $table    The URL-encoded table name.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getForeignKeys($database, $schema, $table)
    {
        $data = ['database' => $database, 'schema' => $schema, 'table' => $table];
        if (! $this->_validateInput(array_fill_keys(array_keys($data), self::OBJECT_NAME_RULES), $data)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $fks = $this->model->getForeignKeys(urldecode($database), urldecode($schema), urldecode($table));
        return $this->respond($fks);
    }

    /**
     * Handles the API request to create a new foreign key constraint.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function createForeignKey()
    {
        $data = $this->request->getJSON(true);
        $rules = [
            'database'         => self::OBJECT_NAME_RULES,
            'schema'           => self::OBJECT_NAME_RULES,
            'table'            => self::OBJECT_NAME_RULES,
            'fk_name'          => self::OBJECT_NAME_RULES,
            'references_table' => self::OBJECT_NAME_RULES,
        ];

        if (! $this->_validateInput($rules, $data)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $result = $this->model->createForeignKey($data['database'], $data['schema'], $data['table'], $data['fk_name'], $data['columns'], $data['references_table'], $data['references_columns']);
        if ($result['status'] === 'success') {
            return $this->respondCreated(['message' => 'Foreign key created successfully.']);
        }
        return $this->fail($result['message']);
    }

    /**
     * Handles the API request to drop a foreign key constraint.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function dropForeignKey()
    {
        $data = $this->request->getJSON(true);
        $rules = [
            'database' => self::OBJECT_NAME_RULES,
            'schema'   => self::OBJECT_NAME_RULES,
            'table'    => self::OBJECT_NAME_RULES,
            'fk_name'  => self::OBJECT_NAME_RULES,
        ];

        if (! $this->_validateInput($rules, $data)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $result = $this->model->dropForeignKey($data['database'], $data['schema'], $data['table'], $data['fk_name']);
        if ($result['status'] === 'success') {
            return $this->respondDeleted(['message' => 'Foreign key dropped successfully.']);
        }
        return $this->fail($result['message']);
    }
}
