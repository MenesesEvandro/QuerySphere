SELECT
    OBJECT_NAME(s.object_id) AS TableName,
    i.name AS IndexName,
    s.user_seeks,
    s.user_scans,
    s.user_lookups,
    s.user_updates
FROM
    sys.dm_db_index_usage_stats s
    INNER JOIN sys.indexes i ON s.object_id = i.object_id
    AND s.index_id = i.index_id
WHERE
    s.database_id = DB_ID()
    AND i.type_desc = 'NONCLUSTERED'
    AND s.user_seeks = 0
    AND s.user_scans = 0
    AND s.user_lookups = 0
ORDER BY
    s.user_updates DESC;
