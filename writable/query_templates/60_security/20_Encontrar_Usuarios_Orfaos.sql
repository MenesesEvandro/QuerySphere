-- Este script deve ser executado no contexto do banco de dados que você quer verificar.
-- Use o seletor de banco de dados do QuerySphere para trocar o contexto antes de rodar.

SELECT
    dp.name AS DatabaseUser,
    dp.sid AS UserSID
FROM
    sys.database_principals dp
    LEFT JOIN sys.server_principals sp ON dp.sid = sp.sid
WHERE
    sp.sid IS NULL
    AND dp.authentication_type_desc = 'INSTANCE'
    AND dp.principal_id > 4;
