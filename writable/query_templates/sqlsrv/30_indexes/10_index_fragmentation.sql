SELECT
    OBJECT_NAME(ips.object_id) AS TableName,
    si.name AS IndexName,
    ips.index_type_desc,
    ips.avg_fragmentation_in_percent
FROM
    sys.dm_db_index_physical_stats (DB_ID(), NULL, NULL, NULL, 'SAMPLED') ips
    JOIN sys.indexes si ON ips.object_id = si.object_id
    AND ips.index_id = si.index_id
WHERE
    ips.avg_fragmentation_in_percent > 10.0
ORDER BY
    ips.avg_fragmentation_in_percent DESC;
