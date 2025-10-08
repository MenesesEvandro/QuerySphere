<?php

namespace App\Interfaces;

interface DatabaseModelInterface
{
    public function tryConnect(array $credentials): array;
    public function getDatabases(): array;
    public function getTablesAndViews(string $database): array;
    public function getColumns(string $database, string $table): array;
    public function getProceduresAndFunctions(string $database): array;
    public function getRoutineParameters(
        string $database,
        string $routineSchema,
        string $routineName,
    ): array;
    public function getAutocompletionSchema(): array;
    public function executeQuery(
        string $sql,
        int $page = 1,
        int $pageSize = 1000,
        bool $disablePagination = false,
    ): array;
    public function getExecutionPlan(string $sql): array;
    public function searchObjects(string $database, string $searchTerm): array;
    public function getObjectDefinition(
        string $database,
        string $schema,
        string $objectName,
        string $type,
    ): ?string;

    public function getPrimaryKey(string $database, string $table): ?string;
    public function updateRecord(
        string $database,
        string $schema,
        string $table,
        string $primaryKey,
        $primaryKeyValue,
        array $data,
    ): array;

    public function getTableStructure(
        string $database,
        string $schema,
        string $table,
    ): array;
    public function createTable(
        string $database,
        string $schema,
        string $table,
        array $columns,
        array $primaryKeys,
    ): array;
    public function addColumn(
        string $database,
        string $schema,
        string $table,
        array $column,
    ): array;

    public function getIndexes(
        string $database,
        string $schema,
        string $table,
    ): array;

    public function createIndex(string $database, string $schema, string $table, string $indexName, array $columns, bool $isUnique): array;
    public function dropIndex(string $database, string $schema, string $table, string $indexName): array;
    public function getAllTables(string $database): array;
    public function getForeignKeys(string $database, string $schema, string $table): array;
    public function createForeignKey(string $database, string $schema, string $table, string $fkName, array $columns, string $refTable, array $refColumns): array;
    public function dropForeignKey(string $database, string $schema, string $table, string $fkName): array;
    public function checkConnection(): bool;
    public function getRichSchema(string $database): array;
}
