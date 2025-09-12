USE msdb;

GO
SELECT
    j.name AS JobName,
    h.step_name AS StepName,
    h.run_date,
    h.run_time,
    h.run_duration,
    h.message
FROM
    dbo.sysjobs j
    JOIN dbo.sysjobhistory h ON j.job_id = h.job_id
WHERE
    h.run_status = 0
    AND h.run_date >= CONVERT(VARCHAR(8), GETDATE() - 1, 112)
ORDER BY
    h.run_date DESC,
    h.run_time DESC;
