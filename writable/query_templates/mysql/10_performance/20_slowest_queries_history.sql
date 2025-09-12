-- Analisa o Performance Schema para encontrar as queries mais lentas (requer que o Performance Schema esteja habilitado)
SELECT
    `SCHEMA_NAME`,
    `DIGEST_TEXT`,
    `COUNT_STAR`,
    `SUM_TIMER_WAIT`
FROM
    `performance_schema`.`events_statements_summary_by_digest`
ORDER BY
    `SUM_TIMER_WAIT` DESC
LIMIT 10;