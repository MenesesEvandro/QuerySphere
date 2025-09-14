SELECT
    a.TABLE_SCHEMA,
    a.TABLE_NAME,
    a.INDEX_NAME,
    GROUP_CONCAT (
        a.COLUMN_NAME
        ORDER BY
            a.SEQ_IN_INDEX
    ) AS COLUMNS
FROM
    information_schema.STATISTICS a
    JOIN (
        SELECT
            TABLE_SCHEMA,
            TABLE_NAME,
            GROUP_CONCAT (
                COLUMN_NAME
                ORDER BY
                    SEQ_IN_INDEX
            ) AS COLUMNS
        FROM
            information_schema.STATISTICS
        GROUP BY
            TABLE_SCHEMA,
            TABLE_NAME,
            INDEX_NAME
    ) b ON a.TABLE_SCHEMA = b.TABLE_SCHEMA
    AND a.TABLE_NAME = b.TABLE_NAME
GROUP BY
    a.TABLE_SCHEMA,
    a.TABLE_NAME,
    b.COLUMNS
HAVING
    COUNT(*) > 1
ORDER BY
    a.TABLE_SCHEMA,
    a.TABLE_NAME;
