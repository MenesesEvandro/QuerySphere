SELECT
    s.database_name,
    s.backup_start_date,
    s.backup_finish_date,
    CAST((s.backup_size / 1024 / 1024) AS DECIMAL(10, 2)) AS backup_size_mb,
    CASE s.type
        WHEN 'D' THEN 'Database Full'
        WHEN 'I' THEN 'Database Differential'
        WHEN 'L' THEN 'Log'
        ELSE 'Other'
    END AS backup_type
FROM
    msdb.dbo.backupset s
    INNER JOIN (
        SELECT
            database_name,
            MAX(backup_start_date) AS last_backup_date
        FROM
            msdb.dbo.backupset
        GROUP BY
            database_name
    ) bs ON s.database_name = bs.database_name
    AND s.backup_start_date = bs.last_backup_date
WHERE
    s.database_name NOT IN ('tempdb')
ORDER BY
    s.database_name;
