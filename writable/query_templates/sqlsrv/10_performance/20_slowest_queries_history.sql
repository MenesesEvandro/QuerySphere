SELECT
    TOP 10 total_worker_time / 1000 AS total_cpu_ms,
    execution_count,
    (total_worker_time / 1000) / execution_count AS avg_cpu_ms,
    SUBSTRING(
        st.text,
        (qs.statement_start_offset / 2) + 1,
        (
            (
                CASE qs.statement_end_offset
                    WHEN -1 THEN DATALENGTH(st.text)
                    ELSE qs.statement_end_offset
                END - qs.statement_start_offset
            ) / 2
        ) + 1
    ) AS statement_text
FROM
    sys.dm_exec_query_stats AS qs
    CROSS APPLY sys.dm_exec_sql_text (qs.sql_handle) AS st
ORDER BY
    total_worker_time DESC;
