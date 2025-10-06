<?php

namespace App\Libraries;

/**
 * A simple audit logger that writes critical queries to a dedicated JSON log file.
 * Logging is controlled by the AUDIT_LOGGING environment variable.
 */
class AuditLogger
{
    private $logPath;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->logPath = WRITEPATH . 'logs/audit/';
    }

    /**
     * Logs a critical SQL query to a specific audit log file.
     *
     * @param string $sql The critical SQL query that was attempted.
     * @return bool Returns true on successful write, false otherwise.
     */
    public function logQuery(string $sql): bool
    {
        if (filter_var(env('AUDIT_LOGGING'), FILTER_VALIDATE_BOOLEAN) !== true) {
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
        $logFilename = "audit_{$safeIdentity}_on_{$safeHost}.log";
        $logFile = $this->logPath . $logFilename;

        if (!is_dir($this->logPath)) {
            mkdir($this->logPath, 0777, true);
        }

        $logEntry = [
            'timestamp'    => date('c'),
            'ip_address'   => request()->getIPAddress(),
            'web_user'     => $webUser,
            'db_user'      => $dbUser,
            'host'         => $host,
            'database'     => session()->get('db_database'),
            'critical_sql' => trim($sql),
        ];

        $logLine = json_encode($logEntry) . PHP_EOL;

        return (bool) file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
    }
}
