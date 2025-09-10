<?php

namespace App\Libraries;

use App\Libraries\ConnectionManager;

/**
 * Manages a single, shared SQL Server connection instance per request.
 */
class DatabaseConnector
{
    /**
     * @var resource|false|null The single, static connection instance.
     */
    private static $connection = null;

    /**
     * Gets the active database connection.
     *
     * If a connection has not yet been established for this request, it will
     * attempt to create one using the credentials stored in the session.
     *
     * @return resource|false The active sqlsrv connection resource, or false on failure.
     */
    public static function getConnection()
    {
        if (self::$connection === null) {
            $connManager = new ConnectionManager();
            $credentials = $connManager->getCredentials();

            if (!$credentials) {
                self::$connection = false;
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

            self::$connection = @sqlsrv_connect($serverName, $connectionInfo);
        }

        return self::$connection;
    }
}
