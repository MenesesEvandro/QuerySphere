SELECT
    ja.job_id,
    j.name AS job_name,
    ja.start_execution_date,
    ISNULL(last_executed_step_id, 0) + 1 AS current_executing_step_id,
    js.step_name
FROM
    msdb.dbo.sysjobactivity ja
    LEFT JOIN msdb.dbo.sysjobs j ON ja.job_id = j.job_id
    LEFT JOIN msdb.dbo.sysjobsteps js ON ja.job_id = js.job_id
    AND ISNULL(last_executed_step_id, 0) + 1 = js.step_id
WHERE
    ja.session_id = (
        SELECT
            MAX(session_id)
        FROM
            msdb.dbo.syssessions
    )
    AND start_execution_date is not null
    AND stop_execution_date is null;
