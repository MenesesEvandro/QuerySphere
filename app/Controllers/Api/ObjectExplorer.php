<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SqlServerModel;
use CodeIgniter\API\ResponseTrait;

/**
 * Controller for exploring database objects via the API.
 *
 * Provides endpoints for listing databases, tables, views, procedures, functions,
 * and performing searches across these objects to power the jsTree-based object explorer.
 *
 * @package App\Controllers\Api
 */
class ObjectExplorer extends BaseController
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
     * Retrieves the list of available databases on the connected server.
     *
     * Formats the list for consumption by the jsTree library.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface The JSON response with the database list.
     */
    public function databases()
    {
        $databases = $this->model->getDatabases();
        $response = [];
        foreach ($databases as $db) {
            $response[] = [
                'id' => 'db_' . $db['name'],
                'text' => $db['name'],
                'icon' => 'fa fa-database',
                'children' => true,
                'data' => ['type' => 'database'],
            ];
        }
        return $this->respond($response);
    }

    /**
     * Retrieves the source definition of a database object (Procedure, Function, View, etc.).
     *
     * It fetches the original 'CREATE' script from the database and intelligently
     * converts it to an 'ALTER' script for easy editing.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface The JSON response with the ALTER script or an error.
     */
    public function getObjectSource()
    {
        $db = $this->request->getGet('db');
        $schema = $this->request->getGet('schema');
        $object = $this->request->getGet('object');
        $type = $this->request->getGet('type');

        if (empty($db) || empty($schema) || empty($object) || empty($type)) {
            return $this->fail('Insufficient parameters provided.', 400);
        }

        $definition = $this->model->getObjectDefinition($db, $schema, $object);

        if (is_null($definition)) {
            return $this->failNotFound('Could not find the object definition.');
        }

        // Converts the first occurrence of CREATE to ALTER, case-insensitively.
        // This turns "CREATE PROCEDURE" into "ALTER PROCEDURE".
        $alterScript = preg_replace('/^\s*CREATE/i', 'ALTER', $definition, 1);

        return $this->respond(['sql' => $alterScript]);
    }

    /**
     * Retrieves the children of an object explorer node.
     *
     * The type of children returned (e.g., folders, tables, columns, parameters)
     * is determined by the 'id' parameter from the GET request, which indicates the parent node.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface The JSON response with the child nodes.
     */
    public function children()
    {
        $nodeId = $this->request->getGet('id');
        $response = [];
        $parts = explode('_', $nodeId, 2);
        if (count($parts) < 2) {
            return $this->respond([]);
        }
        [$type, $dbName] = $parts;

        switch ($type) {
            case 'db': // User expanded a database, show static folders
                $response = [
                    [
                        'id' => 'folder-tables_' . $dbName,
                        'text' => lang('App.objects_browser.tables'),
                        'icon' => 'fa fa-folder',
                        'children' => true,
                        'data' => ['type' => 'folder_tables'],
                    ],
                    [
                        'id' => 'folder-views_' . $dbName,
                        'text' => lang('App.objects_browser.views'),
                        'icon' => 'fa fa-folder',
                        'children' => true,
                        'data' => ['type' => 'folder_views'],
                    ],
                    [
                        'id' => 'folder-procs_' . $dbName,
                        'text' => lang('App.objects_browser.stored_procedures'),
                        'icon' => 'fa fa-folder',
                        'children' => true,
                        'data' => ['type' => 'folder_procs'],
                    ],
                    [
                        'id' => 'folder-funcs_' . $dbName,
                        'text' => lang('App.objects_browser.functions'),
                        'icon' => 'fa fa-folder',
                        'children' => true,
                        'data' => ['type' => 'folder_funcs'],
                    ],
                ];
                break;

            case 'folder-tables':
            case 'folder-views':
                $items = $this->model->getTablesAndViews($dbName);
                $filter = $type === 'folder-tables' ? 'BASE TABLE' : 'VIEW';
                foreach ($items as $item) {
                    if ($item['TABLE_TYPE'] === $filter) {
                        $is_table = $filter === 'BASE TABLE';
                        $response[] = [
                            'id' =>
                                ($is_table ? 'tbl_' : 'vw_') .
                                $dbName .
                                '.' .
                                $item['TABLE_SCHEMA'] .
                                '.' .
                                $item['TABLE_NAME'],
                            'text' =>
                                esc($item['TABLE_SCHEMA']) .
                                '.' .
                                esc($item['TABLE_NAME']),
                            'icon' => $is_table ? 'fa fa-table' : 'fa fa-eye',
                            'children' => $is_table,
                            'data' => [
                                'type' => $is_table ? 'table' : 'view',
                                'db' => $dbName,
                                'schema' => $item['TABLE_SCHEMA'],
                                'table' => $item['TABLE_NAME'],
                            ],
                        ];
                    }
                }
                break;

            case 'folder-procs':
            case 'folder-funcs':
                $items = $this->model->getProceduresAndFunctions($dbName);
                $filter = $type === 'folder-procs' ? 'PROCEDURE' : 'FUNCTION';
                foreach ($items as $item) {
                    if ($item['ROUTINE_TYPE'] === $filter) {
                        $is_proc = $filter === 'PROCEDURE';
                        $response[] = [
                            'id' =>
                                ($is_proc ? 'proc_' : 'func_') .
                                $dbName .
                                '.' .
                                $item['ROUTINE_SCHEMA'] .
                                '.' .
                                $item['ROUTINE_NAME'],
                            'text' =>
                                esc($item['ROUTINE_SCHEMA']) .
                                '.' .
                                esc($item['ROUTINE_NAME']),
                            'icon' => $is_proc ? 'fa fa-cog' : 'fa fa-cogs',
                            'children' => true, // Allows expansion to see parameters
                            'data' => [
                                'type' => $is_proc ? 'procedure' : 'function',
                                'db' => $dbName,
                                'schema' => $item['ROUTINE_SCHEMA'],
                                'routine' => $item['ROUTINE_NAME'],
                            ],
                        ];
                    }
                }
                break;

            case 'tbl':
                $nameParts = explode('.', $dbName, 3);
                if (count($nameParts) === 3) {
                    [$db, $schema, $table] = $nameParts;
                    $columns = $this->model->getColumns($db, $table);
                    foreach ($columns as $column) {
                        $response[] = [
                            'text' =>
                                esc($column['COLUMN_NAME']) .
                                ' <small class="text-muted">(' .
                                esc($column['DATA_TYPE']) .
                                ')</small>',
                            'id' =>
                                'col_' .
                                $db .
                                '.' .
                                $schema .
                                '.' .
                                $table .
                                '.' .
                                $column['COLUMN_NAME'],
                            'icon' => 'fa fa-columns',
                            'children' => false,
                        ];
                    }
                }
                break;

            case 'proc':
            case 'func':
                $nameParts = explode('.', $dbName, 3);
                if (count($nameParts) === 3) {
                    [$db, $schema, $routine] = $nameParts;
                    $params = $this->model->getRoutineParameters(
                        $db,
                        $schema,
                        $routine,
                    );
                    if (empty($params)) {
                        $response[] = [
                            'text' =>
                                '<em class="text-muted">' .
                                lang('App.objects_browser.no_parameters') .
                                '</em>',
                            'icon' => 'fa fa-ellipsis-h',
                            'children' => false,
                        ];
                    } else {
                        foreach ($params as $param) {
                            $response[] = [
                                'text' =>
                                    esc($param['PARAMETER_NAME']) .
                                    ' <small class="text-muted">(' .
                                    esc($param['full_type']) .
                                    ')</small>',
                                'id' =>
                                    'param_' .
                                    $dbName .
                                    '.' .
                                    $param['PARAMETER_NAME'],
                                'icon' => 'fa fa-arrow-right',
                                'children' => false,
                            ];
                        }
                    }
                }
                break;
        }

        return $this->respond($response);
    }

    /**
     * Searches for database objects based on a provided search term.
     *
     * It queries tables, views, procedures, and functions. The results are formatted
     * as an array of jsTree node IDs, which represents the full path to each found object.
     * This allows the jsTree search plugin to open the tree directly to the search results.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface The JSON response with an array of node ID paths.
     */
    public function search()
    {
        $dbName = session()->get('db_database');
        $term = $this->request->getGet('str');

        if (empty($dbName) || empty($term)) {
            return $this->respond([]);
        }

        $results = $this->model->searchObjects($dbName, $term);

        $paths = [];
        foreach ($results as $result) {
            $path = [];
            $path[] = 'db_' . $dbName;

            switch ($result['ObjectType']) {
                case 'BASE TABLE':
                    $path[] = 'folder-tables_' . $dbName;
                    $path[] =
                        'tbl_' .
                        $dbName .
                        '.' .
                        $result['ObjectSchema'] .
                        '.' .
                        $result['ObjectName'];
                    break;
                case 'VIEW':
                    $path[] = 'folder-views_' . $dbName;
                    $path[] =
                        'vw_' .
                        $dbName .
                        '.' .
                        $result['ObjectSchema'] .
                        '.' .
                        $result['ObjectName'];
                    break;
                case 'PROCEDURE':
                    $path[] = 'folder-procs_' . $dbName;
                    $path[] =
                        'proc_' .
                        $dbName .
                        '.' .
                        $result['ObjectSchema'] .
                        '.' .
                        $result['ObjectName'];
                    break;
                case 'FUNCTION':
                    $path[] = 'folder-funcs_' . $dbName;
                    $path[] =
                        'func_' .
                        $dbName .
                        '.' .
                        $result['ObjectSchema'] .
                        '.' .
                        $result['ObjectName'];
                    break;
            }
            $paths = array_merge($paths, $path);
        }

        return $this->respond(array_unique($paths));
    }

    /**
     * Retrieves a simple list of column names for a specific table.
     * Used by the context menu to build scripts.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getColumnsForScripting()
    {
        $db = $this->request->getGet('db');
        $table = $this->request->getGet('table');

        if (empty($db) || empty($table)) {
            return $this->fail('Parâmetros insuficientes.', 400);
        }

        $columnsData = $this->model->getColumns($db, $table);

        $columnNames = array_map(fn($col) => $col['COLUMN_NAME'], $columnsData);

        return $this->respond($columnNames);
    }
}
