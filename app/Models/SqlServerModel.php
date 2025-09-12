<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\DatabaseConnector;
use App\Interfaces\DatabaseModelInterface;

/**
 * The primary data access layer for interacting with a Microsoft SQL Server database.
 *
 * This model encapsulates all database operations, from establishing connections
 * to fetching metadata for the object explorer, executing user queries with pagination,
 * retrieving execution plans, and searching for database objects.
 *
 * @package App\Models
 */
class SqlServerModel extends Model implements DatabaseModelInterface
{
    /**
     * The active SQL Server connection resource, obtained from the shared connector.
     * @var resource|false
     */
    private $conn;

    /**
     * Constructor: gets the shared connection from the connector.
     */
    public function __construct()
    {
        parent::__construct();
        $this->conn = DatabaseConnector::getConnection();
    }

    /**
     * Checks if the model has a valid and active connection.
     * @return bool
     */
    private function hasConnection(): bool
    {
        if ($this->conn === false || $this->conn === null) {
            log_message(
                'error',
                'SqlServerModel: Database connection failed or was not established.',
            );
            return false;
        }
        return true;
    }

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
                'message' => lang('App.feedback.connection_success'),
            ];
        } else {
            $errors = sqlsrv_errors();
            $errorMessage = lang('App.feedback.connection_failed') . ' ';
            if (is_array($errors) && !empty($errors)) {
                $errorMessage .=
                    lang('App.general.details') . ': ' . $errors[0]['message'];
            } else {
                $errorMessage .= lang('App.feedback.check_credentials');
            }
            return ['status' => false, 'message' => $errorMessage];
        }
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
        if (!$this->hasConnection()) {
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
        if (!$this->hasConnection()) {
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
        if (!$this->hasConnection()) {
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
        if (!$this->hasConnection()) {
            return [];
        }
        $sql =
            'SELECT ROUTINE_SCHEMA, ROUTINE_NAME, ROUTINE_TYPE FROM [' .
            $database .
            "].INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_TYPE IN ('PROCEDURE', 'FUNCTION') ORDER BY ROUTINE_TYPE, ROUTINE_SCHEMA, ROUTINE_NAME;";
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
        if (!$this->hasConnection()) {
            return [];
        }
        $sql =
            'SELECT PARAMETER_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH FROM [' .
            $database .
            '].INFORMATION_SCHEMA.PARAMETERS WHERE SPECIFIC_SCHEMA = ? AND SPECIFIC_NAME = ? ORDER BY ORDINAL_POSITION;';
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
        if (!$this->hasConnection()) {
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
            'SELECT t.TABLE_SCHEMA, t.TABLE_NAME, c.COLUMN_NAME FROM [' .
            $database .
            '].INFORMATION_SCHEMA.TABLES t INNER JOIN [' .
            $database .
            '].INFORMATION_SCHEMA.COLUMNS c ON t.TABLE_NAME = c.TABLE_NAME AND t.TABLE_SCHEMA = c.TABLE_SCHEMA ORDER BY t.TABLE_SCHEMA, t.TABLE_NAME, c.ORDINAL_POSITION;';
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
        bool $disablePagination = false,
    ): array {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        if (!$this->hasConnection()) {
            return [
                'status' => 'error',
                'message' => lang('App.feedback.session_lost'),
            ];
        }

        $startTime = microtime(true);
        $totalRows = 0;
        $allResults = [];
        $totalRowsAffected = 0;
        $paginated = false;
        $trimmedSql = ltrim($sql);
        $normalizedSql = preg_replace('/\s+/', ' ', $trimmedSql);

        $isSingleSelect =
            substr_count(strtoupper($normalizedSql), 'SELECT ') === 1;
        $hasTopClause = stripos($normalizedSql, 'SELECT TOP ') === 0;

        $isPaginatable = $isSingleSelect && !$hasTopClause;

        if ($disablePagination) {
            $isPaginatable = false;
        }

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
                    lang('App.feedback.syntax_error') .
                    ($errors[0]['message'] ??
                        lang('App.feedback.unknown_error')),
            ];
        }

        if ($pageSize <= 0) {
            $pageSize = 1000;
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
        if (!$this->hasConnection()) {
            return [
                'status' => 'error',
                'message' => lang('App.feedback.session_lost'),
            ];
        }

        sqlsrv_query($this->conn, 'SET SHOWPLAN_XML ON;');
        $stmt = sqlsrv_query($this->conn, $sql);

        if ($stmt === false) {
            sqlsrv_query($this->conn, 'SET SHOWPLAN_XML OFF;');
            $errors = sqlsrv_errors();
            return ['status' => 'error', 'message' => $errors[0]['message']];
        }

        $xmlPlan = '';

        do {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $xmlPlan .= current($row);
            }
        } while (sqlsrv_next_result($stmt));

        sqlsrv_query($this->conn, 'SET SHOWPLAN_XML OFF;');

        if (empty($xmlPlan)) {
            return [
                'status' => 'error',
                'message' => lang(
                    'App.feedback.execution_plan_generation_failed',
                ),
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
        if (!$this->hasConnection()) {
            return [];
        }
        $likeTerm = '%' . $searchTerm . '%';
        $sql =
            "SELECT 'TABLE' AS ObjectType, TABLE_SCHEMA AS ObjectSchema, TABLE_NAME AS ObjectName FROM [" .
            $database .
            '].INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME LIKE ? UNION ALL SELECT ROUTINE_TYPE AS ObjectType, ROUTINE_SCHEMA AS ObjectSchema, ROUTINE_NAME AS ObjectName FROM [' .
            $database .
            '].INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_NAME LIKE ?;';
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
        string $type,
    ): ?string {
        if (!$this->hasConnection()) {
            return null;
        }

        $type = strtoupper($type);

        if ($type === 'TABLE' || $type === 'BASE TABLE') {
            $sql = "
                SELECT
                    '    [' + c.name + '] ' +
                    UPPER(tp.name) +
                    CASE
                        WHEN tp.name IN ('varchar', 'nvarchar', 'char', 'nchar', 'binary', 'varbinary') THEN '(' + IIF(c.max_length = -1, 'MAX', CAST(c.max_length AS VARCHAR(10))) + ')'
                        WHEN tp.name IN ('decimal', 'numeric') THEN '(' + CAST(c.precision AS VARCHAR(10)) + ', ' + CAST(c.scale AS VARCHAR(10)) + ')'
                        WHEN tp.name IN ('datetime2', 'time') THEN '(' + CAST(c.scale AS VARCHAR(10)) + ')'
                        ELSE ''
                    END +
                    ' ' +
                    CASE WHEN c.is_nullable = 0 THEN 'NOT NULL' ELSE 'NULL' END +
                    ISNULL(' DEFAULT ' + dc.definition, '') AS column_definition,
                    pk.is_primary_key
                FROM [{$database}].sys.columns c
                JOIN [{$database}].sys.tables st ON c.object_id = st.object_id
                JOIN [{$database}].sys.schemas ss ON st.schema_id = ss.schema_id
                JOIN [{$database}].sys.types tp ON c.user_type_id = tp.user_type_id
                LEFT JOIN [{$database}].sys.default_constraints dc ON c.default_object_id = dc.object_id
                OUTER APPLY (
                    SELECT 1 AS is_primary_key
                    FROM [{$database}].sys.index_columns ic
                    JOIN [{$database}].sys.indexes i ON ic.object_id = i.object_id AND ic.index_id = i.index_id
                    WHERE i.is_primary_key = 1
                    AND ic.object_id = c.object_id
                    AND ic.column_id = c.column_id
                ) pk
                WHERE st.name = ? AND ss.name = ?
                ORDER BY c.column_id;
            ";

            $params = [$objectName, $schema];
            $stmt = sqlsrv_query($this->conn, $sql, $params);

            if ($stmt) {
                $cols = [];
                $pk_columns = [];
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $cols[] = $row['column_definition'];
                    if ($row['is_primary_key']) {
                        preg_match(
                            '/\[(.*?)\]/',
                            $row['column_definition'],
                            $matches,
                        );
                        if (isset($matches[1])) {
                            $pk_columns[] = '[' . $matches[1] . ']';
                        }
                    }
                }
                sqlsrv_free_stmt($stmt);

                if (empty($cols)) {
                    return lang(
                        'App.feedback.db_could_not_retrieve_definition',
                        [$objectName],
                    );
                }

                $script = "CREATE TABLE [{$schema}].[{$objectName}] (\n";
                $script .= implode(",\n", $cols);

                if (!empty($pk_columns)) {
                    $script .=
                        ",\n    CONSTRAINT [PK_{$objectName}] PRIMARY KEY CLUSTERED (" .
                        implode(', ', $pk_columns) .
                        ' ASC)';
                }

                $script .= "\n);";

                return $script;
            }
        } else {
            $fullObjectName = "[{$schema}].[{$objectName}]";
            $sql = 'EXEC sp_helptext ?';
            $params = [$fullObjectName];

            $stmt = sqlsrv_query($this->conn, $sql, $params);

            if ($stmt) {
                $definition = '';
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $definition .= $row['Text'];
                }
                sqlsrv_free_stmt($stmt);

                if (!empty($definition)) {
                    $definition = preg_replace(
                        '/^\s*CREATE/i',
                        'ALTER',
                        $definition,
                    );
                }

                return $definition;
            }
        }

        return null;
    }

    /**
     * Retrieves a list of all SQL Server Agent jobs with their status.
     *
     * @return array A list of jobs.
     */
    public function getAgentJobs(): array
    {
        if (!$this->hasConnection()) {
            return [];
        }
        $sql = "
        WITH LastJobHistory AS (
            SELECT
                job_id,
                MAX(instance_id) AS last_instance_id
            FROM msdb.dbo.sysjobhistory
            GROUP BY job_id
        )
        SELECT
            j.name AS job_name,
            j.enabled,
            ja.run_requested_date,
            CASE
                WHEN ja.start_execution_date IS NOT NULL AND ja.stop_execution_date IS NULL THEN 'Running'
                ELSE ls.step_name
            END AS last_run_step,
            jh.run_status,
            msdb.dbo.agent_datetime(jh.run_date, jh.run_time) AS last_run_datetime,
            s.next_run_date,
            s.next_run_time
        FROM msdb.dbo.sysjobs j
        LEFT JOIN msdb.dbo.sysjobactivity ja ON j.job_id = ja.job_id AND ja.session_id = (SELECT MAX(session_id) FROM msdb.dbo.syssessions)
        LEFT JOIN LastJobHistory ljh ON j.job_id = ljh.job_id
        LEFT JOIN msdb.dbo.sysjobhistory jh ON ljh.last_instance_id = jh.instance_id
        LEFT JOIN msdb.dbo.sysjobsteps ls ON j.job_id = ls.job_id AND jh.step_id = ls.step_id
        LEFT JOIN msdb.dbo.sysjobschedules s ON j.job_id = s.job_id
        ORDER BY j.name;
    ";
        $stmt = sqlsrv_query($this->conn, $sql);
        $jobs = [];
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $jobs[] = $row;
            }
            sqlsrv_free_stmt($stmt);
        }
        return $jobs;
    }

    /**
     * Starts a SQL Server Agent job.
     *
     * @param string $jobName The name of the job to start.
     * @return array Status of the operation.
     */
    public function startAgentJob(string $jobName): array
    {
        if (!$this->hasConnection()) {
            return ['status' => 'error', 'message' => 'No connection.'];
        }
        $sql = 'EXEC msdb.dbo.sp_start_job ?';
        $params = [$jobName];
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        if ($stmt) {
            sqlsrv_free_stmt($stmt);
            return ['status' => 'success'];
        }
        return [
            'status' => 'error',
            'message' => sqlsrv_errors()[0]['message'],
        ];
    }

    /**
     * Stops a SQL Server Agent job.
     *
     * @param string $jobName The name of the job to stop.
     * @return array Status of the operation.
     */
    public function stopAgentJob(string $jobName): array
    {
        if (!$this->hasConnection()) {
            return ['status' => 'error', 'message' => 'No connection.'];
        }
        $sql = 'EXEC msdb.dbo.sp_stop_job ?';
        $params = [$jobName];
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        if ($stmt) {
            sqlsrv_free_stmt($stmt);
            return ['status' => 'success'];
        }
        return [
            'status' => 'error',
            'message' => sqlsrv_errors()[0]['message'],
        ];
    }

    /**
     * Retrieves the execution history for a specific job.
     *
     * @param string $jobName The name of the job.
     * @return array The job's history.
     */
    public function getAgentJobHistory(string $jobName): array
    {
        if (!$this->hasConnection()) {
            return [];
        }
        $sql = "
        SELECT
            h.instance_id,
            msdb.dbo.agent_datetime(h.run_date, h.run_time) AS run_datetime,
            h.step_name,
            h.run_status,
            h.run_duration,
            h.message
        FROM msdb.dbo.sysjobs j
        JOIN msdb.dbo.sysjobhistory h ON j.job_id = h.job_id
        WHERE j.name = ?
        ORDER BY run_datetime DESC;
    ";
        $params = [$jobName];
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        $history = [];
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $history[] = $row;
            }
            sqlsrv_free_stmt($stmt);
        }
        return $history;
    }

    /**
     * Retrieves the primary key column name for a given table from SQL Server.
     *
     * @param string $database The name of the database.
     * @param string $table The name of the table.
     * @return string|null The name of the primary key column, or null if not found.
     */
    public function getPrimaryKey(string $database, string $table): ?string
    {
        if (!$this->hasConnection()) {
            return null;
        }
        $sql = "
        SELECT KU.COLUMN_NAME
        FROM [{$database}].INFORMATION_SCHEMA.TABLE_CONSTRAINTS AS TC
        INNER JOIN [{$database}].INFORMATION_SCHEMA.KEY_COLUMN_USAGE AS KU
            ON TC.CONSTRAINT_TYPE = 'PRIMARY KEY' AND
               TC.CONSTRAINT_NAME = KU.CONSTRAINT_NAME AND
               KU.TABLE_NAME = ?
    ";
        $params = [$table];
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        if ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
            return $row['COLUMN_NAME'];
        }
        return null;
    }

    /**
     * Updates a single record in a SQL Server table based on its primary key.
     *
     * @param string $database The name of the database.
     * @param string $schema The schema of the table.
     * @param string $table The name of the table.
     * @param string $primaryKey The name of the primary key column.
     * @param mixed $primaryKeyValue The value of the primary key for the record to update.
     * @param array $data An associative array of [column => value] pairs to update.
     * @return array An array with 'status' and 'message' keys.
     */
    public function updateRecord(
        string $database,
        string $schema,
        string $table,
        string $primaryKey,
        $primaryKeyValue,
        array $data,
    ): array {
        if (!$this->hasConnection() || empty($data)) {
            return [
                'status' => 'error',
                'message' => lang('App.db_invalid_operation'),
            ];
        }

        $setClauses = [];
        $params = [];
        foreach ($data as $column => $value) {
            $setClauses[] = "[{$column}] = ?";
            $params[] = $value;
        }
        $params[] = $primaryKeyValue;

        $sql =
            "UPDATE [{$database}].[{$schema}].[{$table}] SET " .
            implode(', ', $setClauses) .
            " WHERE [{$primaryKey}] = ?";

        $stmt = sqlsrv_query($this->conn, $sql, $params);
        if ($stmt) {
            return ['status' => 'success'];
        }

        return [
            'status' => 'error',
            'message' => sqlsrv_errors()[0]['message'],
        ];
    }

    /**
     * Retrieves the detailed structure of a given SQL Server table.
     *
     * @param string $database The name of the database.
     * @param string $schema The schema of the table.
     * @param string $table The name of the table.
     * @return array An array of column definitions.
     */
    public function getTableStructure(
        string $database,
        string $schema,
        string $table,
    ): array {
        if (!$this->hasConnection()) {
            return [];
        }
        $sql = "
            SELECT
                c.COLUMN_NAME AS [name],
                UPPER(c.DATA_TYPE) +
                    CASE
                        WHEN c.CHARACTER_MAXIMUM_LENGTH IS NOT NULL THEN '(' + CAST(c.CHARACTER_MAXIMUM_LENGTH AS VARCHAR) + ')'
                        WHEN c.NUMERIC_PRECISION IS NOT NULL THEN '(' + CAST(c.NUMERIC_PRECISION AS VARCHAR) + ',' + CAST(c.NUMERIC_SCALE AS VARCHAR) + ')'
                        ELSE ''
                    END AS [type],
                IIF(c.IS_NULLABLE = 'YES', 1, 0) as [nullable],
                IIF(tc.CONSTRAINT_TYPE IS NOT NULL, 1, 0) as [is_pk]
            FROM [{$database}].INFORMATION_SCHEMA.COLUMNS c
            LEFT JOIN [{$database}].INFORMATION_SCHEMA.KEY_COLUMN_USAGE ku
                ON c.TABLE_SCHEMA = ku.TABLE_SCHEMA
                AND c.TABLE_NAME = ku.TABLE_NAME
                AND c.COLUMN_NAME = ku.COLUMN_NAME
            LEFT JOIN [{$database}].INFORMATION_SCHEMA.TABLE_CONSTRAINTS tc
                ON ku.CONSTRAINT_NAME = tc.CONSTRAINT_NAME
                AND ku.TABLE_SCHEMA = tc.TABLE_SCHEMA
                AND ku.TABLE_NAME = tc.TABLE_NAME
                AND tc.CONSTRAINT_TYPE = 'PRIMARY KEY'
            WHERE c.TABLE_NAME = ? AND c.TABLE_SCHEMA = ?
            ORDER BY c.ORDINAL_POSITION
        ";
        $params = [$table, $schema];
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        $structure = [];
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                // Extrai o tamanho do tipo
                preg_match('/\((\d+)(?:,\s*\d+)?\)/', $row['type'], $matches);
                $row['size'] = $matches[1] ?? '';
                $row['type'] = preg_replace('/\(.*\)/', '', $row['type']); // Remove o tamanho do tipo

                $structure[] = $row;
            }
            sqlsrv_free_stmt($stmt);
        }
        return $structure;
    }

    /**
     * Creates a new table in the SQL Server database.
     *
     * @param string $database The name of the database.
     * @param string $schema The schema where the table will be created.
     * @param string $table The name of the new table.
     * @param array $columns An array of column definitions. Each element is an array with 'name', 'type', 'size', 'nullable'.
     * @param string|null $primaryKey The name of the column to be the primary key.
     * @return array An array with 'status' and 'message' keys.
     */
    /**
     * Creates a new table in the SQL Server database.
     * Includes robust error handling for DDL statements.
     *
     * @param string $database The name of the database.
     * @param string $schema The schema where the table will be created.
     * @param string $table The name of the new table.
     * @param array $columns An array of column definitions.
     * @param string|null $primaryKey The name of the column to be the primary key.
     * @return array An array with 'status' and 'message' keys.
     */
    public function createTable(
        string $database,
        string $schema,
        string $table,
        array $columns,
        ?string $primaryKey,
    ): array {
        if (!$this->hasConnection()) {
            return [
                'status' => 'error',
                'message' => lang('App.feedback.session_lost'),
            ];
        }

        $colsDefs = [];
        foreach ($columns as $col) {
            $def = "[{$col['name']}] {$col['type']}";
            if (!empty($col['size'])) {
                $def .= "({$col['size']})";
            }
            $def .= $col['nullable'] ? ' NULL' : ' NOT NULL';
            $colsDefs[] = $def;
        }

        if ($primaryKey) {
            $colsDefs[] = "CONSTRAINT PK_{$table} PRIMARY KEY ([{$primaryKey}])";
        }

        $sql =
            "CREATE TABLE [{$database}].[{$schema}].[{$table}] (" .
            implode(', ', $colsDefs) .
            ');';

        sqlsrv_query($this->conn, $sql);
        $errors = sqlsrv_errors(SQLSRV_ERR_ALL);

        if ($errors !== null) {
            foreach ($errors as $error) {
                if (
                    substr($error['SQLSTATE'], 0, 2) !== '01' &&
                    substr($error['SQLSTATE'], 0, 2) !== '00'
                ) {
                    return [
                        'status' => 'error',
                        'message' => $error['message'],
                    ]; // Erro real encontrado
                }
            }
        }

        return ['status' => 'success']; // Nenhum erro real foi encontrado
    }

    /**
     * Adds a new column to an existing table in SQL Server.
     *
     * @param string $database The name of the database.
     * @param string $schema The schema of the table.
     * @param string $table The name of the table to alter.
     * @param array $column The definition of the column to add.
     * @return array An array with 'status' and 'message' keys.
     */
    public function addColumn(
        string $database,
        string $schema,
        string $table,
        array $column,
    ): array {
        if (!$this->hasConnection()) {
            return [
                'status' => 'error',
                'message' => lang('App.feedback.session_lost'),
            ];
        }

        $def = "[{$column['name']}] {$column['type']}";
        if (!empty($column['size'])) {
            $def .= "({$column['size']})";
        }
        $def .= $column['nullable'] ? ' NULL' : ' NOT NULL';

        $sql = "ALTER TABLE [{$database}].[{$schema}].[{$table}] ADD {$def};";

        sqlsrv_query($this->conn, $sql);
        $errors = sqlsrv_errors(SQLSRV_ERR_ALL);

        if ($errors !== null) {
            foreach ($errors as $error) {
                if (
                    substr($error['SQLSTATE'], 0, 2) !== '01' &&
                    substr($error['SQLSTATE'], 0, 2) !== '00'
                ) {
                    return [
                        'status' => 'error',
                        'message' => $error['message'],
                    ];
                }
            }
        }

        return ['status' => 'success'];
    }

    /**
     * Drops (deletes) a table from the SQL Server database.
     *
     * @param string $database The name of the database.
     * @param string $schema The schema of the table.
     * @param string $table The name of the table to drop.
     * @return array An array with 'status' and 'message' keys.
     */
    public function dropTable(
        string $database,
        string $schema,
        string $table,
    ): array {
        if (!$this->hasConnection()) {
            return [
                'status' => 'error',
                'message' => lang('App.feedback.session_lost'),
            ];
        }

        $sql = "DROP TABLE [{$database}].[{$schema}].[{$table}];";

        sqlsrv_query($this->conn, $sql);
        $errors = sqlsrv_errors(SQLSRV_ERR_ALL);

        if ($errors !== null) {
            foreach ($errors as $error) {
                if (
                    substr($error['SQLSTATE'], 0, 2) !== '01' &&
                    substr($error['SQLSTATE'], 0, 2) !== '00'
                ) {
                    return [
                        'status' => 'error',
                        'message' => $error['message'],
                    ];
                }
            }
        }

        return ['status' => 'success'];
    }
}
