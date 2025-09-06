<?php

namespace App\Libraries;

/**
 * Manages database connection credentials within the user's session.
 *
 * This library provides a centralized way to securely store connection details
 * after a successful login and retrieve them for subsequent database operations.
 * The password is obfuscated using base64 encoding before being stored.
 *
 * @package App\Libraries
 */
class ConnectionManager
{
    /**
     * Stores the database connection credentials in the user's session.
     *
     * The password is base64 encoded for simple obfuscation before being saved.
     * It also sets an 'is_connected' flag in the session to indicate an active connection.
     *
     * @param array $credentials An associative array containing connection details: 'host', 'database', 'user', 'password', 'port'.
     * @return void
     */
    public function storeCredentials(array $credentials): void
    {
        $sessionData = [
            'db_host' => $credentials['host'],
            'db_database' => $credentials['database'],
            'db_user' => $credentials['user'],
            'db_password' => base64_encode($credentials['password']), // Obfuscate password
            'db_port' => $credentials['port'],
            'is_connected' => true,
        ];

        session()->set($sessionData);
    }

    /**
     * Retrieves the stored connection credentials from the session.
     *
     * It first checks for the 'is_connected' session flag. If the user is not connected,
     * it returns null. Otherwise, it returns the credentials array with the
     * password base64 decoded.
     *
     * @return array|null An associative array of credentials if the user is connected, otherwise null.
     */
    public function getCredentials(): ?array
    {
        if (!session()->get('is_connected')) {
            return null;
        }

        return [
            'host' => session()->get('db_host'),
            'database' => session()->get('db_database'),
            'user' => session()->get('db_user'),
            'password' => base64_decode(session()->get('db_password')),
            'port' => session()->get('db_port'),
        ];
    }
}
