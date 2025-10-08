<?php

namespace App\Models;

use App\Libraries\MySqlConnector;

/**
 * The data access layer for interacting with a MySQL database.
 */
class MySqlModel extends BaseDatabaseModel
{
    /**
     * @var \mysqli|false|null The active MySQLi connection resource.
     */
    private $conn;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->conn = MySqlConnector::getConnection();
    }

    /**
     * Checks if the model has a valid and active connection.
     * @return bool
     */
    private function hasConnection(): bool
    {
        return $this->conn !== null && $this->conn !== false;
    }

    /**
     * Translates common SQL Server syntax to MySQL syntax.
     * @param string $sql The original SQL query.
     * @param int|null &$limit Extracts the TOP N value if present.
     * @return string The translated SQL query.
     */
    private function translateSqlServerToMySql(
        string $sql,
        ?int &$limit = null,
    ): string {
        // Replace SQL Server's TOP N syntax and extract the value
        $sql = preg_replace_callback(
            '/^\s*SELECT\s+TOP\s+(\d+)/i',
            function ($matches) use (&$limit) {
                $limit = (int) $matches[1];
                return 'SELECT';
            },
            $sql,
            1,
        );

        // Replace [object] with `object`
        $sql = str_replace(['[', ']'], '`', $sql);

        return $sql;
    }

    /**
     * Attempts a preliminary connection to the MySQL Server to validate credentials.
     *
     * @param array $credentials An associative array of connection details.
     * @return array An associative array with 'status' (bool) and 'message' (string).
     */
    public function tryConnect(array $credentials): array
    {
        $conn = @mysqli_connect(
            $credentials['host'],
            $credentials['user'],
            $credentials['password'],
            $credentials['database'] ?: null,
            (int) $credentials['port'],
        );

        if ($conn) {
            mysqli_close($conn);
            return [
                'status' => true,
                'message' => lang('App.connection_success'),
            ];
        }

        return [
            'status' => false,
            'message' =>
                lang('App.connection_failed') . ': ' . mysqli_connect_error(),
        ];
    }

    /**
     * Retrieves a list of all user databases on the server.
     * @return array A list of databases.
     */
    public function getDatabases(): array
    {
        if (!$this->hasConnection()) {
            return [];
        }
        $sql = 'SHOW DATABASES;';
        $result = $this->conn->query($sql);
        $databases = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                if (
                    !in_array($row['Database'], [
                        'information_schema',
                        'mysql',
                        'performance_schema',
                        'sys',
                    ])
                ) {
                    $databases[] = ['name' => $row['Database']];
                }
            }
            $result->free();
        }
        return $databases;
    }

    /**
     * Fetches all tables and views for a given database.
     * @param string $database The name of the database.
     * @return array A list of objects.
     */
    public function getTablesAndViews(string $database): array
    {
        if (!$this->hasConnection()) {
            return [];
        }
        $sql =
            'SELECT TABLE_SCHEMA, TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? ORDER BY TABLE_SCHEMA, TABLE_NAME;';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $database);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            $result->free();
        }
        $stmt->close();
        return $items;
    }

    /**
     * Retrieves all columns for a specific table within a database.
     * @param string $database The name of the database.
     * @param string $table The name of the table.
     * @return array A list of columns.
     */
    public function getColumns(string $database, string $table): array
    {
        if (!$this->hasConnection()) {
            return [];
        }
        $sql =
            'SELECT COLUMN_NAME, DATA_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION;';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $database, $table);
        $stmt->execute();
        $result = $stmt->get_result();
        $columns = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $columns[] = $row;
            }
            $result->free();
        }
        $stmt->close();
        return $columns;
    }

    /**
     * Fetches all stored procedures and functions for a given database.
     * @param string $database The name of the database.
     * @return array A list of routines.
     */
    public function getProceduresAndFunctions(string $database): array
    {
        if (!$this->hasConnection()) {
            return [];
        }
        $sql =
            'SELECT ROUTINE_SCHEMA, ROUTINE_NAME, ROUTINE_TYPE FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = ? ORDER BY ROUTINE_TYPE, ROUTINE_NAME;';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $database);
        $stmt->execute();
        $result = $stmt->get_result();
        $routines = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $routines[] = $row;
            }
            $result->free();
        }
        $stmt->close();
        return $routines;
    }

    /**
     * Retrieves the parameters for a specific stored procedure or function.
     * @param string $database The name of the database.
     * @param string $routineSchema The schema of the routine.
     * @param string $routineName The name of the routine.
     * @return array A list of parameters.
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
            'SELECT PARAMETER_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH FROM information_schema.PARAMETERS WHERE SPECIFIC_SCHEMA = ? AND SPECIFIC_NAME = ? ORDER BY ORDINAL_POSITION;';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $routineSchema, $routineName);
        $stmt->execute();
        $result = $stmt->get_result();
        $params = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $length = $row['CHARACTER_MAXIMUM_LENGTH'] ?? '';
                $row['full_type'] =
                    $row['DATA_TYPE'] . ($length ? "({$length})" : '');
                $params[] = $row;
            }
            $result->free();
        }
        $stmt->close();
        return $params;
    }

    /**
     * Builds a comprehensive schema dictionary for the CodeMirror Intellisense feature.
     * @return array The schema dictionary.
     */
    public function getAutocompletionSchema(): array
    {
        if (!$this->hasConnection()) {
            return [];
        }
        $database = session()->get('db_database');
        if (empty($database)) {
            $result = $this->conn->query('SELECT DATABASE() as dbname');
            if ($result && ($row = $result->fetch_assoc())) {
                $database = $row['dbname'];
            }
        }
        if (empty($database)) {
            return [];
        }

        $sql =
            'SELECT t.TABLE_NAME, c.COLUMN_NAME FROM information_schema.TABLES t JOIN information_schema.COLUMNS c ON t.TABLE_NAME = c.TABLE_NAME AND t.TABLE_SCHEMA = c.TABLE_SCHEMA WHERE t.TABLE_SCHEMA = ? ORDER BY t.TABLE_NAME, c.ORDINAL_POSITION;';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $database);
        $stmt->execute();
        $result = $stmt->get_result();
        $schema = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $tableNameForHint = $row['TABLE_NAME'];
                if (!isset($schema[$tableNameForHint])) {
                    $schema[$tableNameForHint] = [];
                }
                $schema[$tableNameForHint][] = $row['COLUMN_NAME'];
            }
            $result->free();
        }
        $stmt->close();
        return $schema;
    }

    /**
     * Executes a user-provided SQL query, with support for server-side pagination.
     *
     * @param string $sql The user's full SQL query string.
     * @param int $page The page number to retrieve.
     * @param int $pageSize The number of rows per page.
     * @param bool $disablePagination Flag to disable pagination logic.
     * @return array An associative array containing the status, results, and metadata.
     */
    public function executeQuery(
        string $sql,
        int $page = 1,
        int $pageSize = 1000,
        bool $disablePagination = false,
    ): array {
        if (!$this->hasConnection()) {
            return ['status' => 'error', 'message' => lang('App.session_lost')];
        }

        $startTime = microtime(true);
        $limit = null;
        $translatedSql = $this->translateSqlServerToMySql($sql, $limit);

        if ($this->isPaginatable($translatedSql, $disablePagination, $limit)) {
            $result = $this->executePaginatedQuery($translatedSql, $page, $pageSize);
        } else {
            $result = $this->executeSimpleQuery($translatedSql, $limit);
        }

        $executionTime = number_format(microtime(true) - $startTime, 4);
        $result['executionTime'] = $executionTime;

        $this->queryLogger->logQuery(
            $sql,
            $result['status'],
            $executionTime,
            $result['totalRowsAffected'] ?? 0
        );

        return $result;
    }

    /**
     * Determines if a given SQL query can be paginated.
     *
     * @param string $sql The SQL query string.
     * @param bool $disablePagination Flag to force disable pagination.
     * @param int|null $limit A limit extracted from a TOP clause.
     * @return bool
     */
    private function isPaginatable(string $sql, bool $disablePagination, ?int $limit): bool
    {
        if ($disablePagination) {
            return false;
        }

        $trimmedSql = rtrim(trim($sql), ';');
        $isSingleSelect = preg_match('/^\s*SELECT/i', $trimmedSql) && substr_count(strtoupper($trimmedSql), ';') === 0;
        $hasLimitClause = preg_match('/LIMIT\s+\d+/i', $trimmedSql);

        return $isSingleSelect && !$hasLimitClause && $limit === null;
    }

    /**
     * Executes a paginated SELECT query for MySQL.
     *
     * @param string $sql The SQL SELECT statement.
     * @param int $page The current page number.
     * @param int $pageSize The number of rows per page.
     * @return array The result of the query execution.
     */
    private function executePaginatedQuery(string $sql, int $page, int $pageSize): array
    {
        $trimmedSql = rtrim(trim($sql), ';');

        // Get total rows
        $countSql = "SELECT COUNT(*) as TotalRows FROM ({$trimmedSql}) AS count_query;";
        $countResult = $this->conn->query($countSql);

        if ($this->conn->error) {
            return [
                'status' => 'error',
                'message' => lang('App.syntax_error') . $this->conn->error . ' (in count query)',
            ];
        }

        $totalRows = 0;
        if ($countResult && ($row = $countResult->fetch_assoc())) {
            $totalRows = $row['TotalRows'];
        }

        // Fetch paginated data
        $offset = ($page - 1) * $pageSize;
        $paginatedSql = "{$trimmedSql} LIMIT {$pageSize} OFFSET {$offset};";

        $this->conn->multi_query($paginatedSql);

        if ($this->conn->error) {
            return [
                'status' => 'error',
                'message' => lang('App.syntax_error') . $this->conn->error,
            ];
        }

        return $this->processResults(true, $totalRows, $page, $pageSize);
    }

    /**
     * Executes a non-paginated or already limited query.
     *
     * @param string $sql The SQL query to execute.
     * @param int|null $limit An optional limit from a TOP clause.
     * @return array The result of the query execution.
     */
    private function executeSimpleQuery(string $sql, ?int $limit): array
    {
        $trimmedSql = rtrim(trim($sql), ';');

        if ($limit !== null) {
            $trimmedSql .= ' LIMIT ' . $limit;
        }

        $this->conn->multi_query($trimmedSql);

        if ($this->conn->error) {
            return [
                'status' => 'error',
                'message' => lang('App.syntax_error') . $this->conn->error,
            ];
        }

        return $this->processResults();
    }

    /**
     * Processes the results from a MySQLi multi-query execution.
     *
     * @param bool $paginated
     * @param int $totalRows
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    private function processResults(bool $paginated = false, int $totalRows = 0, int $page = 1, int $pageSize = 1000): array
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $allResults = [];
        $totalRowsAffected = 0;

        do {
            $result = $this->conn->store_result();
            if ($result) {
                $headers = array_map(fn ($field) => $field->name, $result->fetch_fields());
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                $resultSet = [
                    'headers' => $headers,
                    'data' => $data,
                    'rowCount' => $result->num_rows,
                ];

                if ($paginated) {
                    $resultSet['totalRows'] = $totalRows;
                    $resultSet['currentPage'] = $page;
                    $resultSet['totalPages'] = $pageSize > 0 ? ceil($totalRows / $pageSize) : 1;
                }

                $allResults[] = $resultSet;
                $result->free();
            }

            if ($this->conn->affected_rows > -1) {
                $totalRowsAffected += $this->conn->affected_rows;
            }
        } while ($this->conn->more_results() && $this->conn->next_result());

        return [
            'status' => 'success',
            'results' => $allResults,
            'totalRowsAffected' => $totalRowsAffected,
            'resultSetCount' => count($allResults),
        ];
    }

    /**
     * Retrieves the execution plan for a given SQL query using EXPLAIN.
     * @param string $sql The SQL query to analyze.
     * @return array An associative array with the status and the JSON plan string.
     */
    public function getExecutionPlan(string $sql): array
    {
        if (!$this->hasConnection()) {
            return ['status' => 'error', 'message' => lang('App.session_lost')];
        }

        $sql = $this->translateSqlServerToMySql($sql);

        $jsonPlan = '';
        $result = $this->conn->query('EXPLAIN FORMAT=JSON ' . $sql);
        if ($result) {
            $row = $result->fetch_assoc();
            $jsonPlan = $row['EXPLAIN'];
            $result->free();
        } else {
            return ['status' => 'error', 'message' => $this->conn->error];
        }

        return [
            'status' => 'success',
            'plan' => $jsonPlan,
            'db_type' => 'mysql',
        ];
    }

    /**
     * Searches for database objects (tables, views, routines) by name.
     * @param string $database The name of the database to search within.
     * @param string $searchTerm The term to search for.
     * @return array A list of found objects.
     */
    public function searchObjects(string $database, string $searchTerm): array
    {
        if (!$this->hasConnection()) {
            return [];
        }
        $likeTerm = '%' . $searchTerm . '%';

        $sql = "
            SELECT 'BASE TABLE' AS ObjectType, TABLE_SCHEMA AS ObjectSchema, TABLE_NAME AS ObjectName FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME LIKE ?
            UNION ALL
            SELECT 'VIEW' AS ObjectType, TABLE_SCHEMA AS ObjectSchema, TABLE_NAME AS ObjectName FROM information_schema.VIEWS WHERE TABLE_SCHEMA = ? AND TABLE_NAME LIKE ?
            UNION ALL
            SELECT ROUTINE_TYPE AS ObjectType, ROUTINE_SCHEMA AS ObjectSchema, ROUTINE_NAME AS ObjectName FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = ? AND ROUTINE_NAME LIKE ?;
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            'ssssss',
            $database,
            $likeTerm,
            $database,
            $likeTerm,
            $database,
            $likeTerm,
        );
        $stmt->execute();
        $result = $stmt->get_result();
        $items = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            $result->free();
        }
        $stmt->close();
        return $items;
    }

    /**
     * Fetches the source code definition of a database object.
     * @param string $database The name of the database.
     * @param string $schema The schema of the object.
     * @param string $objectName The name of the object.
     * @param string $type The type of the object (TABLE, VIEW, etc.).
     * @return string|null The full SQL definition of the object.
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
        $query = '';

        switch ($type) {
            case 'TABLE':
            case 'BASE TABLE':
                $query = "SHOW CREATE TABLE `{$database}`.`{$objectName}`";
                break;
            case 'VIEW':
                $query = "SHOW CREATE VIEW `{$database}`.`{$objectName}`";
                break;
            case 'PROCEDURE':
                $query = "SHOW CREATE PROCEDURE `{$database}`.`{$objectName}`";
                break;
            case 'FUNCTION':
                $query = "SHOW CREATE FUNCTION `{$database}`.`{$objectName}`";
                break;
            default:
                return lang('App.db_object_type_not_supported', [$type]);
        }

        $result = $this->conn->query($query);
        if ($result && ($row = $result->fetch_assoc())) {
            $key = 'Create Table';
            if (isset($row['Create View'])) {
                $key = 'Create View';
            }
            if (isset($row['Create Procedure'])) {
                $key = 'Create Procedure';
            }
            if (isset($row['Create Function'])) {
                $key = 'Create Function';
            }

            return $row[$key];
        }
        return lang('App.db_could_not_retrieve_definition', [$objectName]);
    }

    /**
     * Retrieves the primary key column name for a given table from MySQL using information_schema.
     * This version is case-insensitive to work reliably across different server configurations.
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
            SELECT k.COLUMN_NAME
            FROM information_schema.table_constraints t
            JOIN information_schema.key_column_usage k
            USING(constraint_name, table_schema, table_name)
            WHERE t.constraint_type = 'PRIMARY KEY'
              AND LOWER(t.table_schema) = LOWER(?)
              AND LOWER(t.table_name) = LOWER(?);
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $database, $table);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && ($row = $result->fetch_assoc())) {
            $stmt->close();
            return $row['COLUMN_NAME'];
        }

        $stmt->close();
        return null;
    }

    /**
     * Updates a single record in a MySQL table based on its primary key.
     *
     * @param string $database The name of the database.
     * @param string $schema The schema of the table (unused in MySQL context).
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

        $startTime = microtime(true);

        $setClauses = [];
        $params = [];
        $types = '';
        foreach ($data as $column => $value) {
            $setClauses[] = "`{$column}` = ?";
            $params[] = $value;
            $types .= 's';
        }
        $params[] = $primaryKeyValue;
        $types .= 's';

        $sql =
            "UPDATE `{$database}`.`{$table}` SET " .
            implode(', ', $setClauses) .
            " WHERE `{$primaryKey}` = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $executionTime = microtime(true) - $startTime;
            $this->queryLogger->logQuery(
                $sql,
                'success',
                $executionTime,
                $stmt->affected_rows,
            );
            return ['status' => 'success'];
        }

        $executionTime = microtime(true) - $startTime;
        $this->queryLogger->logQuery($sql, 'error', $executionTime, 0);

        return ['status' => 'error', 'message' => $stmt->error];
    }

    /**
     * Retrieves a list of all scheduled events for the current database.
     * @return array
     */
    public function getEvents(): array
    {
        if (!$this->hasConnection()) {
            return [];
        }
        $sql = 'SHOW EVENTS;';
        $result = $this->conn->query($sql);
        $events = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $events[] = [
                    'name' => $row['Name'],
                    'status' => $row['Status'],
                    'next_execution' => $row['Execute at'],
                ];
            }
            $result->free();
        }
        return $events;
    }

    /**
     * Toggles the status of a specific event (ENABLE/DISABLE).
     * @param string $eventName The name of the event.
     * @param string $status The new status ('ENABLE' or 'DISABLE').
     * @return array
     */
    public function toggleEventStatus(string $eventName, string $status): array
    {
        if (
            !$this->hasConnection() ||
            !in_array(strtoupper($status), ['ENABLE', 'DISABLE'])
        ) {
            return [
                'status' => 'error',
                'message' => lang('App.db_invalid_operation'),
            ];
        }

        $safeEventName = '`' . str_replace('`', '``', $eventName) . '`';

        $sql = "ALTER EVENT {$safeEventName} {$status};";
        if ($this->conn->query($sql)) {
            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => $this->conn->error];
    }

    /**
     * Retrieves the CREATE statement for a specific event.
     * @param string $eventName
     * @return string|null
     */
    public function getEventDefinition(string $eventName): ?string
    {
        if (!$this->hasConnection()) {
            return null;
        }

        $safeEventName = $this->conn->real_escape_string($eventName);
        $sql = "SHOW CREATE EVENT `{$safeEventName}`";

        $result = $this->conn->query($sql);
        if ($result && ($row = $result->fetch_assoc())) {
            return $row['Create Event'];
        }
        return lang('App.db_event_not_found', [$eventName]);
    }

    /**
     * Retrieves the detailed structure of a given MySQL table.
     *
     * @param string $database The name of the database.
     * @param string $schema The schema (same as database for MySQL).
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
                c.COLUMN_NAME as 'name',
                c.COLUMN_TYPE as 'type',
                IF(c.IS_NULLABLE = 'YES', 1, 0) as 'nullable',
                IF(kcu.CONSTRAINT_NAME IS NOT NULL, 1, 0) as 'is_pk'
            FROM information_schema.COLUMNS c
            LEFT JOIN information_schema.KEY_COLUMN_USAGE kcu
                ON c.TABLE_SCHEMA = kcu.TABLE_SCHEMA
                AND c.TABLE_NAME = kcu.TABLE_NAME
                AND c.COLUMN_NAME = kcu.COLUMN_NAME
                AND kcu.CONSTRAINT_NAME = 'PRIMARY'
            WHERE c.TABLE_SCHEMA = ? AND c.TABLE_NAME = ?
            ORDER BY c.ORDINAL_POSITION;
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $database, $table);
        $stmt->execute();
        $result = $stmt->get_result();
        $structure = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $structure[] = $row;
            }
            $result->free();
        }
        $stmt->close();
        return $structure;
    }

    /**
     * Creates a new table in the MySQL database.
     *
     * @param string $database The name of the database.
     * @param string $schema The schema (same as database for MySQL).
     * @param string $table The name of the new table.
     * @param array $columns An array of column definitions.
     * @param array $primaryKeys An array of column names for the primary key.
     * @return array An array with 'status' and 'message' keys.
     */
    public function createTable(
        string $database,
        string $schema,
        string $table,
        array $columns,
        array $primaryKeys,
    ): array {
        if (!$this->hasConnection()) {
            return ['status' => 'error', 'message' => lang('App.session_lost')];
        }

        $startTime = microtime(true);

        $colsDefs = [];
        foreach ($columns as $col) {
            $def = "`{$col['name']}` {$col['type']}";
            if (!empty($col['size'])) {
                $def .= "({$col['size']})";
            }
            $def .= $col['nullable'] ? ' NULL' : ' NOT NULL';
            $colsDefs[] = $def;
        }

        if (!empty($primaryKeys)) {
            $quotedKeys = array_map(fn ($key) => "`{$key}`", $primaryKeys);
            $colsDefs[] = 'PRIMARY KEY (' . implode(', ', $quotedKeys) . ')';
        }

        $sql =
            "CREATE TABLE `{$database}`.`{$table}` (" .
            implode(', ', $colsDefs) .
            ') ENGINE=InnoDB;';

        if ($this->conn->query($sql)) {
            $executionTime = microtime(true) - $startTime;
            $this->queryLogger->logQuery($sql, 'success', $executionTime, 0);

            return ['status' => 'success'];
        }

        $executionTime = microtime(true) - $startTime;
        $this->queryLogger->logQuery($sql, 'error', $executionTime, 0);

        return ['status' => 'error', 'message' => $this->conn->error];
    }

    /**
     * Adds a new column to an existing table in MySQL.
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
            return ['status' => 'error', 'message' => lang('App.session_lost')];
        }

        $startTime = microtime(true);

        $def = "`{$column['name']}` {$column['type']}";
        if (!empty($column['size'])) {
            $def .= "({$column['size']})";
        }
        $def .= $column['nullable'] ? ' NULL' : ' NOT NULL';

        $sql = "ALTER TABLE `{$database}`.`{$table}` ADD COLUMN {$def};";

        if ($this->conn->query($sql)) {
            $executionTime = microtime(true) - $startTime;
            $this->queryLogger->logQuery($sql, 'success', $executionTime, 0);

            return ['status' => 'success'];
        }

        $executionTime = microtime(true) - $startTime;
        $this->queryLogger->logQuery($sql, 'error', $executionTime, 0);

        return ['status' => 'error', 'message' => $this->conn->error];
    }

    /**
     * Drops (deletes) a table from the MySQL database.
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
            return ['status' => 'error', 'message' => lang('App.session_lost')];
        }

        $startTime = microtime(true);

        $sql = "DROP TABLE `{$database}`.`{$table}`;";

        if ($this->conn->query($sql)) {
            $executionTime = microtime(true) - $startTime;
            $this->queryLogger->logQuery($sql, 'success', $executionTime, 0);

            return ['status' => 'success'];
        }

        $executionTime = microtime(true) - $startTime;
        $this->queryLogger->logQuery($sql, 'error', $executionTime, 0);

        return ['status' => 'error', 'message' => $this->conn->error];
    }

    /**
     * Retrieves a list of all indexes for a given MySQL table.
     *
     * @param string $database The name of the database.
     * @param string $schema The schema (same as database for MySQL).
     * @param string $table The name of the table.
     * @return array An array of index definitions.
     */
    public function getIndexes(
        string $database,
        string $schema,
        string $table,
    ): array {
        if (!$this->hasConnection()) {
            return [];
        }
        $sql = "SHOW INDEX FROM `{$database}`.`{$table}`;";
        $result = $this->conn->query($sql);
        $indexesData = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $indexesData[$row['Key_name']]['columns'][] =
                    $row['Column_name'];
                $indexesData[$row['Key_name']]['is_unique'] =
                    $row['Non_unique'] == 0;
                $indexesData[$row['Key_name']]['type_desc'] =
                    $row['Index_type'];
            }
            $result->free();
        }

        $indexes = [];
        foreach ($indexesData as $name => $data) {
            $indexes[] = [
                'index_name' => $name,
                'columns' => implode(', ', $data['columns']),
                'is_unique' => $data['is_unique'],
                'type_desc' => $data['type_desc'],
            ];
        }
        return $indexes;
    }

    public function createIndex(string $database, string $schema, string $table, string $indexName, array $columns, bool $isUnique): array
    {
        if (!$this->hasConnection()) {
            return ['status' => 'error', 'message' => lang('App.feedback.session_lost')];
        }
        $unique = $isUnique ? 'UNIQUE' : '';
        $cols = implode(', ', array_map(fn ($c) => "`{$c}`", $columns));
        $sql = "CREATE {$unique} INDEX `{$indexName}` ON `{$database}`.`{$table}` ({$cols});";

        if ($this->conn->query($sql)) {
            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => $this->conn->error];
    }

    public function dropIndex(string $database, string $schema, string $table, string $indexName): array
    {
        if (!$this->hasConnection()) {
            return ['status' => 'error', 'message' => lang('App.feedback.session_lost')];
        }
        $sql = "DROP INDEX `{$indexName}` ON `{$database}`.`{$table}`;";

        if ($this->conn->query($sql)) {
            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => $this->conn->error];
    }

    public function getAllTables(string $database): array
    {
        if (!$this->hasConnection()) {
            return [];
        }
        $sql = 'SELECT TABLE_NAME AS name FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? ORDER BY TABLE_NAME;';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $database);
        $stmt->execute();
        $result = $stmt->get_result();
        $tables = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $tables[] = $row;
            }
        }
        return $tables;
    }

    public function getForeignKeys(string $database, string $schema, string $table): array
    {
        $sql = "
            SELECT
                kcu.constraint_name as fk_name,
                kcu.column_name as columns,
                kcu.referenced_table_name as references_table,
                kcu.referenced_column_name as references_columns
            FROM information_schema.key_column_usage AS kcu
            JOIN information_schema.table_constraints AS tc
                ON kcu.constraint_name = tc.constraint_name
                AND kcu.table_schema = tc.table_schema
            WHERE tc.constraint_type = 'FOREIGN KEY'
            AND kcu.table_schema = ? AND kcu.table_name = ?;
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $database, $table);
        $stmt->execute();
        $result = $stmt->get_result();
        $fks = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $fks[] = $row;
            }
        }
        return $fks;
    }

    public function createForeignKey(string $database, string $schema, string $table, string $fkName, array $columns, string $refTable, array $refColumns): array
    {
        $cols = implode(', ', array_map(fn ($c) => "`{$c}`", $columns));
        $refCols = implode(', ', array_map(fn ($c) => "`{$c}`", $refColumns));
        $sql = "ALTER TABLE `{$database}`.`{$table}` ADD CONSTRAINT `{$fkName}` FOREIGN KEY ({$cols}) REFERENCES `{$refTable}`({$refCols});";

        if ($this->conn->query($sql)) {
            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => $this->conn->error];
    }

    public function dropForeignKey(string $database, string $schema, string $table, string $fkName): array
    {
        $sql = "ALTER TABLE `{$database}`.`{$table}` DROP FOREIGN KEY `{$fkName}`;";

        if ($this->conn->query($sql)) {
            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => $this->conn->error];
    }

    /**
     * Checks if the current MySQLi connection is active and responsive.
     *
     * This method uses the most efficient way to check a MySQLi connection
     * by calling the `ping()` method on the connection object. It's used
     * by the API's "ping" endpoint to provide a lightweight connection health check.
     *
     * @return bool True if the connection is alive, false otherwise.
     */
    public function checkConnection(): bool
    {
        if (!$this->hasConnection()) {
            return false;
        }

        // The ping method is the most efficient way to check a MySQLi connection
        return $this->conn->ping();
    }

    /**
     * Retrieves a rich, structured representation of the database schema for a given database.
     * This method is optimized to fetch all necessary data for the IntelliSense feature in a single query.
     * For MySQL, the schema is the database itself. The resulting array includes a map of all
     * objects (tables and views), their type, and a list of their columns with data types.
     *
     * @param string $database The name of the database to retrieve the schema from.
     * @return array An associative array structured for IntelliSense, containing:
     * - 'objects' (array): A map where keys are object names (e.g., 'Users')
     * and values are arrays containing the object 'type' ('table' or 'view') and a 'columns' map
     * (column name => data type).
     * - 'schemas' (array): A simple list containing only the database name, as MySQL treats schemas and databases similarly in this context.
     * Returns an empty array if the connection is not available.
     * @example
     * [
     * 'objects' => [
     * 'orders' => [
     * 'type' => 'table',
     * 'columns' => [
     * 'order_id' => 'int',
     * 'order_date' => 'datetime'
     * ]
     * ],
     * 'vw_recent_invoices' => [
     * 'type' => 'view',
     * 'columns' => [
     * 'invoice_id' => 'int',
     * 'total_amount' => 'decimal'
     * ]
     * ]
     * ],
     * 'schemas' => ['my_database']
     * ]
     */
    public function getRichSchema(string $database): array
    {
        if (!$this->hasConnection()) {
            return [];
        }

        $sql = "
            SELECT 
                t.TABLE_SCHEMA, 
                t.TABLE_NAME, 
                t.TABLE_TYPE, 
                c.COLUMN_NAME, 
                c.DATA_TYPE 
            FROM information_schema.TABLES t 
            JOIN information_schema.COLUMNS c 
                ON t.TABLE_NAME = c.TABLE_NAME AND t.TABLE_SCHEMA = c.TABLE_SCHEMA 
            WHERE t.TABLE_SCHEMA = ? 
            ORDER BY t.TABLE_NAME, c.ORDINAL_POSITION;
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $database);
        $stmt->execute();
        $result = $stmt->get_result();

        $schema = ['objects' => [], 'schemas' => [$database]];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $tableName = $row['TABLE_NAME'];
                $fullObjectName = $tableName; // In MySQL, the schema is the database itself

                if (!isset($schema['objects'][$fullObjectName])) {
                    $schema['objects'][$fullObjectName] = [
                        'type' => str_contains($row['TABLE_TYPE'], 'VIEW') ? 'view' : 'table',
                        'columns' => []
                    ];
                }

                $schema['objects'][$fullObjectName]['columns'][$row['COLUMN_NAME']] = $row['DATA_TYPE'];
            }
            $result->free();
        }

        $stmt->close();
        return $schema;
    }
}
