<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

/**
 * Manages shared SQL queries via a file-based API.
 *
 * This controller provides a collaborative library of scripts for a team.
 * It does not use a database for storage; instead, it reads from and writes to
 * a single JSON file located in the `writable` directory. It uses file locking (`flock`)
 * to ensure data integrity during concurrent write operations.
 *
 * @package App\Controllers\Api
 */
class SharedQueries extends BaseController
{
    use ResponseTrait;

    /**
     * The full path to the JSON file used as storage.
     * @var string
     */
    private $filePath;

    /**
     * Constructor.
     *
     * Initializes the controller and sets the file path for the shared queries JSON file.
     */
    public function __construct()
    {
        $this->filePath = WRITEPATH . 'shared_queries.json';
    }

    /**
     * Retrieves the list of all shared queries.
     *
     * Reads the shared queries from the JSON file and returns them as a JSON array.
     * If the storage file does not exist, it returns an empty array.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface The JSON response containing the list of queries.
     */
    public function index()
    {
        if (!file_exists($this->filePath)) {
            return $this->respond([]);
        }
        return $this->respond(json_decode(file_get_contents($this->filePath), true));
    }

    /**
     * Creates and saves a new shared query.
     *
     * It receives the query's name, author, and SQL content via a POST request.
     * A new query object is created with a unique ID and timestamp, then prepended to the
     * existing array of queries. It uses an exclusive file lock (`flock`) to prevent
     * data corruption from concurrent writes.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface Returns a 201 Created response with the new query object on success.
     */
    public function create()
    {
        $sql = $this->request->getPost('sql');
        $name = $this->request->getPost('name');
        $author = $this->request->getPost('author');

        if (empty($sql) || empty($name) || empty($author)) {
            return $this->fail('Insufficient data to share the query.', 400);
        }

        $queries = file_exists($this->filePath) ? json_decode(file_get_contents($this->filePath), true) : [];

        $newQuery = [
            'id' => uniqid('q_', true), // Generate a unique ID
            'name' => $name,
            'author' => $author,
            'sql' => $sql,
            'timestamp' => date('c') // ISO 8601 format
        ];

        array_unshift($queries, $newQuery);

        // File locking logic to prevent race conditions
        $fp = fopen($this->filePath, 'w');
        if (flock($fp, LOCK_EX)) { // Acquire exclusive lock
            fwrite($fp, json_encode($queries, JSON_PRETTY_PRINT));
            flock($fp, LOCK_UN); // Release lock
        }
        fclose($fp);

        return $this->respondCreated($newQuery);
    }

    /**
     * Deletes a specific shared query by its ID.
     *
     * Reads all queries from the JSON file, filters out the query with the matching ID,
     * and writes the updated array back to the file. It uses an exclusive file lock (`flock`)
     * to ensure the write operation is safe from concurrency issues.
     *
     * @param string|null $id The unique ID of the query to delete, passed via URL segment.
     * @return \CodeIgniter\HTTP\ResponseInterface Returns a 200 OK response with the deleted ID on success, or a 404 Not Found if the ID does not exist.
     */
    public function delete($id = null)
    {
        if (empty($id) || !file_exists($this->filePath)) {
            return $this->failNotFound('Query not found.');
        }

        $queries = json_decode(file_get_contents($this->filePath), true);

        $queriesAfterDelete = array_filter($queries, fn ($q) => $q['id'] !== $id);

        if (count($queries) === count($queriesAfterDelete)) {
            return $this->failNotFound('Query not found to delete.');
        }

        $fp = fopen($this->filePath, 'w');
        if (flock($fp, LOCK_EX)) {
            fwrite($fp, json_encode(array_values($queriesAfterDelete), JSON_PRETTY_PRINT));
            flock($fp, LOCK_UN);
        }
        fclose($fp);

        return $this->respondDeleted(['id' => $id]);
    }
}