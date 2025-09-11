<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Interfaces\DatabaseModelInterface;
use App\Libraries\MySqlConnector;

/**
 * The data access layer for interacting with a MySQL database.
 */
class MySqlModel extends Model implements DatabaseModelInterface
{
    private $conn;

    public function __construct()
    {
        parent::__construct();
        $this->conn = MySqlConnector::getConnection();
    }

    private function hasConnection(): bool
    {
        return $this->conn !== null && $this->conn !== false;
    }

    public function tryConnect(array $credentials): array
    {
        $conn = @mysqli_connect(
            $credentials['host'],
            $credentials['user'],
            $credentials['password'],
            $credentials['database'] ?: null,
            (int) $credentials['port'],
        );

        if ($conn) {
            mysqli_close($conn);
            return [
                'status' => true,
                'message' => lang('App.connection_success'),
            ];
        }

        return [
            'status' => false,
            'message' =>
                lang('App.connection_failed') . ': ' . mysqli_connect_error(),
        ];
    }

    // --- Métodos da Interface (Implementação Pendente) ---

    public function getDatabases(): array
    {
        return [];
    }
    public function getTablesAndViews(string $database): array
    {
        return [];
    }
    public function getColumns(string $database, string $table): array
    {
        return [];
    }
    public function getProceduresAndFunctions(string $database): array
    {
        return [];
    }
    public function getRoutineParameters(
        string $database,
        string $routineSchema,
        string $routineName,
    ): array {
        return [];
    }
    public function getAutocompletionSchema(): array
    {
        return [];
    }
    public function executeQuery(
        string $sql,
        int $page = 1,
        int $pageSize = 1000,
        bool $disablePagination = false,
    ): array {
        return ['status' => 'error', 'message' => 'Not implemented'];
    }
    public function getExecutionPlan(string $sql): array
    {
        return ['status' => 'error', 'message' => 'Not implemented'];
    }
    public function searchObjects(string $database, string $searchTerm): array
    {
        return [];
    }
    public function getObjectDefinition(
        string $database,
        string $schema,
        string $objectName,
    ): ?string {
        return null;
    }
    public function getAgentJobs(): array
    {
        return [];
    }
    public function startAgentJob(string $jobName): array
    {
        return ['status' => 'error', 'message' => 'Not applicable for MySQL'];
    }
    public function stopAgentJob(string $jobName): array
    {
        return ['status' => 'error', 'message' => 'Not applicable for MySQL'];
    }
    public function getAgentJobHistory(string $jobName): array
    {
        return [];
    }
}
