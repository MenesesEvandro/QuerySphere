<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class SharedQueries extends BaseController
{
    use ResponseTrait;

    private $filePath;

    public function __construct()
    {
        $this->filePath = WRITEPATH . 'shared_queries.json';
    }

    public function index()
    {
        if (!file_exists($this->filePath)) {
            return $this->respond([]);
        }
        return $this->respond(json_decode(file_get_contents($this->filePath), true));
    }

    public function create()
    {
        $sql = $this->request->getPost('sql');
        $name = $this->request->getPost('name');
        $author = $this->request->getPost('author');

        if (empty($sql) || empty($name) || empty($author)) {
            return $this->fail('Dados insuficientes para compartilhar a query.', 400);
        }

        $queries = file_exists($this->filePath) ? json_decode(file_get_contents($this->filePath), true) : [];

        $newQuery = [
            'id' => uniqid('q_', true),
            'name' => $name,
            'author' => $author,
            'sql' => $sql,
            'timestamp' => date('c') 
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

    public function delete($id = null)
    {
        if (empty($id) || !file_exists($this->filePath)) {
            return $this->failNotFound('Query não encontrada.');
        }

        $queries = json_decode(file_get_contents($this->filePath), true);
        
        $queriesAfterDelete = array_filter($queries, fn($q) => $q['id'] !== $id);

        if (count($queries) === count($queriesAfterDelete)) {
            return $this->failNotFound('Query não encontrada para deletar.');
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