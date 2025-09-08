-- Este script deve ser executado no contexto do banco de dados que você quer verificar.
-- Use o seletor de banco de dados do QuerySphere para trocar o contexto antes de rodar.
SELECT
    dpr.name AS PrincipalName,
    dpr.type_desc AS PrincipalType,
    p.permission_name,
    p.state_desc AS PermissionStatus
FROM
    sys.database_permissions p
    JOIN sys.database_principals dpr ON p.grantee_principal_id = dpr.principal_id
WHERE
    p.permission_name IN (
        'CONTROL',
        'IMPERSONATE',
        'ALTER ANY USER',
        'ALTER ANY ROLE'
    )
    AND dpr.name NOT IN ('dbo', 'public')
ORDER BY
    dpr.name;
