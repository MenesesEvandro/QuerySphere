SELECT
    DB_NAME(database_id) AS DatabaseName,
    type_desc AS FileType,
    name AS LogicalName,
    physical_name AS PhysicalName,
    (size * 8.0 / 1024) AS SizeMB,
    (FILEPROPERTY(name, 'SpaceUsed') * 8.0 / 1024) AS SpaceUsedMB,
    (size * 8.0 / 1024) - (FILEPROPERTY(name, 'SpaceUsed') * 8.0 / 1024) AS FreeSpaceMB
FROM
    sys.master_files
ORDER BY
    DatabaseName,
    FileType;
