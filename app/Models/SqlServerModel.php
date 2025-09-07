<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\ConnectionManager;

/**
 * The primary data access layer for interacting with a Microsoft SQL Server database.
 *
 * This model encapsulates all database operations, from establishing connections
 * to fetching metadata for the object explorer, executing user queries with pagination,
 * retrieving execution plans, and searching for database objects.
 *
 * @package App\Models
 */
class SqlServerModel extends Model
{
    /**
     * The active SQL Server connection resource.
     * @var resource|false|null
     */
    private $conn;

    /**
     * Attempts a preliminary connection to the SQL Server to validate credentials.
     *
     * This method is used exclusively by the ConnectionController during login.
     * It establishes a connection and immediately closes it. It does not persist
     * the connection for use by the model instance.
     *
     * @param array $credentials An associative array of connection details ('host', 'port', 'user', etc.).
     * @return array An associative array with 'status' (bool) and 'message' (string).
     */
    public function tryConnect(array $credentials): array
    {
        $serverName = $credentials['host'] . ',' . $credentials['port'];
        $connectionInfo = [
            'Database' => $credentials['database'],
            'UID' => $credentials['user'],
            'PWD' => $credentials['password'],
            'CharacterSet' => 'UTF-8',
        ];

        if (!empty($credentials['trust_cert'])) {
            $connectionInfo['TrustServerCertificate'] = true;
        }

        $conn = @sqlsrv_connect($serverName, $connectionInfo);

        if ($conn) {
            sqlsrv_close($conn);
            return [
                'status' => true,
                'message' => lang('App.connection_success'),
            ];
        } else {
            $errors = sqlsrv_errors();
            $errorMessage = lang('App.connection_failed') . ' ';
            if (is_array($errors) && !empty($errors)) {
                $errorMessage .=
                    lang('App.details') . ': ' . $errors[0]['message'];
            } else {
                $errorMessage .= lang('App.check_credentials');
            }
            return ['status' => false, 'message' => $errorMessage];
        }
    }

