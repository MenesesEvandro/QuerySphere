<?php

namespace App\Libraries;

use App\Libraries\ConnectionManager;
use mysqli;

/**
 * Manages a single, shared MySQL connection instance per request.
 */
class MySqlConnector
{
    private static $connection = null;

    public static function getConnection()
    {
        if (self::$connection === null) {
            $connManager = new ConnectionManager();
            $credentials = $connManager->getCredentials();

            if (!$credentials || $credentials['db_type'] !== 'mysql') {
                self::$connection = false;
                return false;
            }

            self::$connection = @new mysqli(
                $credentials['host'],
                $credentials['user'],
                $credentials['password'],
                $credentials['database'] ?: null,
                (int) $credentials['port'],
            );

            if (self::$connection->connect_error) {
                self::$connection = false;
            }
        }

        return self::$connection;
    }
}
