DECLARE @objectName NVARCHAR(256) = 'schema.NomeDoObjeto';

SELECT
    referencing_schema_name AS Depending_Schema,
    referencing_entity_name AS Depending_Object,
    referencing_class_desc AS Depending_Object_Type
FROM
    sys.dm_sql_referencing_entities (@objectName, 'OBJECT');
