<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

/**
 * Manages shared SQL queries via a file-based API, scoped by server host.
 *
 * This controller provides a collaborative library of scripts for a team.
 * It does not use a database for storage; instead, it reads from and writes to
 * a host-specific JSON file located in the `writable/shared_queries/` directory.
 * It uses file locking (`flock`) to ensure data integrity.
 *
 * @package App\Controllers\Api
 */
class SharedQueries extends BaseController
{
    use ResponseTrait;

    /**
     * The full path to the host-specific JSON file used as storage.
     * @var string|null
     */
    private $filePath;

    /**
     * Constructor.
     *
     * Initializes the controller and dynamically sets the file path for the
     * shared queries JSON file based on the currently connected host.
     */
    public function __construct()
    {
        $session = session();
        $host = $session->get('db_host');

        if (!empty($host)) {
            // Sanitize the hostname to create a safe filename
            $safeFilename = preg_replace('/[^a-zA-Z0-9._-]/', '', $host);

            $this->filePath =
                WRITEPATH . 'shared_queries/' . $safeFilename . '.json';
        } else {
            $this->filePath = null;
        }
    }

    /**
     * Retrieves the list of all shared queries for the current host.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface The JSON response containing the list of queries.
     */
    public function index()
    {
        if (empty($this->filePath) || !file_exists($this->filePath)) {
            return $this->respond([]);
        }
        return $this->respond(
            json_decode(file_get_contents($this->filePath), true),
        );
    }

    /**
     * Creates and saves a new shared query for the current host.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface Returns a 201 Created response with the new query object on success.
     */
    public function create()
    {
        if (empty($this->filePath)) {
            return $this->fail(
                'Não é possível salvar a query. Conexão com o host não estabelecida.',
                400,
            );
        }

        $sql = $this->request->getPost('sql');
        $name = $this->request->getPost('name');
        $author = $this->request->getPost('author');

        if (empty($sql) || empty($name) || empty($author)) {
            return $this->fail(
                'Dados insuficientes para compartilhar a query.',
                400,
            );
        }

        $queries = file_exists($this->filePath)
            ? json_decode(file_get_contents($this->filePath), true)
            : [];

        $newQuery = [
            'id' => uniqid('q_', true),
            'name' => $name,
            'author' => $author,
            'sql' => $sql,
            'timestamp' => date('c'),
        ];

        array_unshift($queries, $newQuery);

        $fp = fopen($this->filePath, 'w');
        if (flock($fp, LOCK_EX)) {
            fwrite($fp, json_encode($queries, JSON_PRETTY_PRINT));
            flock($fp, LOCK_UN);
        }
        fclose($fp);

        return $this->respondCreated($newQuery);
    }

    /**
     * Deletes a specific shared query by its ID for the current host.
     *
     * @param string|null $id The unique ID of the query to delete.
     * @return \CodeIgniter\HTTP\ResponseInterface Returns a 200 OK response with the deleted ID on success.
     */
    public function delete($id = null)
    {
        if (
            empty($this->filePath) ||
            empty($id) ||
            !file_exists($this->filePath)
        ) {
            return $this->failNotFound('Query não encontrada.');
        }

        $queries = json_decode(file_get_contents($this->filePath), true);

        $queriesAfterDelete = array_filter(
            $queries,
            fn($q) => $q['id'] !== $id,
        );

        if (count($queries) === count($queriesAfterDelete)) {
            return $this->failNotFound('Query não encontrada para deletar.');
        }

        $fp = fopen($this->filePath, 'w');
        if (flock($fp, LOCK_EX)) {
            fwrite(
                $fp,
                json_encode(
                    array_values($queriesAfterDelete),
                    JSON_PRETTY_PRINT,
                ),
            );
            flock($fp, LOCK_UN);
        }
        fclose($fp);

        return $this->respondDeleted(['id' => $id]);
    }
}
