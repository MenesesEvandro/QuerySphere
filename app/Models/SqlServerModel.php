<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\ConnectionManager;

class SqlServerModel extends Model
{
    private $conn;

    public function tryConnect(array $credentials): array
    {
        $serverName = $credentials['host'] . ',' . $credentials['port'];
        $connectionInfo = [
            "Database" => $credentials['database'],
            "UID" => $credentials['user'],
            "PWD" => $credentials['password'],
            "CharacterSet" => "UTF-8"
        ];
        
        $conn = @sqlsrv_connect($serverName, $connectionInfo);

        if ($conn) {
            sqlsrv_close($conn);
            return ['status' => true, 'message' => lang('connection_success')];
        } else {
            $errors = sqlsrv_errors();
            $errorMessage = lang('connection_failed') . " ";
            if (is_array($errors) && !empty($errors)) {
                 $errorMessage .= lang('details') . ": " . $errors[0]['message'];
            } else {
                $errorMessage .= lang('check_credentials');
            }
            return ['status' => false, 'message' => $errorMessage];
        }
    }

    public function connectFromSession(): bool
    {
        if ($this->conn) {
            return true;
        }
        
        $connManager = new ConnectionManager();
        $credentials = $connManager->getCredentials();
        
        if (!$credentials) {
            return false;
        }

        $serverName = $credentials['host'] . ',' . $credentials['port'];
        $connectionInfo = [
            "Database" => $credentials['database'],
            "UID" => $credentials['user'],
            "PWD" => $credentials['password'],
            "CharacterSet" => "UTF-8",
            "LoginTimeout" => 10
        ];

        $this->conn = sqlsrv_connect($serverName, $connectionInfo);
        
        return $this->conn !== false;
    }

