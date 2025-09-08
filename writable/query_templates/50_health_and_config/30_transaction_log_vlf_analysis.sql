-- Este script deve ser executado no contexto do banco de dados que você quer verificar.
-- Use o seletor de banco de dados do QuerySphere para trocar o contexto antes de rodar.

CREATE TABLE #VLFInfo (
    RecoveryUnitId int,
    FileID int,
    FileSize bigint,
    StartOffset bigint,
    FSeqNo bigint,
    Status tinyint,
    Parity tinyint,
    CreateLSN numeric(25, 0)
);

INSERT INTO
    #VLFInfo EXEC sp_executesql N'DBCC LOGINFO';

SELECT
    COUNT(*) AS VLF_Count
FROM
    #VLFInfo;

DROP TABLE #VLFInfo;

-- Recomendações:
-- < 50 VLFs: Excelente.
-- 50-250 VLFs: Bom.
-- 250-1000 VLFs: Atenção, pode precisar de manutenção.
-- > 1000 VLFs: Crítico, provavelmente impactando a performance.
