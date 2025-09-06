<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SqlServerModel;
use CodeIgniter\API\ResponseTrait;

/**
 * Controlador para explorar objetos do banco de dados via API.
 *
 * Fornece endpoints para listar bancos, tabelas, views, procedures, funções e realizar buscas.
 */
class ObjectExplorer extends BaseController
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
     * Retorna a lista de bancos de dados disponíveis.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
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
                'data' => ['type' => 'database']
            ];
        }
        return $this->respond($response);
    }

    public function getObjectSource()
    {
        $db = $this->request->getGet('db');
        $schema = $this->request->getGet('schema');
        $object = $this->request->getGet('object');
        $type = $this->request->getGet('type'); // 'procedure' ou 'function'

        if (empty($db) || empty($schema) || empty($object) || empty($type)) {
            return $this->fail('Parâmetros insuficientes.', 400);
        }

        $definition = $this->model->getObjectDefinition($db, $schema, $object);

        if (is_null($definition)) {
            return $this->failNotFound('Não foi possível encontrar a definição do objeto.');
        }

        // Converte a primeira ocorrência de CREATE para ALTER, de forma case-insensitive
        // Isso transforma "CREATE PROCEDURE" em "ALTER PROCEDURE"
        $alterScript = preg_replace('/^\s*CREATE/i', 'ALTER', $definition, 1);
        
        return $this->respond(['sql' => $alterScript]);
    }

    /**
     * Retorna os filhos de um nó do explorador de objetos (tabelas, views, procedures, funções, colunas, parâmetros).
     *
     * O tipo de nó é determinado pelo parâmetro 'id' da requisição.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
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
            case 'db': // Usuário expandiu um banco de dados, mostre as pastas
                $response = [
                    ['id' => 'folder-tables_' . $dbName, 'text' => 'Tabelas', 'icon' => 'fa fa-folder', 'children' => true, 'data' => ['type' => 'folder_tables']],
                    ['id' => 'folder-views_' . $dbName, 'text' => 'Views', 'icon' => 'fa fa-folder', 'children' => true, 'data' => ['type' => 'folder_views']],
                    ['id' => 'folder-procs_' . $dbName, 'text' => 'Stored Procedures', 'icon' => 'fa fa-folder', 'children' => true, 'data' => ['type' => 'folder_procs']],
                    ['id' => 'folder-funcs_' . $dbName, 'text' => 'Funções', 'icon' => 'fa fa-folder', 'children' => true, 'data' => ['type' => 'folder_funcs']],
                ];
                break;

            case 'folder-tables':
            case 'folder-views':
                $items = $this->model->getTablesAndViews($dbName);
                $filter = ($type === 'folder-tables') ? 'BASE TABLE' : 'VIEW';
                foreach ($items as $item) {
                    if ($item['TABLE_TYPE'] === $filter) {
                        $is_table = ($filter === 'BASE TABLE');
                        $response[] = [
                            'id' => ($is_table ? 'tbl_' : 'vw_') . $dbName . '.' . $item['TABLE_SCHEMA'] . '.' . $item['TABLE_NAME'],
                            'text' => esc($item['TABLE_SCHEMA']) . '.' . esc($item['TABLE_NAME']),
                            'icon' => $is_table ? 'fa fa-table' : 'fa fa-eye',
                            'children' => $is_table,
                            'data' => ['type' => $is_table ? 'table' : 'view', 'db' => $dbName, 'schema' => $item['TABLE_SCHEMA'], 'table' => $item['TABLE_NAME']]
                        ];
                    }
                }
                break;

            case 'folder-procs':
            case 'folder-funcs':
                $items = $this->model->getProceduresAndFunctions($dbName);
                $filter = ($type === 'folder-procs') ? 'PROCEDURE' : 'FUNCTION';
                foreach ($items as $item) {
                    if ($item['ROUTINE_TYPE'] === $filter) {
                        $is_proc = ($filter === 'PROCEDURE');
                        $response[] = [
                            'id' => ($is_proc ? 'proc_' : 'func_') . $dbName . '.' . $item['ROUTINE_SCHEMA'] . '.' . $item['ROUTINE_NAME'],
                            'text' => esc($item['ROUTINE_SCHEMA']) . '.' . esc($item['ROUTINE_NAME']),
                            'icon' => $is_proc ? 'fa fa-cog' : 'fa fa-cogs',
                            'children' => true, // Para expandir e ver os parâmetros
                            'data' => ['type' => $is_proc ? 'procedure' : 'function', 'db' => $dbName, 'schema' => $item['ROUTINE_SCHEMA'], 'routine' => $item['ROUTINE_NAME']]
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
                            'text' => esc($column['COLUMN_NAME']) . ' <small class="text-muted">(' . esc($column['DATA_TYPE']) . ')</small>',
                            'id' => 'col_' . $db . '.' . $schema . '.' . $table . '.' . $column['COLUMN_NAME'],
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
                    $params = $this->model->getRoutineParameters($db, $schema, $routine);
                    if (empty($params)) {
                        $response[] = ['text' => '<em class="text-muted">Sem parâmetros</em>', 'icon' => 'fa fa-ellipsis-h', 'children' => false];
                    } else {
                        foreach ($params as $param) {
                           $response[] = [
                               'text' => esc($param['PARAMETER_NAME']) . ' <small class="text-muted">(' . esc($param['full_type']) . ')</small>',
                               'id' => 'param_' . $dbName . '.' . $param['PARAMETER_NAME'],
                               'icon' => 'fa fa-arrow-right',
                               'children' => false
                           ];
                       }
                    }
                 }
                break;
        }

        return $this->respond($response);
    } 


    /**
     * Busca objetos no banco de dados pelo termo informado.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
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
                    $path[] = 'tbl_' . $dbName . '.' . $result['ObjectSchema'] . '.' . $result['ObjectName'];
                    break;
                case 'VIEW':
                    $path[] = 'folder-views_' . $dbName;
                    $path[] = 'vw_' . $dbName . '.' . $result['ObjectSchema'] . '.' . $result['ObjectName'];
                    break;
                case 'PROCEDURE':
                    $path[] = 'folder-procs_' . $dbName;
                    $path[] = 'proc_' . $dbName . '.' . $result['ObjectSchema'] . '.' . $result['ObjectName'];
                    break;
                case 'FUNCTION':
                    $path[] = 'folder-funcs_' . $dbName;
                    $path[] = 'func_' . $dbName . '.' . $result['ObjectSchema'] . '.' . $result['ObjectName'];
                    break;
            }
            $paths = array_merge($paths, $path);
        }
        
        return $this->respond(array_unique($paths));
    }
}
