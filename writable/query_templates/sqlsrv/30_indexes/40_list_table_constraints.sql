DECLARE @tableName NVARCHAR(128) = 'NOME_DA_SUA_TABELA';

SELECT
    con.name AS ConstraintName,
    col.name AS ColumnName,
    con.type_desc AS ConstraintType
FROM
    sys.objects con
    JOIN sys.all_columns col ON con.parent_object_id = col.object_id
    JOIN sys.default_constraints def ON con.object_id = def.object_id
    AND col.column_id = def.parent_column_id
WHERE
    con.parent_object_id = OBJECT_ID(@tableName)
UNION
SELECT
    kc.name,
    c.name,
    kc.type_desc
FROM
    sys.key_constraints kc
    JOIN sys.index_columns ic ON kc.parent_object_id = ic.object_id
    AND kc.unique_index_id = ic.index_id
    JOIN sys.columns c ON ic.object_id = c.object_id
    AND ic.column_id = c.column_id
WHERE
    kc.parent_object_id = OBJECT_ID(@tableName)
ORDER BY
    ConstraintType,
    ConstraintName,
    ColumnName;
