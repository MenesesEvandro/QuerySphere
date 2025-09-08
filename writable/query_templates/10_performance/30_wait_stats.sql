WITH
    Waits AS (
        SELECT
            wait_type,
            wait_time_ms / 1000.0 AS WaitS,
            (wait_time_ms - signal_wait_time_ms) / 1000.0 AS ResourceS,
            signal_wait_time_ms / 1000.0 AS SignalS,
            waiting_tasks_count AS WaitCount,
            100.0 * wait_time_ms / SUM(wait_time_ms) OVER () AS Percentage
            sys.dm_os_wait_stats
        WHERE
            wait_type NOT IN (
                'BROKER_EVENTHANDLER',
                'BROKER_RECEIVE_WAITFOR',
                'BROKER_TASK_STOP',
                'BROKER_TO_FLUSH',∏
                'BROKER_TRANSMITTER',
                'CHECKPOINT_QUEUE',
                'CHKPT',
                'CLR_AUTO_EVENT',
                'CLR_MANUAL_EVENT',
                'CLR_SEMAPHORE',
                'DBMIRROR_DBM_EVENT',
                'DBMIRROR_EVENTS_QUEUE',
                'DBMIRROR_WORKER_QUEUE',
                'DBMIRRORING_CMD',
                'DIRTY_PAGE_POLL',
                'DISPATCHER_QUEUE_SEMAPHORE',
                'EXECSYNC',
                'FSAGENT',
                'FT_IFTS_SCHEDULER_IDLE_WAIT',
                'FT_IFTSHC_MUTEX',
                'HADR_CLUSAPI_CALL',
                'HADR_FILESTREAM_IOMGR_IOCOMPLETION',
                'HADR_LOGCAPTURE_WAIT',
                'HADR_NOTIFY_SYNC',
                'HADR_TIMER_TASK',
                'HADR_WORK_QUEUE',
                'KSOURCE_WAKEUP',
                'LAZYWRITER_SLEEP',
                'LOGMGR_QUEUE',
                'ONDEMAND_TASK_QUEUE',
                'PWAIT_ALL_COMPONENTS_INITIALIZED',
                'QDS_PERSIST_TASK_MAIN_LOOP_SLEEP',
                'QDS_CLEANUP_STALE_QUERIES_TASK_MAIN_LOOP_SLEEP',
                'REQUEST_FOR_DEADLOCK_SEARCH',
                'RESOURCE_QUEUE',
                'SERVER_IDLE_CHECK',
                'SLEEP_BPOOL_FLUSH',
                'SLEEP_DBSTARTUP',
                'SLEEP_DCOMSTARTUP',
                'SLEEP_MASTERDBREADY',
                'SLEEP_MASTERMDREADY',
                'SLEEP_MASTERUPGRADED',
                'SLEEP_MSSEARCH',
                'SLEEP_SYSTEMTASK',
                'SLEEP_TASK',
                'SLEEP_TEMPDBSTARTUP',
                'SNI_HTTP_ACCEPT',
                'SP_SERVER_DIAGNOSTICS_SLEEP',
                'SQLTRACE_BUFFER_FLUSH',
                'SQLTRACE_INCREMENTAL_FLUSH_SLEEP',
                'SQLTRACE_WAIT_ENTRIES',
                'WAIT_FOR_RESULTS',
                'WAITFOR',
                'WAITFOR_TASKSHUTDOWN',
                'WAIT_XTP_HOST_WAIT',
                'WAIT_XTP_OFFLINE_CKPT_NEW_LOG',
                'WAIT_XTP_CKPT_CLOSE',
                'XE_DISPATCHER_WAIT',
                'XE_TIMER_EVENT'
            )
            AND waiting_tasks_count > 0
    )
SELECT
    W.wait_type,
    CAST(W.WaitS AS DECIMAL(12, 2)) AS Wait_S,
    CAST(W.ResourceS AS DECIMAL(12, 2)) AS Resource_S,
    CAST(W.SignalS AS DECIMAL(12, 2)) AS Signal_S,
    W.WaitCount,
    CAST(W.Percentage AS DECIMAL(5, 2)) AS Pct
FROM
    Waits AS W
ORDER BY
    W.WaitS DESC;
