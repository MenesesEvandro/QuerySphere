SELECT
    s.session_id,
    s.login_time,
    s.host_name,
    s.program_name,
    s.login_name,
    s.status,
    c.client_net_address,
    c.client_tcp_port
FROM
    sys.dm_exec_sessions s
    JOIN sys.dm_exec_connections c ON s.session_id = c.session_id
WHERE
    s.is_user_process = 1
ORDER BY
    s.login_time;
