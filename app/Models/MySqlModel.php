<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Interfaces\DatabaseModelInterface;
use App\Libraries\MySqlConnector;

class MySqlModel extends Model implements DatabaseModelInterface
{
    // ... (início do ficheiro e métodos anteriores) ...

    public function getObjectDefinition(
        string $database,
        string $schema,
        string $objectName,
        string $type,
    ): ?string {
        if (!$this->hasConnection()) {
            return null;
        }

        $type = strtoupper($type);
        $query = '';

        switch ($type) {
            case 'TABLE':
            case 'BASE TABLE':
                $query = "SHOW CREATE TABLE `{$database}`.`{$objectName}`";
                break;
            case 'VIEW':
                $query = "SHOW CREATE VIEW `{$database}`.`{$objectName}`";
                break;
            case 'PROCEDURE':
                $query = "SHOW CREATE PROCEDURE `{$database}`.`{$objectName}`";
                break;
            case 'FUNCTION':
                $query = "SHOW CREATE FUNCTION `{$database}`.`{$objectName}`";
                break;
            default:
                return lang('App.feedback.db_object_type_not_supported', [
                    $type,
                ]);
        }

        $result = $this->conn->query($query);
        if ($result && ($row = $result->fetch_assoc())) {
            $key = 'Create Table';
            if (isset($row['Create View'])) {
                $key = 'Create View';
            }
            if (isset($row['Create Procedure'])) {
                $key = 'Create Procedure';
            }
            if (isset($row['Create Function'])) {
                $key = 'Create Function';
            }

            return $row[$key];
        }
        return lang('App.feedback.db_could_not_retrieve_definition', [
            $objectName,
        ]);
    }

    public function getEvents(): array
    {
        if (!$this->hasConnection()) {
            return [];
        }
        $sql = 'SHOW EVENTS;';
        $result = $this->conn->query($sql);
        $events = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $events[] = [
                    'name' => $row['Name'],
                    'status' => $row['Status'],
                    'next_execution' => $row['Execute at'],
                ];
            }
            $result->free();
        }
        return $events;
    }

    public function toggleEventStatus(string $eventName, string $status): array
    {
        if (
            !$this->hasConnection() ||
            !in_array(strtoupper($status), ['ENABLE', 'DISABLE'])
        ) {
            return [
                'status' => 'error',
                'message' => lang('App.feedback.db_invalid_operation'),
            ];
        }

        $safeEventName = '`' . str_replace('`', '``', $eventName) . '`';

        $sql = "ALTER EVENT {$safeEventName} {$status};";
        if ($this->conn->query($sql)) {
            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => $this->conn->error];
    }

    public function getEventDefinition(string $eventName): ?string
    {
        if (!$this->hasConnection()) {
            return null;
        }

        $safeEventName = $this->conn->real_escape_string($eventName);
        $sql = "SHOW CREATE EVENT `{$safeEventName}`";

        $result = $this->conn->query($sql);
        if ($result && ($row = $result->fetch_assoc())) {
            return $row['Create Event'];
        }
        return lang('App.feedback.db_event_not_found', [$eventName]);
    }

    // --- Funções não aplicáveis ao MySQL ---
    public function getAgentJobs(): array
    {
        return [];
    }
    public function startAgentJob(string $jobName): array
    {
        return [
            'status' => 'error',
            'message' => lang('App.feedback.db_unsupported_feature'),
        ];
    }
    public function stopAgentJob(string $jobName): array
    {
        return [
            'status' => 'error',
            'message' => lang('App.feedback.db_unsupported_feature'),
        ];
    }
    public function getAgentJobHistory(string $jobName): array
    {
        return [];
    }
}