    /**
     * Establishes a persistent connection for the model instance using session credentials.
     *
     * This method is used internally by all other data-fetching methods. It retrieves
     * credentials from the session via the ConnectionManager and establishes a connection
     * that is stored in the private `$conn` property for reuse during the object's lifecycle.
     *
     * @return bool True if the connection is successful, false otherwise.
     */
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
            'Database' => $credentials['database'],
            'UID' => $credentials['user'],
            'PWD' => $credentials['password'],
            'CharacterSet' => 'UTF-8',
            'LoginTimeout' => 10,
        ];

        if (!empty($credentials['trust_cert'])) {
            $connectionInfo['TrustServerCertificate'] = true;
        }

        $this->conn = sqlsrv_connect($serverName, $connectionInfo);

        return $this->conn !== false;
    }

    /**
     * Retrieves a list of all user databases on the server.
     *
     * Filters out system databases like 'master', 'tempdb', etc.
     *
     * @return array A list of databases, where each item is an array with a 'name' key.
     */
    public function getDatabases(): array
    {
        if (!$this->connectFromSession()) {
            return [];
        }

        $sql =
            "SELECT name FROM sys.databases WHERE state = 0 AND name NOT IN ('master', 'tempdb', 'model', 'msdb') ORDER BY name;";
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

    /**
     * Fetches all tables and views for a given database.
     *
     * @param string $database The name of the database to query.
     * @return array A list of objects, each containing 'TABLE_SCHEMA', 'TABLE_NAME', and 'TABLE_TYPE'.
     */
    public function getTablesAndViews(string $database): array
    {
        if (!$this->connectFromSession()) {
            return [];
        }

        $sql =
            'SELECT TABLE_SCHEMA, TABLE_NAME, TABLE_TYPE FROM [' .
            $database .
            '].INFORMATION_SCHEMA.TABLES ORDER BY TABLE_SCHEMA, TABLE_NAME;';

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

    /**
     * Retrieves all columns for a specific table within a database.
     *
     * @param string $database The name of the database.
     * @param string $table    The name of the table.
     * @return array A list of columns, each containing 'COLUMN_NAME' and 'DATA_TYPE'.
     */
    public function getColumns(string $database, string $table): array
    {
        if (!$this->connectFromSession()) {
            return [];
        }

        $sql =
            'SELECT COLUMN_NAME, DATA_TYPE FROM [' .
            $database .
            '].INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? ORDER BY ORDINAL_POSITION;';

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

    /**
     * Fetches all stored procedures and functions for a given database.
     *
     * @param string $database The name of the database to query.
     * @return array A list of routines, each containing 'ROUTINE_SCHEMA', 'ROUTINE_NAME', and 'ROUTINE_TYPE'.
     */
    public function getProceduresAndFunctions(string $database): array
    {
        if (!$this->connectFromSession()) {
            return [];
        }

        $sql =
            "SELECT ROUTINE_SCHEMA, ROUTINE_NAME, ROUTINE_TYPE 
                FROM [" .
            $database .
            "].INFORMATION_SCHEMA.ROUTINES 
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

    /**
     * Retrieves the parameters for a specific stored procedure or function.
     *
     * @param string $database      The name of the database.
     * @param string $routineSchema The schema of the routine.
     * @param string $routineName   The name of the routine.
     * @return array A list of parameters for the specified routine.
     */
    public function getRoutineParameters(
        string $database,
        string $routineSchema,
        string $routineName,
    ): array {
        if (!$this->connectFromSession()) {
            return [];
        }

        $sql =
            "SELECT PARAMETER_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH 
                FROM [" .
            $database .
            "].INFORMATION_SCHEMA.PARAMETERS 
                WHERE SPECIFIC_SCHEMA = ? AND SPECIFIC_NAME = ?
                ORDER BY ORDINAL_POSITION;";

        $params = [$routineSchema, $routineName];
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        $results = [];
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $length = $row['CHARACTER_MAXIMUM_LENGTH'] ?? '';
                $length = $length == -1 ? 'MAX' : $length;
                $row['full_type'] =
                    $row['DATA_TYPE'] . ($length ? "({$length})" : '');
                $results[] = $row;
            }
            sqlsrv_free_stmt($stmt);
        }
        return $results;
    }

    /**
     * Builds a comprehensive schema dictionary for the CodeMirror Intellisense feature.
     *
     * It queries the INFORMATION_SCHEMA to get all tables, views, and their respective columns.
     * If no database is explicitly set in the session, it attempts to detect the current
     * database context from the active connection. The returned array is structured
     * specifically for consumption by the CodeMirror `sql-hint` addon.
     *
     * @return array The schema dictionary where keys are table names and values are arrays of column names.
     */
    public function getAutocompletionSchema(): array
    {
        if (!$this->connectFromSession()) {
            return [];
        }

        $database = session()->get('db_database');

        if (empty($database)) {
            $stmt = sqlsrv_query($this->conn, 'SELECT DB_NAME() AS dbname');
            if (
                $stmt &&
                ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
            ) {
                $database = $row['dbname'];
            }
        }

        if (empty($database)) {
            return [];
        }

        $sql =
            "
            SELECT 
                t.TABLE_SCHEMA, 
                t.TABLE_NAME, 
                c.COLUMN_NAME
            FROM 
                [" .
            $database .
            "].INFORMATION_SCHEMA.TABLES t
            INNER JOIN 
                [" .
            $database .
            "].INFORMATION_SCHEMA.COLUMNS c ON t.TABLE_NAME = c.TABLE_NAME AND t.TABLE_SCHEMA = c.TABLE_SCHEMA
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

                $fullTableName =
                    $row['TABLE_SCHEMA'] . '.' . $row['TABLE_NAME'];
                if (!isset($schema[$fullTableName])) {
                    $schema[$fullTableName] = $schema[$tableNameForHint];
                }
            }
            sqlsrv_free_stmt($stmt);
        }

        return $schema;
    }

    /**
     * Executes a user-provided SQL query, with support for server-side pagination.
     *
     * It intelligently detects if a query is a single, paginatable SELECT statement.
     * If so, it first runs a COUNT(*) query to get the total number of rows, then
     * modifies the original SQL with `OFFSET...FETCH` to retrieve only the requested page.
     * If the query is not paginatable (e.g., multiple statements, DDL), it executes
     * the script as-is and loops through multiple result sets using `sqlsrv_next_result()`.
     * The method returns raw data; the controller is responsible for localization.
     *
     * @param string $sql      The user's full SQL query string.
     * @param int    $page     The page number to retrieve for paginated queries.
     * @param int    $pageSize The number of rows per page.
     * @return array An associative array containing the status, results, and execution metadata.
     */
    public function executeQuery(
        string $sql,
        int $page = 1,
        int $pageSize = 1000,
    ): array {
        if (!$this->connectFromSession()) {
            return ['status' => 'error', 'message' => lang('App.session_lost')];
        }

        $startTime = microtime(true);
        $totalRows = 0;
        $allResults = [];
        $totalRowsAffected = 0;
        $paginated = false;

        $isPaginatable =
            stripos(trim($sql), 'SELECT') === 0 &&
            substr_count(strtoupper($sql), 'SELECT') === 1;

        if ($isPaginatable) {
            $paginated = true;

            $countSql = "WITH UserQuery AS ({$sql}) SELECT COUNT(*) as TotalRows FROM UserQuery";
            $countStmt = sqlsrv_query($this->conn, $countSql);
            if (
                $countStmt &&
                ($row = sqlsrv_fetch_array($countStmt, SQLSRV_FETCH_ASSOC))
            ) {
                $totalRows = $row['TotalRows'];
            }
            if ($countStmt) {
                sqlsrv_free_stmt($countStmt);
            }

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
            return [
                'status' => 'error',
                'message' =>
                    lang('App.syntax_error') .
                    ($errors[0]['message'] ?? lang('App.unknown_error')),
            ];
        }

        do {
            $headers = [];
            $data = [];
            $rowsAffected = sqlsrv_rows_affected($stmt);
            if ($rowsAffected > 0 && sqlsrv_field_metadata($stmt) === false) {
                $totalRowsAffected += $rowsAffected;
            }

            if (sqlsrv_has_rows($stmt)) {
                foreach (sqlsrv_field_metadata($stmt) as $fieldMetadata) {
                    $headers[] = $fieldMetadata['Name'];
                }
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $data[] = array_map(
                        fn($v) => $v instanceof \DateTime
                            ? $v->format('Y-m-d H:i:s.v')
                            : $v,
                        $row,
                    );
                }
                $result = [
                    'headers' => $headers,
                    'data' => $data,
                    'rowCount' => count($data),
                ];
                if ($paginated) {
                    $result['totalRows'] = $totalRows;
                    $result['currentPage'] = $page;
                    $result['totalPages'] =
                        $pageSize > 0 ? ceil($totalRows / $pageSize) : 1;
                }
                $allResults[] = $result;
            }
        } while (!$paginated && sqlsrv_next_result($stmt));

        $executionTime = number_format(microtime(true) - $startTime, 4);

        if ($stmt) {
            sqlsrv_free_stmt($stmt);
        }

        return [
            'status' => 'success',
            'results' => $allResults,
            'executionTime' => $executionTime,
            'totalRowsAffected' => $totalRowsAffected,
            'resultSetCount' => count($allResults),
        ];
    }

    /**
     * Retrieves the XML execution plan for a given SQL query.
     *
     * It enables the `SHOWPLAN_XML` setting, executes the query to capture the
     * plan output, and then disables the setting to return to normal operation.
     *
     * @param string $sql The SQL query to analyze.
     * @return array An associative array with the status and the XML plan string.
     */
    public function getExecutionPlan(string $sql): array
    {
        if (!$this->connectFromSession()) {
            return ['status' => 'error', 'message' => lang('App.session_lost')];
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
            return [
                'status' => 'error',
                'message' => lang('App.execution_plan_generation_failed'),
            ];
        }

        return ['status' => 'success', 'plan' => $xmlPlan];
    }

    /**
     * Searches for database objects (tables, views, routines) by name.
     *
     * @param string $database   The name of the database to search within.
     * @param string $searchTerm The term to search for using a LIKE clause.
     * @return array A list of found objects with their type, schema, and name.
     */
    public function searchObjects(string $database, string $searchTerm): array
    {
        if (!$this->connectFromSession()) {
            return [];
        }

        $likeTerm = '%' . $searchTerm . '%';

        $sql =
            "
            SELECT 
                'TABLE' AS ObjectType, 
                TABLE_SCHEMA AS ObjectSchema, 
                TABLE_NAME AS ObjectName 
            FROM [" .
            $database .
            "].INFORMATION_SCHEMA.TABLES 
            WHERE TABLE_NAME LIKE ?
            
            UNION ALL
            
            SELECT 
                ROUTINE_TYPE AS ObjectType, 
                ROUTINE_SCHEMA AS ObjectSchema, 
                ROUTINE_NAME AS ObjectName 
            FROM [" .
            $database .
            "].INFORMATION_SCHEMA.ROUTINES 
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
     * Fetches the source code definition of a database object (e.g., Procedure, View, Function).
     *
     * Uses the `sp_helptext` system stored procedure to retrieve the full text
     * of the object's CREATE script.
     *
     * @param string $database   The name of the database.
     * @param string $schema     The schema of the object.
     * @param string $objectName The name of the object.
     * @return string|null The full T-SQL definition of the object, or null on failure.
     */
    public function getObjectDefinition(
        string $database,
        string $schema,
        string $objectName,
    ): ?string {
        if (!$this->connectFromSession()) {
            return null;
        }

        if (sqlsrv_query($this->conn, 'USE [' . $database . ']') === false) {
            return null; // Failed to switch database context
        }

        $qualifiedName = $schema . '.' . $objectName;
        $sql = 'EXEC sp_helptext ?';
        $params = [$qualifiedName];

        $stmt = sqlsrv_query($this->conn, $sql, $params);
        $definition = '';

        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $definition .= $row['Text'];
            }
            sqlsrv_free_stmt($stmt);
            return $definition;
        }

        return null;
    }

    /**
     * Destructor: ensures the database connection is closed when the object is destroyed.
     */
    public function __destruct()
    {
        if ($this->conn) {
            sqlsrv_close($this->conn);
        }
    }
}
