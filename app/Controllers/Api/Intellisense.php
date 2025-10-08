<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Factories\DatabaseModelFactory;

/**
 * @class Intellisense
 * @package App\Controllers\Api
 *
 * Controller responsible for providing intelligent code completion data (IntelliSense) for the SQL editor.
 * It fetches the schema for ALL databases on the connected server, combines it with dialect-specific
 * SQL keywords and functions, and returns a comprehensive, cached JSON response for the frontend.
 */
class Intellisense extends BaseController
{
    use ResponseTrait;

    private function getDialectKeywords(string $dbType): array
    {
        $common = [
            'SELECT', 'FROM', 'WHERE', 'JOIN', 'LEFT JOIN', 'RIGHT JOIN', 'INNER JOIN', 'ON',
            'GROUP BY', 'HAVING', 'ORDER BY', 'ASC', 'DESC', 'INSERT INTO', 'VALUES',
            'UPDATE', 'SET', 'DELETE FROM', 'CREATE TABLE', 'ALTER TABLE', 'DROP TABLE',
            'AND', 'OR', 'NOT', 'IN', 'BETWEEN', 'LIKE', 'IS NULL', 'IS NOT NULL', 'AS', 'CASE', 'WHEN', 'THEN', 'ELSE', 'END'
        ];

        if ($dbType === 'mysql') {
            return array_merge($common, ['LIMIT', 'GROUP_CONCAT']);
        }

        // mssql
        return array_merge($common, ['TOP', 'ROW_NUMBER', 'OVER', 'PARTITION BY']);
    }

    private function getDialectFunctions(string $dbType): array
    {
        $common = ['COUNT()', 'SUM()', 'AVG()', 'MIN()', 'MAX()', 'CONVERT()', 'CAST()', 'COALESCE()'];

        if ($dbType === 'mysql') {
            return array_merge($common, ['NOW()', 'CONCAT()', 'DATE_FORMAT()']);
        }

        // mssql
        return array_merge($common, ['GETDATE()', 'ISNULL()']);
    }

    /**
     * Main endpoint to get all data required for SQL IntelliSense.
     *
     * This method builds a "global schema" by iterating through all databases on the connected server.
     * The schema for the entire server is cached to ensure high performance.
     * The final payload includes all objects (with fully qualified names), schemas, databases,
     * and dialect-specific keywords and functions.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface The JSON response containing the full autocompletion schema.
     */
    public function index()
    {
        $dbType = session()->get('db_type');
        $host = session()->get('db_host');

        if (empty($dbType) || empty($host)) {
            return $this->fail('Database connection not established.', 400);
        }

        $safeHost = preg_replace('/[^a-zA-Z0-9._-]/', '', $host);
        $cacheKey = 'intellisense_global_' . $dbType . '_' . $safeHost;

        if ($this->request->getGet('clearCache')) {
            cache()->delete($cacheKey);
        }

        if (!$globalSchema = cache($cacheKey)) {
            $model = DatabaseModelFactory::create();
            $databases = $model->getDatabases();

            $globalSchema = ['objects' => [], 'schemas' => [], 'databases' => []];

            foreach ($databases as $db) {
                $dbName = $db['name'];
                $globalSchema['databases'][] = $dbName;

                $dbSchema = $model->getRichSchema($dbName);

                foreach ($dbSchema['objects'] as $objectName => $details) {
                    // Create fully qualified names for all objects
                    // For MySQL: database.table
                    // For SQL Server: database.schema.table
                    $qualifiedObjectName = ($dbType === 'mysql')
                        ? $dbName . '.' . $objectName
                        : $dbName . '.' . $objectName;

                    $globalSchema['objects'][$qualifiedObjectName] = $details;
                }

                foreach ($dbSchema['schemas'] as $schemaName) {
                    $qualifiedSchemaName = $dbName . '.' . $schemaName;
                    if (!in_array($qualifiedSchemaName, $globalSchema['schemas'])) {
                        $globalSchema['schemas'][] = $qualifiedSchemaName;
                    }
                }
            }

            cache()->save($cacheKey, $globalSchema, 3600); // Cache for 1 hour
        }

        $globalSchema['keywords'] = $this->getDialectKeywords($dbType);
        $globalSchema['functions'] = $this->getDialectFunctions($dbType);

        return $this->respond($globalSchema);
    }
}
