SELECT
    p.name AS LoginName,
    p.type_desc AS LoginType,
    p.create_date,
    p.modify_date
FROM
    sys.server_principals p
    JOIN sys.syslogins s ON p.sid = s.sid
WHERE
    p.principal_id > 1
    AND IS_SRVROLEMEMBER('sysadmin', p.name) = 1
ORDER BY
    p.name;
