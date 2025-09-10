SELECT
    db.name AS DBName,
    tl.request_session_id AS Blocker_SPID,
    es.login_name AS Blocker_Login,
    es.host_name AS Blocker_Host,
    (
        SELECT
            text
        FROM
            sys.dm_exec_sql_text (es.sql_handle)
    ) AS Blocker_SQL,
    tl.resource_type,
    tl.resource_description,
    tl.request_mode,
    tl.request_status
FROM
    sys.dm_tran_locks tl
    JOIN sys.databases db ON db.database_id = tl.resource_database_id
    JOIN sys.dm_exec_sessions es ON es.session_id = tl.request_session_id
WHERE
    tl.request_status = 'WAIT'
ORDER BY
    tl.request_session_id;
