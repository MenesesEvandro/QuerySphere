<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Interfaces\DatabaseModelInterface;
use App\Libraries\MySqlConnector;

/**
 * The data access layer for interacting with a MySQL database.
 * This class implements all the necessary methods for QuerySphere to function with MySQL.
 */
class MySqlModel extends Model implements DatabaseModelInterface
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
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        if (!$this->hasConnection()) {
            return ['status' => 'error', 'message' => lang('App.session_lost')];
        }

        $limit = null;
        $sql = $this->translateSqlServerToMySql($sql, $limit);

        $startTime = microtime(true);
        $totalRows = 0;
        $allResults = [];
        $totalRowsAffected = 0;
        $paginated = false;

        $trimmedSql = rtrim(trim($sql), ';');
        $isSingleSelect =
            preg_match('/^\s*SELECT/i', $trimmedSql) &&
            substr_count(strtoupper($trimmedSql), ';') === 0;
        $hasLimitClause = preg_match('/LIMIT\s+\d+/i', $trimmedSql);

        if ($limit !== null && !$hasLimitClause) {
            $trimmedSql .= ' LIMIT ' . $limit;
            $hasLimitClause = true;
        }

        if ($isSingleSelect && !$disablePagination && !$hasLimitClause) {
            $paginated = true;
            $countSql = "SELECT COUNT(*) as TotalRows FROM ({$trimmedSql}) AS count_query;";
            $countResult = $this->conn->query($countSql);

            if ($this->conn->error) {
                return [
                    'status' => 'error',
                    'message' =>
                        lang('App.syntax_error') .
                        $this->conn->error .
                        ' (in count query)',
                ];
            }

            if ($countResult && ($row = $countResult->fetch_assoc())) {
                $totalRows = $row['TotalRows'];
            }

            $offset = ($page - 1) * $pageSize;
            $paginatedSql = "{$trimmedSql} LIMIT {$pageSize} OFFSET {$offset};";
            $this->conn->multi_query($paginatedSql);
        } else {
            $this->conn->multi_query($trimmedSql);
        }

        if ($this->conn->error) {
            return [
                'status' => 'error',
                'message' => lang('App.syntax_error') . $this->conn->error,
            ];
        }

        do {
            $result = $this->conn->store_result();
            if ($result) {
                $headers = [];
                foreach ($result->fetch_fields() as $field) {
                    $headers[] = $field->name;
                }

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
                    $resultSet['totalPages'] =
                        $pageSize > 0 ? ceil($totalRows / $pageSize) : 1;
                }

                $allResults[] = $resultSet;
                $result->free();
            }

            if ($this->conn->affected_rows > -1) {
                $totalRowsAffected += $this->conn->affected_rows;
            }
        } while ($this->conn->more_results() && $this->conn->next_result());

        $executionTime = number_format(microtime(true) - $startTime, 4);
        return [
            'status' => 'success',
            'results' => $allResults,
            'executionTime' => $executionTime,
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
            return ['status' => 'success'];
        }

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
}
