SELECT
    name,
    database_id,
    recovery_model_desc,
    compatibility_level,
    collation_name,
    is_auto_shrink_on,
    is_auto_close_on,
    is_read_only
FROM
    sys.databases
ORDER BY
    name;
