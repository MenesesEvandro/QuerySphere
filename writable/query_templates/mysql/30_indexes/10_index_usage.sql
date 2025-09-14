SELECT
    t.TABLE_SCHEMA,
    t.TABLE_NAME,
    s.INDEX_NAME,
    s.ROWS_READ
FROM
    information_schema.TABLES t
    JOIN performance_schema.table_io_waits_summary_by_index_usage s ON t.OBJECT_SCHEMA = s.OBJECT_SCHEMA
    AND t.OBJECT_NAME = s.OBJECT_NAME
WHERE
    s.INDEX_NAME IS NOT NULL
    AND t.TABLE_SCHEMA NOT IN ('mysql', 'performance_schema', 'sys')
ORDER BY
    s.ROWS_READ ASC;
