SELECT
    r.session_id,
    r.status,
    r.start_time,
    r.command,
    DB_NAME(r.database_id) AS database_name,
    s.login_name,
    s.host_name,
    s.program_name,
    r.wait_type,
    r.wait_time,
    r.total_elapsed_time / 1000.0 AS total_elapsed_time_sec,
    SUBSTRING(
        qt.text,
        (r.statement_start_offset / 2) + 1,
        (
            (
                CASE r.statement_end_offset
                    WHEN -1 THEN DATALENGTH(qt.text)
                    ELSE r.statement_end_offset
                END - r.statement_start_offset
            ) / 2
        ) + 1
    ) AS sql_text
FROM
    sys.dm_exec_requests AS r
    JOIN sys.dm_exec_sessions AS s ON r.session_id = s.session_id
    CROSS APPLY sys.dm_exec_sql_text (r.sql_handle) AS qt
WHERE
    r.session_id <> @@SPID
ORDER BY
    r.total_elapsed_time DESC;
