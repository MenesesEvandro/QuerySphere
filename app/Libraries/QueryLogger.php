<?php

namespace App\Libraries;

/**
 * A simple query logger that writes executed queries to a JSON log file.
 * Logging is controlled by the QUERY_LOGGING environment variable.
 */
class QueryLogger
{
    private $logPath;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->logPath = WRITEPATH . 'logs/querys/';
    }

    /**
     * Logs an SQL query to a specific log file if enabled.
     * The log file and user identity can be based on the web server's authenticated user.
     *
     * @param string $sql The SQL query that was executed.
     * @param string $status The status of the execution ('success' or 'error').
     * @param float $executionTime The query execution time in seconds.
     * @param int $rowsAffected The number of rows affected or returned by the query.
     * @return bool Returns true on successful write, false otherwise.
     */
    public function logQuery(
        string $sql,
        string $status,
        float $executionTime,
        int $rowsAffected,
    ): bool {
        if (
            filter_var(env('QUERY_LOGGING'), FILTER_VALIDATE_BOOLEAN) !== true
        ) {
            return false;
        }

        $dbUser = session()->get('db_user');
        $host = session()->get('db_host');
        $webUser = null;
        $logIdentity = $dbUser;

        if (
            filter_var(
                env('QUERY_LOG_USE_WEB_USER'),
                FILTER_VALIDATE_BOOLEAN,
            ) === true
        ) {
            $webUserKey = env('QUERY_LOG_WEB_USER_KEY', 'REMOTE_USER');
            $webUser = request()->getServer($webUserKey);

            if (!empty($webUser)) {
                $logIdentity = $webUser;
            }
        }

        if (empty($logIdentity) || empty($host)) {
            return false;
        }

        $safeIdentity = preg_replace('/[^a-zA-Z0-9@._-]/', '_', $logIdentity);
        $safeHost = preg_replace('/[^a-zA-Z0-9._-]/', '_', $host);
        $logFilename = "log_{$safeIdentity}_on_{$safeHost}.log";
        $logFile = $this->logPath . $logFilename;

        if (!is_dir($this->logPath)) {
            mkdir($this->logPath, 0777, true);
        }

        $logEntry = [
            'timestamp' => date('c'),
            'ip_address' => request()->getIPAddress(),
            'web_user' => $webUser,
            'db_user' => $dbUser,
            'host' => $host,
            'database' => session()->get('db_database'),
            'status' => $status,
            'execution_time_s' => $executionTime,
            'rows_affected' => $rowsAffected,
            'sql' => trim($sql),
        ];

        $logLine = json_encode($logEntry) . PHP_EOL;

        if (file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX)) {
            return true;
        }

        return false;
    }
}
