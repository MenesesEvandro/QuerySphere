<?php 

namespace App\Libraries;

/**
 * Gerenciador de credenciais de conexão com o banco de dados.
 *
 * Armazena e recupera dados de conexão na sessão do usuário.
 */
class ConnectionManager
{
    /**
     * Armazena as credenciais de conexão na sessão do usuário.
     *
     * @param array $credentials Credenciais de conexão (host, database, user, password, port).
     * @return void
     */
    public function storeCredentials(array $credentials): void
    {
        $sessionData = [
            'db_host'     => $credentials['host'],
            'db_database' => $credentials['database'],
            'db_user'     => $credentials['user'],
            'db_password' => base64_encode($credentials['password']),
            'db_port'     => $credentials['port'],
            'is_connected'=> true
        ];
        session()->set($sessionData);
    }

    /**
     * Recupera as credenciais de conexão armazenadas na sessão.
     *
     * @return array|null Retorna as credenciais ou null se não estiver conectado.
     */
    public function getCredentials(): ?array
    {
        if (! session()->get('is_connected')) {
            return null;
        }

        return [
            'host'     => session()->get('db_host'),
            'database' => session()->get('db_database'),
            'user'     => session()->get('db_user'),
            'password' => base64_decode(session()->get('db_password')),
            'port'     => session()->get('db_port')
        ];
    }
}