-- Lista as maiores tabelas da base de dados atual, ordenadas por tamanho em MB
SELECT
    table_name AS `TableName`,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS `TotalSpaceMB`
FROM
    information_schema.TABLES
WHERE
    table_schema = DATABASE()
ORDER BY
    (data_length + index_length) DESC
LIMIT 20;