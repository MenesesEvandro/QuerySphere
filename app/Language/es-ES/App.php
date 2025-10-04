<?php

return [
    //--------------------------------------------------------------------
    // Elementos Generales de la Interfaz de Usuario
    //--------------------------------------------------------------------
    'general' => [
        'submit' => 'Enviar',
        'close' => 'Cerrar',
        'details' => 'Detalles',
        'loading' => 'Cargando...',
        'page' => 'Página',
        'of' => 'de',
        'records' => 'registros',
        'result' => 'resultado',
        'actions' => 'Acciones',
        'delete' => 'Eliminar',
        'edit' => 'Editar',
        'previous' => 'Anterior',
        'next' => 'Siguiente',
        'language' => 'Idioma',
        'theme' => 'Tema',
        'toggleTheme' => 'Cambiar Tema',
        'select' => 'Seleccione ...',
        'status' => 'Estado',
        'unknown' => 'Desconocido',
        'enable' => 'Habilitar',
        'enabled' => 'Habilitado',
        'disable' => 'Deshabilitar',
        'disabled' => 'Deshabilitado',
        'not_found' => 'No encontrado',
        'running' => 'En ejecución',
        'success' => 'Éxito',
        'failed' => 'Falló',
        'cancel' => 'Cancelar',
        'canceled' => 'Cancelado',
        'retry' => 'Reintentar',
        'https_warning' =>
            'Advertencia: Esta página debe ser accedida a través de HTTPS para garantizar la seguridad.',
        'crypto_warning' =>
            'Error: Este navegador no soporta la API Web Crypto. Por favor, usa un navegador moderno.',
        'confirmation' => 'Confirmación',
    ],

    //--------------------------------------------------------------------
    // Pantalla de Conexión y Gestión
    //--------------------------------------------------------------------
    'connection' => [
        'title' => 'Conexión',
        'screenTitle' => 'Conéctate a tu SQL Server',
        'connect' => 'Conectar',
        'connecting' => 'Conectando...',
        'disconnect' => 'Desconectar',
        'connectedTo' => 'Conectado a',
        'changing' => 'Cambiando ...',
        'db_type' => 'Tipo de Base de Datos',
        'sql_server' => 'SQL Server',
        'mysql' => 'MySQL',
        'postgresql' => 'PostgreSQL (próximamente)',
        'host' => 'Host (Dirección del Servidor)',
        'port' => 'Puerto',
        'database' => 'Nombre de la Base de Datos (Opcional)',
        'user' => 'Usuario',
        'password' => 'Contraseña',
        'show_password' => 'Mostrar Contraseña',
        'hide_password' => 'Ocultar Contraseña',
        'rememberConnection' => 'Guardar datos de conexión',
        'trust_cert' =>
            'Confiar en el certificado del servidor (para localhost/autofirmado)',
        'new_connection' => '-- Nueva Conexión --',
        'manage_connections' => 'Gestionar Conexiones',
        'select_connection' =>
            'Selecciona una conexión existente o crea una nueva.',
        'saved_connection' => 'Conexión Guardada',
        'saved_connections' => 'Conexiones Guardadas',
        'no_saved_connections' => 'No hay conexiones guardadas para gestionar.',
        'connection_deleted' => 'Conexión eliminada.',
        'confirm_delete_connection' =>
            '¿Estás seguro de que deseas eliminar la conexión {0}?',
        'prompt_connection_name' => 'Ingresa un nombre para esta nueva conexión:',
        'confirm_clear_connections' =>
            '¿Estás seguro de que deseas eliminar todas las conexiones guardadas?',
        'clear_saved_connections' => 'Eliminar Conexiones Guardadas',
        'connections_cleared' => 'Todas las conexiones guardadas han sido eliminadas.',
        'clear_all_connections' => 'Eliminar todas las conexiones',
    ],

    //--------------------------------------------------------------------
    // Contraseña Maestra
    //--------------------------------------------------------------------
    'master_password' => [
        'title' => 'Contraseña Maestra',
        'hint' =>
            'Debe tener al menos 8 caracteres con letras, números y símbolos.',
        'enter' => 'Ingresa la Contraseña Maestra',
        'change' => 'Cambiar Contraseña Maestra',
        'change_info' =>
            'Puedes cambiar tu Contraseña Maestra aquí. Será necesario ingresar la contraseña actual para verificación.',
        'current' => 'Contraseña Maestra Actual',
        'new' => 'Nueva Contraseña Maestra',
        'confirm_new' => 'Confirmar Nueva Contraseña Maestra',
        'no_match' => 'La Nueva Contraseña Maestra y la confirmación no coinciden.',
        'changed_success' => 'Contraseña Maestra cambiada con éxito.',
        'incorrect' => 'La Contraseña Maestra Actual es incorrecta.',
        'invalid' =>
            'Contraseña Maestra inválida. Debe tener al menos 8 caracteres con letras, números y símbolos.',
        'new_prompt' =>
            'CREA una Contraseña Maestra para proteger tus contraseñas guardadas.\nEsta contraseña NO será guardada y se te solicitará cada vez que necesites cargar una contraseña.',
        'ask_prompt' =>
            'Por favor, ingresa tu Contraseña Maestra para cargar la contraseña.',
        'error_decrypting' =>
            'Error al descifrar la contraseña. ¿Contraseña Maestra incorrecta?',
        'no_saved_passwords' => 'No hay contraseñas guardadas.',
        'saved_passwords' => 'Contraseñas Guardadas',
    ],

    //--------------------------------------------------------------------
    // Área de Trabajo Principal (Editor, Resultados, Pestañas)
    //--------------------------------------------------------------------
    'workspace' => [
        'execute' => 'Ejecutar',
        'executing' => 'Ejecutando...',
        'explain' => 'Plan de Ejecución',
        'formatSQL' => 'Formatear SQL',
        'exportCSV' => 'Exportar CSV',
        'exportJSON' => 'Exportar JSON',
        'objects' => 'Objetos',
        'history' => 'Historial',
        'saved' => 'Guardados',
        'shared' => 'Compartidos',
        'results' => 'Resultados',
        'messages' => 'Mensajes',
        'queryResultsPlaceholder' =>
            'Ejecuta una consulta para ver los resultados.',
        'search_placeholder' => 'Buscar en {0}...',
        'templates' => 'Plantillas',
        'save_changes' => 'Guardar Cambios',
        'saving_changes' => 'Guardando...',
        'error_saving' => 'Error al guardar los cambios.',
        'no_primary_key' =>
            'Esta tabla no puede ser editada porque no tiene una clave primaria.',
        'multiple_tables_not_supported' =>
            'La edición no es compatible con resultados de múltiples tablas.',
        'no_table_detected' =>
            'No se pudo detectar una tabla en la consulta. La edición está deshabilitada.',
        'confirm_discard_changes' =>
            'Tienes cambios no guardados. ¿Estás seguro de que deseas descartarlos?',
        'maxEditor' => 'Maximizar editor',
        'no_tab_history' => 'No hay historial para esta pestaña.',
    ],

    //--------------------------------------------------------------------
    // Navegador de Objetos
    //--------------------------------------------------------------------
    'objects_browser' => [
        'search' => 'Buscar objetos',
        'tables' => 'Tablas',
        'views' => 'Vistas',
        'stored_procedures' => 'Procedimientos Almacenados',
        'functions' => 'Funciones',
        'no_parameters' => 'Sin parámetros',
        'loading_definition_for' => 'Cargando definición para {0}...',
        'script_execute' => 'Script como Ejecutar',
    ],

    //--------------------------------------------------------------------
    // Gestión de Scripts (Guardar, Compartir)
    //--------------------------------------------------------------------
    'scripts' => [
        'save' => 'Guardar Script',
        'share' => 'Compartir',
        'confirm_delete' => '¿Estás seguro de que deseas eliminar este script?',
        'prompt_name' => 'Ingresa el nombre del script:',
        'default_name' => 'Mi Script',
        'empty_alert' => 'No hay script para guardar.',
        'empty_shared_alert' => 'No hay script para compartir.',
        'prompt_shared_name' => 'Ingresa un nombre para esta consulta compartida:',
        'shared_default_name' => 'Script Compartido',
        'prompt_author' => 'Tu nombre:',
        'author_default' => 'Usuario',
        'share_fail' => 'Error al compartir el script.',
        'share_success' => 'Script "{0}" compartido con éxito.',
        'confirm_delete_shared' =>
            '¿Estás seguro de que deseas eliminar esta consulta compartida para todos?',
        'delete_shared_fail' => 'Error al eliminar la consulta.',
    ],

    //--------------------------------------------------------------------
    // Gráficos y Visualización
    //--------------------------------------------------------------------
    'charts' => [
        'title' => 'Ver Gráfico',
        'modalTitle' => 'Visualización de Gráfico',
        'type' => 'Tipo de Gráfico',
        'labelAxis' => 'Eje X (Etiquetas)',
        'valueAxis' => 'Eje Y (Valores)',
        'generate' => 'Generar Gráfico',
        'bar' => 'Barras',
        'line' => 'Líneas',
        'pie' => 'Pastel',
    ],

    //--------------------------------------------------------------------
    // Mensajes de Retroalimentación (Éxito, Error, Advertencias)
    //--------------------------------------------------------------------
    'feedback' => [
        'connection_success' => '¡Conexión establecida con éxito!',
        'connection_failed' => 'Error en la conexión.',
        'check_credentials' => 'Verifica el host, el puerto y las credenciales.',
        'logout_success' => 'Has sido desconectado.',
        'session_lost' => 'Sesión de conexión perdida.',
        'query_empty' => 'La consulta SQL no puede estar vacía.',
        'noquery_to_export' => 'No hay consulta para exportar.',
        'no_results_found' => 'No se encontraron resultados.',
        'empty_result' => 'Resultado vacío.',
        'commands_executed_successfully' =>
            'Comando(s) ejecutado(s) con éxito.',
        'result_sets_returned' => 'Conjunto(s) de resultados devuelto(s): ',
        'rows_affected' => 'Fila(s) afectada(s): ',
        'execution_time' => 'Tiempo de ejecución',
        'syntax_error' => 'Error de sintaxis o ejecución: ',
        'unknown_error' => 'Error desconocido',
        'exec_error' => 'Error en la ejecución.',
        'format_fail' => 'Error al formatear el SQL. Verifica la sintaxis.',
        'execution_plan_generation_failed' =>
            'Error al generar el plan de ejecución.',
        'intellisense_error' =>
            'Error al cargar el diccionario de IntelliSense.',
        'error_alter_database' => 'Error al modificar la base de datos.',
        'error_loading_definition' =>
            'ERROR: No se pudo cargar la definición del objeto.',
        'language_not_supported' => 'Idioma no soportado.',
        'invalid_number' => 'Número inválido.',
        'no_templates' => 'No se encontraron plantillas.',
        'db_unsupported_feature' =>
            'Esta funcionalidad no es compatible con la base de datos conectada.',
        'db_invalid_operation' => 'Operación inválida.',
        'db_object_type_not_supported' =>
            'El tipo de objeto "{0}" no es compatible para la recuperación de definición.',
        'db_could_not_retrieve_definition' =>
            'No se pudo obtener la definición para el objeto `{0}`.',
        'db_event_not_found' =>
            'No se pudo obtener la definición para el evento `{0}`.',
        'error_no_db_selected_for_edit' =>
            'Por favor, selecciona una base de datos en el menú superior antes de intentar editar los datos.',
        'data_saved' => '¡Éxito! Todos los cambios fueron guardados.',
        'no_pk_edit' => 'La columna de clave primaria no puede ser editada.',
        'cannot_close_last_tab' => 'Al menos una pestaña debe permanecer activa',
    ],

    //--------------------------------------------------------------------
    // Validación de Requisitos del Servidor
    //--------------------------------------------------------------------
    'server_check' => [
        'title' => 'Validación de Requisitos del Servidor',
        'trigger_button' => 'Probar Compatibilidad',
        'header_item' => 'Requisito',
        'header_status' => 'Estado',
        'header_current' => 'Valor Actual',
        'header_required' => 'Requerido',
        'header_notes' => 'Notas',
        'status_ok' => 'OK',
        'status_fail' => 'FALLO',
        'ok_title' => '¡Todo Bien!',
        'ok_message' =>
            'Tu servidor cumple con todos los requisitos críticos para ejecutar la aplicación.',
        'warn_title' => '¡Advertencia!',
        'warn_message' =>
            'Tu servidor tiene algunas advertencias, pero los requisitos críticos están cumplidos. La aplicación debería funcionar, pero revisa los puntos a continuación.',
        'fail_title' => '¡Problemas Encontrados!',
        'fail_message' =>
            'Tu servidor no cumple con uno o más requisitos críticos. La aplicación no funcionará correctamente hasta que los elementos marcados como FALLO sean corregidos.',
        'go_to_app' => 'Ir a la Aplicación',
        'php_version' => 'Versión de PHP',
        'php_version_note' =>
            'La versión mínima recomendada para el proyecto es 8.0.',
        'php_extension_item' => 'Extensión PHP: {0}',
        'note_sqlsrv' => 'Crítico: Esencial para conectar a SQL Server.',
        'note_intl' =>
            'Crítico: Requerido por CodeIgniter 4 para internacionalización.',
        'note_mbstring' =>
            'Crítico: Esencial para la manipulación de cadenas multibyte.',
        'note_json' => 'Crítico: Esencial para las respuestas de la API.',
        'note_xml' =>
            'Importante: Necesario para la funcionalidad de Plan de Ejecución.',
        'writable_folder' => 'Permiso de escritura en la carpeta "writable"',
        'writable' => 'Escribible',
        'not_writable' => 'No escribible',
        'writable_note' =>
            'CodeIgniter necesita permiso para escribir registros, caché y sesiones.',
        'env_file' => 'Archivo de entorno ".env"',
        'found' => 'Encontrado',
        'env_file_note' =>
            'Recomendado para configurar el entorno de producción/desarrollo.',
    ],

    //--------------------------------------------------------------------
    // Trabajos del Agente de SQL Server
    //--------------------------------------------------------------------
    'agent' => [
        'title' => 'Trabajos del Agente de SQL Server',
        'job_name' => 'Nombre del Trabajo',
        'last_run' => 'Última Ejecución',
        'last_run_status' => 'Resultado',
        'next_run' => 'Próxima Ejecución',
        'no_jobs_found' => 'No se encontraron trabajos del Agente de SQL Server.',
        'status_success' => 'Éxito',
        'status_failed' => 'Falló',
        'status_running' => 'Ejecutando',
        'status_canceled' => 'Cancelado',
        'status_retry' => 'Reintentar',
        'start_job' => 'Iniciar Trabajo',
        'stop_job' => 'Detener Trabajo',
        'job_started' => 'Trabajo iniciado con éxito.',
        'job_start_failed' => 'Error al iniciar el trabajo.',
        'job_stopped' => 'Trabajo detenido con éxito.',
        'job_stop_failed' => 'Error al detener el trabajo.',
        'history' => 'Historial del Trabajo',
        'no_history_found' => 'No se encontró historial para este trabajo.',
        'error_retrieving_history' => 'Error al recuperar el historial del trabajo.',
        'step_name' => 'Nombre del Paso',
        'step_id' => 'ID del Paso',
        'operation' => 'Operación',
        'status_message' => 'Mensaje de Estado',
        'duration' => 'Duración',
        'start_time' => 'Hora de Inicio',
        'end_time' => 'Hora de Finalización',
        'view_details' => 'Ver Detalles',
        'hide_details' => 'Ocultar Detalles',
        'no_details' => 'No hay detalles disponibles.',
    ],

    //--------------------------------------------------------------------
    // Eventos de MySQL
    //--------------------------------------------------------------------
    'event' => [
        'title' => 'Eventos de MySQL',
        'event_name' => 'Nombre del Evento',
        'disable_event' => 'Deshabilitar Evento',
        'enable_event' => 'Habilitar Evento',
        'no_events_found' => 'No se encontraron eventos de MySQL.',
    ],

    //--------------------------------------------------------------------
    // Editor de Esquema (Crear/Diseñar Tabla)
    //--------------------------------------------------------------------
    'schema_editor' => [
        'new_table' => 'Nueva Tabla',
        'design_table' => 'Diseño de Tabla',
        'drop_table' => 'Eliminar Tabla',
        'table_name' => 'Nombre de la Tabla',
        'columns' => 'Columnas',
        'column_name' => 'Nombre de la Columna',
        'data_type' => 'Tipo de Datos',
        'size_length' => 'Tamaño/Longitud',
        'allow_null' => 'Permitir Nulo',
        'primary_key' => 'Clave Primaria',
        'add_column' => 'Agregar Columna',
        'confirm_drop_table' =>
            '¿Estás seguro de que deseas eliminar la tabla {0}? Esta acción no se puede deshacer.',
        'table_drop_successfully' => 'Tabla {0} eliminada con éxito.',
        'table_creation_failed' => 'La creación de la tabla falló.',
        'table_created_successfully' => 'Tabla {0} creada con éxito.',
        'script_alter' => 'Mostrar script de alteración',
        'script_create' => 'Mostrar script de creación',
        'indexes' => 'Índices',
        'index_name' => 'Nombre del Índice',
        'index_columns' => 'Columnas',
        'index_unique' => 'Único',
        'index_type' => 'Tipo',
        'view_data' => 'Ver Datos (Top 200)',
        'add_index' => 'Agregar Índice',
        'index_name_placeholder' => 'ej: idx_nombre_columna',
        'confirm_drop_index' => '¿Estás seguro de que deseas eliminar el índice {0}?',
        'index_dropped_successfully' => 'Índice {0} eliminado con éxito.',
        'index_created_successfully' => 'Índice {0} creado con éxito.',
        'index_creation_failed' => 'La creación del índice falló.',
        'index_drop_failed' => 'La eliminación del índice falló.',
        'select_columns' => 'Seleccionar columnas',
        'constraints' => 'Restricciones',
        'foreign_keys' => 'Claves Foráneas',
        'add_foreign_key' => 'Agregar Clave Foránea',
        'fk_name' => 'Nombre de la Restricción',
        'fk_name_placeholder' => 'ej: fk_tabla_tabla_ref',
        'fk_columns' => 'Columnas en Esta Tabla',
        'fk_references_table' => 'Tabla de Referencia',
        'fk_references_columns' => 'Columnas en la Tabla de Referencia',
        'confirm_drop_fk' => '¿Estás seguro de que deseas eliminar la clave foránea {0}?',
        'fk_dropped_successfully' => 'Clave foránea {0} eliminada con éxito.',
        'fk_created_successfully' => 'Clave foránea {0} creada con éxito.',
        'fk_creation_failed' => 'La creación de la clave foránea falló.',
        'fk_drop_failed' => 'La eliminación de la clave foránea falló.',
    ],

    //--------------------------------------------------------------------
    // Plantillas de Consultas
    //--------------------------------------------------------------------
    'query_templates' => [
        'sqlsrv' => [
            '10_performance' => [
                'title' => 'Rendimiento',
                'scripts' => [
                    '10_active_queries.sql' => [
                        'title' => 'Monitorear Consultas Activas',
                        'description' =>
                            'Muestra todas las consultas que se están ejecutando en el servidor en este momento.',
                    ],
                    '20_slowest_queries_history.sql' => [
                        'title' => 'Top 10 Consultas Más Lentas (Histórico)',
                        'description' =>
                            'Analiza el caché para encontrar las consultas que más consumieron tiempo de CPU.',
                    ],
                    '30_wait_stats.sql' => [
                        'title' => 'Estadísticas de Espera',
                        'description' =>
                            'Muestra los principales cuellos de botella del servidor, indicando qué está esperando SQL Server.',
                    ],
                ],
            ],
            '20_space_usage' => [
                'title' => 'Uso de Espacio',
                'scripts' => [
                    '10_largest_tables.sql' => [
                        'title' => 'Listar Tablas Más Grandes',
                        'description' =>
                            'Calcula y lista las tablas de la base de datos actual, ordenadas por espacio total.',
                    ],
                    '20_space_by_database.sql' => [
                        'title' => 'Uso de Espacio por Base de Datos',
                        'description' =>
                            'Muestra el tamaño total, espacio usado y libre para todas las bases de datos.',
                    ],
                ],
            ],
            '30_indexes' => [
                'title' => 'Índices',
                'scripts' => [
                    '10_index_fragmentation.sql' => [
                        'title' => 'Verificar Fragmentación de Índices',
                        'description' =>
                            'Analiza y lista índices con fragmentación superior al 10%.',
                    ],
                    '20_unused_indexes.sql' => [
                        'title' => 'Índices No Utilizados',
                        'description' =>
                            'Encuentra índices que se mantienen (actualizados) pero rara vez o nunca se usan en lecturas.',
                    ],
                    '30_missing_indexes.sql' => [
                        'title' => 'Índices Faltantes (Sugeridos)',
                        'description' =>
                            'Lista las sugerencias de creación de índices que hace el propio SQL Server.',
                    ],
                    '40_list_table_constraints.sql' => [
                        'title' => 'Listar Restricciones de Tabla',
                        'description' =>
                            'Muestra todas las restricciones (PK, FK, Unique) de una tabla específica.',
                    ],
                ],
            ],
            '40_current_activity' => [
                'title' => 'Actividad Actual',
                'scripts' => [
                    '10_active_locks.sql' => [
                        'title' => 'Consultar Bloqueos Activos',
                        'description' =>
                            'Muestra qué procesos (sesiones) están bloqueando a otros.',
                    ],
                    '20_active_connections.sql' => [
                        'title' => 'Listar Conexiones Activas',
                        'description' =>
                            'Lista todas las conexiones activas en el servidor, mostrando usuario, máquina y programa.',
                    ],
                ],
            ],
            '50_health_and_config' => [
                'title' => 'Salud y Configuración',
                'scripts' => [
                    '10_backup_status.sql' => [
                        'title' => 'Estado de Copias de Seguridad',
                        'description' =>
                            'Verifica y muestra la fecha y el tipo de la última copia de seguridad para cada base de datos.',
                    ],
                    '20_database_configs.sql' => [
                        'title' => 'Configuraciones de Bases de Datos',
                        'description' =>
                            'Lista configuraciones importantes (Modelo de Recuperación, Nivel de Compatibilidad) para cada base de datos.',
                    ],
                    '30_transaction_log_vlf_analysis.sql' => [
                        'title' => 'Analizar Uso del Registro de Transacciones (VLFs)',
                        'description' =>
                            'Verifica la salud del Registro de Transacciones, un factor crítico de rendimiento.',
                    ],
                ],
            ],
            '60_security' => [
                'title' => 'Seguridad',
                'scripts' => [
                    '10_list_sysadmin_logins.sql' => [
                        'title' => 'Listar Inicios de Sesión con Sysadmin',
                        'description' =>
                            'Auditoría de seguridad que lista todos los inicios de sesión con control total del servidor.',
                    ],
                    '20_find_orphan_users.sql' => [
                        'title' => 'Encontrar Usuarios Huérfanos',
                        'description' =>
                            'Encuentra usuarios en una base de datos que ya no están vinculados a un inicio de sesión válido en el servidor.',
                    ],
                    '30_audit_high_level_permissions.sql' => [
                        'title' => 'Auditoría de Permisos Elevados (Base de Datos)',
                        'description' =>
                            'Verifica usuarios con permisos críticos (CONTROL, IMPERSONATE) en la base de datos actual.',
                    ],
                    '40_audit_server_level_permissions.sql' => [
                        'title' => 'Auditoría de Permisos Elevados (Servidor)',
                        'description' =>
                            'Verifica inicios de sesión con permisos críticos (CONTROL SERVER) a nivel de servidor.',
                    ],
                ],
            ],
            '70_sql_server_agent' => [
                'title' => 'Agente de SQL Server',
                'scripts' => [
                    '10_failed_jobs_last_24h.sql' => [
                        'title' => 'Trabajos Fallidos Recientemente',
                        'description' =>
                            'Lista todos los trabajos del Agente de SQL Server que fallaron en las últimas 24 horas.',
                    ],
                    '20_currently_running_jobs.sql' => [
                        'title' => 'Trabajos en Ejecución Ahora',
                        'description' =>
                            'Muestra qué trabajos del Agente de SQL Server están en ejecución en este momento.',
                    ],
                ],
            ],
            '80_object_management' => [
                'title' => 'Gestión de Objetos',
                'scripts' => [
                    '10_check_object_dependencies.sql' => [
                        'title' => 'Verificar Dependencias de Objetos',
                        'description' =>
                            'Usa este script para ver qué otros objetos dependen de una tabla o procedimiento antes de modificarlo.',
                    ],
                ],
            ],
        ],
        'mysql' => [
            '10_performance' => [
                'title' => 'Rendimiento',
                'scripts' => [
                    '10_active_processes.sql' => [
                        'title' => 'Monitorear Procesos Activos',
                        'description' =>
                            'Muestra todos los procesos activos y consultas en el servidor MySQL.',
                    ],
                    '20_slowest_queries_history.sql' => [
                        'title' => 'Top 10 Consultas Más Lentas (Histórico)',
                        'description' =>
                            'Analiza el Performance Schema para encontrar las consultas más lentas.',
                    ],
                ],
            ],
            '20_space_usage' => [
                'title' => 'Uso de Espacio',
                'scripts' => [
                    '10_largest_tables.sql' => [
                        'title' => 'Listar Tablas Más Grandes',
                        'description' =>
                            'Lista las tablas más grandes en la base de datos actual por tamaño.',
                    ],
                ],
            ],
            '30_indexes' => [
                'title' => 'Índices',
                'scripts' => [
                    '10_index_usage.sql' => [
                        'title' => 'Analizar Uso de Índices',
                        'description' =>
                            'Muestra estadísticas de lectura para cada índice, ayudando a identificar los no utilizados.',
                    ],
                    '20_duplicate_indexes.sql' => [
                        'title' => 'Encontrar Índices Duplicados',
                        'description' =>
                            'Lista cualquier índice duplicado o redundante que pueda ser candidato para eliminación.',
                    ],
                ],
            ],
            '60_security' => [
                'title' => 'Seguridad',
                'scripts' => [
                    '10_users_with_all_privileges.sql' => [
                        'title' => 'Listar Usuarios con Privilegios Elevados',
                        'description' =>
                            'Auditoría de seguridad que lista todos los usuarios con privilegios SUPER o GRANT.',
                    ],
                ],
            ],
        ],
    ],
];