    public function getDatabases(): array
    {
        if (!$this->connectFromSession()) return [];
        
        $sql = "SELECT name FROM sys.databases WHERE state = 0 AND name NOT IN ('master', 'tempdb', 'model', 'msdb') ORDER BY name;";
        $stmt = sqlsrv_query($this->conn, $sql);
        $databases = [];
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $databases[] = $row;
            }
            sqlsrv_free_stmt($stmt);
        }
        return $databases;
    }

    public function getTablesAndViews(string $database): array
    {
        if (!$this->connectFromSession()) return [];
        
        $sql = "SELECT TABLE_SCHEMA, TABLE_NAME, TABLE_TYPE FROM [" . $database . "].INFORMATION_SCHEMA.TABLES ORDER BY TABLE_SCHEMA, TABLE_NAME;";
        
        $stmt = sqlsrv_query($this->conn, $sql);
        $results = [];
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $results[] = $row;
            }
            sqlsrv_free_stmt($stmt);
        }
        return $results;
    }

    public function getColumns(string $database, string $table): array
    {
        if (!$this->connectFromSession()) return [];

        $sql = "SELECT COLUMN_NAME, DATA_TYPE FROM [" . $database . "].INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? ORDER BY ORDINAL_POSITION;";
        
        $params = [$table];
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        $results = [];
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $results[] = $row;
            }
            sqlsrv_free_stmt($stmt);
        }
        return $results;
    }
    
    public function getProceduresAndFunctions(string $database): array
    {
        if (!$this->connectFromSession()) return [];

        $sql = "SELECT ROUTINE_SCHEMA, ROUTINE_NAME, ROUTINE_TYPE 
                FROM [" . $database . "].INFORMATION_SCHEMA.ROUTINES 
                WHERE ROUTINE_TYPE IN ('PROCEDURE', 'FUNCTION') 
                ORDER BY ROUTINE_TYPE, ROUTINE_SCHEMA, ROUTINE_NAME;";
        
        $stmt = sqlsrv_query($this->conn, $sql);
        $results = [];
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $results[] = $row;
            }
            sqlsrv_free_stmt($stmt);
        }
        return $results;
    }

    public function getRoutineParameters(string $database, string $routineSchema, string $routineName): array
    {
        if (!$this->connectFromSession()) return [];

        $sql = "SELECT PARAMETER_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH 
                FROM [" . $database . "].INFORMATION_SCHEMA.PARAMETERS 
                WHERE SPECIFIC_SCHEMA = ? AND SPECIFIC_NAME = ?
                ORDER BY ORDINAL_POSITION;";
        
        $params = [$routineSchema, $routineName];
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        $results = [];
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $length = $row['CHARACTER_MAXIMUM_LENGTH'] ?? '';
                $length = ($length == -1) ? 'MAX' : $length;
                $row['full_type'] = $row['DATA_TYPE'] . ($length ? "({$length})" : '');
                $results[] = $row;
            }
            sqlsrv_free_stmt($stmt);
        }
        return $results;
    }
    
    public function getAutocompletionSchema(): array
    {
        if (!$this->connectFromSession()) return [];

        $database = session()->get('db_database');
        
        if (empty($database)) {
            $stmt = sqlsrv_query($this->conn, 'SELECT DB_NAME() AS dbname');
            if ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
                $database = $row['dbname'];
            }
        }

        if (empty($database)) {
            return [];
        }

        $sql = "
            SELECT 
                t.TABLE_SCHEMA, 
                t.TABLE_NAME, 
                c.COLUMN_NAME
            FROM 
                [" . $database . "].INFORMATION_SCHEMA.TABLES t
            INNER JOIN 
                [" . $database . "].INFORMATION_SCHEMA.COLUMNS c ON t.TABLE_NAME = c.TABLE_NAME AND t.TABLE_SCHEMA = c.TABLE_SCHEMA
            ORDER BY 
                t.TABLE_SCHEMA, t.TABLE_NAME, c.ORDINAL_POSITION;
        ";

        $stmt = sqlsrv_query($this->conn, $sql);
        $schema = [];

        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $tableNameForHint = $row['TABLE_NAME'];
                if (!isset($schema[$tableNameForHint])) {
                    $schema[$tableNameForHint] = [];
                }
                $schema[$tableNameForHint][] = $row['COLUMN_NAME'];
                
                $fullTableName = $row['TABLE_SCHEMA'] . '.' . $row['TABLE_NAME'];
                if (!isset($schema[$fullTableName])) {
                    $schema[$fullTableName] = $schema[$tableNameForHint];
                }
            }
            sqlsrv_free_stmt($stmt);
        }
        
        return $schema;
    }


    public function executeQuery(string $sql, int $page = 1, int $pageSize = 1000): array
    {
        if (!$this.connectFromSession()) {
            return ['status' => 'error', 'message' => 'Sessão de conexão perdida.'];
        }

        $startTime = microtime(true);
        $totalRows = 0;
        $allResults = [];
        $totalRowsAffected = 0;
        $paginated = false;
        
        $isPaginatable = (stripos(trim($sql), 'SELECT') === 0 && substr_count(strtoupper($sql), 'SELECT') === 1);

        if ($isPaginatable) {
            $paginated = true;

            $countSql = "WITH UserQuery AS ({$sql}) SELECT COUNT(*) as TotalRows FROM UserQuery";
            $countStmt = sqlsrv_query($this->conn, $countSql);
            if ($countStmt && ($row = sqlsrv_fetch_array($countStmt, SQLSRV_FETCH_ASSOC))) {
                $totalRows = $row['TotalRows'];
            }
            sqlsrv_free_stmt($countStmt);

            $paginatedSql = $sql;
            if (stripos($paginatedSql, 'ORDER BY') === false) {
                $paginatedSql .= ' ORDER BY (SELECT NULL)';
            }
            $offset = ($page - 1) * $pageSize;
            $paginatedSql .= " OFFSET {$offset} ROWS FETCH NEXT {$pageSize} ROWS ONLY";
            
            $stmt = sqlsrv_query($this->conn, $paginatedSql);
        } else {
            $stmt = sqlsrv_query($this->conn, $sql);
        }
        
        if ($stmt === false) {
            $errors = sqlsrv_errors();
            return ['status' => 'error', 'message' => 'Erro de sintaxe ou execução: ' . ($errors[0]['message'] ?? 'Erro desconhecido')];
        }

        do {
            $headers = [];
            $data = [];
            $rowsAffected = sqlsrv_rows_affected($stmt);
            if ($rowsAffected > 0 && sqlsrv_field_metadata($stmt) === false) {
                $totalRowsAffected += $rowsAffected;
            }

            if (sqlsrv_has_rows($stmt)) {
                foreach (sqlsrv_field_metadata($stmt) as $fieldMetadata) { $headers[] = $fieldMetadata['Name']; }
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $data[] = array_map(fn($v) => ($v instanceof \DateTime) ? $v->format('Y-m-d H:i:s.v') : $v, $row);
                }
                $result = [ 'headers' => $headers, 'data' => $data, 'rowCount' => count($data) ];
                if($paginated) {
                    $result['totalRows'] = $totalRows;
                    $result['currentPage'] = $page;
                    $result['totalPages'] = ($pageSize > 0) ? ceil($totalRows / $pageSize) : 1;
                }
                $allResults[] = $result;
            }
        } while (!$paginated && sqlsrv_next_result($stmt));

        $executionTime = number_format(microtime(true) - $startTime, 4);
        $message = lang('App.commands_executed_successfully');
        
        sqlsrv_free_stmt($stmt);
        
        return [
            'status'             => 'success',
            'results'            => $allResults,
            'executionTime'      => $executionTime,
            'totalRowsAffected'  => $totalRowsAffected,
            'resultSetCount'     => count($allResults)
        ];
    }
    
    public function getExecutionPlan(string $sql): array
    {
        if (!$this->connectFromSession()) {
            return ['status' => 'error', 'message' => lang('session_lost')];
        }

        sqlsrv_query($this->conn, 'SET SHOWPLAN_XML ON;');
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt === false) {
            sqlsrv_query($this->conn, 'SET SHOWPLAN_XML OFF;');
            $errors = sqlsrv_errors();
            return ['status' => 'error', 'message' => $errors[0]['message']];
        }
        
        $xmlPlan = '';
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $xmlPlan .= current($row);
        }

        sqlsrv_query($this->conn, 'SET SHOWPLAN_XML OFF;');
        
        if (empty($xmlPlan)) {
            return ['status' => 'error', 'message' => lang('execution_plan_generation_failed')];
        }
        
        return ['status' => 'success', 'plan' => $xmlPlan];
    }

    /**
     * Busca objetos (tabelas, procedures, funções) pelo nome no banco de dados informado.
     *
     * @param string $database Nome do banco de dados onde buscar.
     * @param string $searchTerm Termo de busca para nome dos objetos.
     * @return array Lista de objetos encontrados com tipo, schema e nome.
     */
    public function searchObjects(string $database, string $searchTerm): array
    {
        if (!$this->connectFromSession()) return [];

        $likeTerm = '%' . $searchTerm . '%';

        $sql = "
            SELECT 
                'TABLE' AS ObjectType, 
                TABLE_SCHEMA AS ObjectSchema, 
                TABLE_NAME AS ObjectName 
            FROM [" . $database . "].INFORMATION_SCHEMA.TABLES 
            WHERE TABLE_NAME LIKE ?
            
            UNION ALL
            
            SELECT 
                ROUTINE_TYPE AS ObjectType, 
                ROUTINE_SCHEMA AS ObjectSchema, 
                ROUTINE_NAME AS ObjectName 
            FROM [" . $database . "].INFORMATION_SCHEMA.ROUTINES 
            WHERE ROUTINE_NAME LIKE ?;
        ";

        $params = [$likeTerm, $likeTerm];
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        $results = [];
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $results[] = $row;
            }
            sqlsrv_free_stmt($stmt);
        }
        return $results;
    }

    /**
     * Busca a definição (código-fonte) de um objeto (Procedure, View, Function, etc.).
     * @param string $database
     * @param string $schema
     * @param string $objectName
     * @return string|null
     */
    public function getObjectDefinition(string $database, string $schema, string $objectName): ?string
    {
        if (!$this->connectFromSession()) return null;

        // sp_helptext opera no contexto do banco de dados atual, então garantimos o contexto.
        if (sqlsrv_query($this->conn, "USE [" . $database . "]") === false) {
            return null; // Falha ao trocar de banco
        }

        // O nome do objeto precisa ser qualificado com o schema para sp_helptext
        $qualifiedName = $schema . '.' . $objectName;
        $sql = "EXEC sp_helptext ?";
        $params = [$qualifiedName];
        
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        $definition = '';

        if ($stmt) {
            // sp_helptext retorna o texto em múltiplas linhas, precisamos concatená-las.
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $definition .= $row['Text'];
            }
            sqlsrv_free_stmt($stmt);
            return $definition;
        }
        
        return null;
    }

    public function __destruct()
    {
        if ($this->conn) {
            sqlsrv_close($this->conn);
        }
    }
}