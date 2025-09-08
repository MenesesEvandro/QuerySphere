-- Lista logins com permissões críticas a nível de SERVIDOR
SELECT
    spr.name AS PrincipalName,
    p.permission_name,
    p.state_desc AS PermissionStatus
FROM
    sys.server_permissions p
    JOIN sys.server_principals spr ON p.grantee_principal_id = spr.principal_id
WHERE
    p.permission_name IN (
        'CONTROL SERVER',
        'IMPERSONATE ANY LOGIN',
        'ALTER ANY LOGIN',
        'ALTER ANY SERVER ROLE'
    )
    AND spr.type_desc NOT IN ('SERVER_ROLE')
    AND spr.name NOT LIKE '##%##'
ORDER BY
    spr.name;
