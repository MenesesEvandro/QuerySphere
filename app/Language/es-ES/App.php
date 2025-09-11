<?php
return [
    //--------------------------------------------------------------------
    // Ítems Generales de la UI
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
        'toggleTheme' => 'Alternar Tema',
        'select' => 'Seleccione...',
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
        'canceled' => 'Cancelado',
        'retry' => 'Reintentar',
        'https_warning' =>
            'Advertencia: Se debe acceder a esta página a través de HTTPS para garantizar la seguridad.',
        'crypto_warning' =>
            'Error: Este navegador no es compatible con la API Web Crypto. Por favor, utilice un navegador moderno.',
    ],

    //--------------------------------------------------------------------
    // Pantalla de Conexión y Administración
    //--------------------------------------------------------------------
    'connection' => [
        'title' => 'Conexión',
        'screenTitle' => 'Conéctese a su SQL Server',
        'connect' => 'Conectar',
        'connecting' => 'Conectando...',
        'disconnect' => 'Desconectar',
        'connectedTo' => 'Conectado a',
        'changing' => 'Cambiando...',
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
        'manage_connections' => 'Administrar Conexiones',
        'select_connection' =>
            'Seleccione una conexión existente o cree una nueva.',
        'saved_connection' => 'Conexión Guardada',
        'saved_connections' => 'Conexiones Guardadas',
        'no_saved_connections' =>
            'No hay conexiones guardadas para administrar.',
        'connection_deleted' => 'Conexión eliminada.',
        'confirm_delete_connection' =>
            '¿Está seguro de que desea eliminar la conexión {0}?',
        'prompt_connection_name' =>
            'Ingrese un nombre para esta nueva conexión:',
        'confirm_clear_connections' =>
            '¿Está seguro de que desea eliminar todas las conexiones guardadas?',
        'clear_saved_connections' => 'Eliminar Conexiones Guardadas',
        'connections_cleared' =>
            'Todas las conexiones guardadas han sido eliminadas.',
        'clear_all_connections' => 'Eliminar todas las conexiones',
    ],

    //--------------------------------------------------------------------
    // Contraseña Maestra
    //--------------------------------------------------------------------
    'master_password' => [
        'title' => 'Contraseña Maestra',
        'hint' =>
            'Debe tener al menos 8 caracteres con letras, números y símbolos.',
        'enter' => 'Ingrese la Contraseña Maestra',
        'change' => 'Cambiar Contraseña Maestra',
        'change_info' =>
            'Puede cambiar su Contraseña Maestra aquí. Deberá ingresar la contraseña actual para su verificación.',
        'current' => 'Contraseña Maestra Actual',
        'new' => 'Nueva Contraseña Maestra',
        'confirm_new' => 'Confirmar Nueva Contraseña Maestra',
        'no_match' =>
            'La nueva Contraseña Maestra y la confirmación no coinciden.',
        'changed_success' => 'Contraseña Maestra cambiada con éxito.',
        'incorrect' => 'La Contraseña Maestra Actual es incorrecta.',
        'invalid' =>
            'Contraseña Maestra no válida. Debe tener al menos 8 caracteres con letras, números y símbolos.',
        'new_prompt' =>
            'CREE una Contraseña Maestra para proteger sus contraseñas guardadas.\nEsta contraseña NO se guardará y se le solicitará cada vez que necesite cargar una contraseña.',
        'ask_prompt' =>
            'Por favor, ingrese su Contraseña Maestra para cargar la contraseña.',
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
            'Ejecute una consulta para ver los resultados.',
        'search_placeholder' => 'Buscar en {0}...',
        'templates' => 'Plantillas',
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
    ],

    //--------------------------------------------------------------------
    // Administración de Scripts (Guardar, Compartir)
    //--------------------------------------------------------------------
    'scripts' => [
        'save' => 'Guardar Script',
        'share' => 'Compartir',
        'confirm_delete' => '¿Está seguro de que desea eliminar este script?',
        'prompt_name' => 'Ingrese el nombre del script:',
        'default_name' => 'Mi Script',
        'empty_alert' => 'No hay script para guardar.',
        'empty_shared_alert' => 'No hay script para compartir.',
        'prompt_shared_name' =>
            'Ingrese un nombre para esta consulta compartida:',
        'shared_default_name' => 'Script Compartido',
        'prompt_author' => 'Su nombre:',
        'author_default' => 'Usuario',
        'share_fail' => 'Error al compartir el script.',
        'confirm_delete_shared' =>
            '¿Está seguro de que desea eliminar esta consulta compartida para todos?',
        'delete_shared_fail' => 'Error al eliminar la consulta.',
    ],

    //--------------------------------------------------------------------
    // Gráficos y Visualización
    //--------------------------------------------------------------------
    'charts' => [
        'title' => 'Visualizar Gráfico',
        'modalTitle' => 'Visualización Gráfica',
        'type' => 'Tipo de Gráfico',
        'labelAxis' => 'Eje X (Etiquetas)',
        'valueAxis' => 'Eje Y (Valores)',
        'generate' => 'Generar Gráfico',
        'bar' => 'Barras',
        'line' => 'Líneas',
        'pie' => 'Tarta',
    ],

    //--------------------------------------------------------------------
    // Mensajes de Feedback (Éxito, Error, Advertencias)
    //--------------------------------------------------------------------
    'feedback' => [
        'connection_success' => '¡Conexión establecida con éxito!',
        'connection_failed' => 'Error de conexión.',
        'check_credentials' =>
            'Verifique el host, el puerto y las credenciales.',
        'logout_success' => 'Ha sido desconectado.',
        'session_lost' => 'Se perdió la sesión de conexión.',
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
        'format_fail' => 'Error al formatear SQL. Verifique la sintaxis.',
        'execution_plan_generation_failed' =>
            'Error en la generación del plan de ejecución.',
        'intellisense_error' =>
            'Error al cargar el diccionario de IntelliSense.',
        'error_alter_database' => 'Error al alterar la base de datos.',
        'error_loading_definition' =>
            'ERROR: No se pudo cargar la definición del objeto.',
        'language_not_supported' => 'Idioma no soportado.',
        'invalid_number' => 'Número no válido.',
        'no_templates' => 'No se encontraron plantillas.',
        'db_unsupported_feature' =>
            'Esta característica no es compatible con la base de datos conectada.',
        'db_invalid_operation' => 'Operación inválida.',
        'db_object_type_not_supported' =>
            'El tipo de objeto \'{0}\' no es compatible para la búsqueda de definiciones.',
        'db_could_not_retrieve_definition' =>
            'No se pudo recuperar la definición para el objeto `{0}`.',
        'db_event_not_found' =>
            'No se pudo recuperar la definición para el evento `{0}`.',
    ],

    //--------------------------------------------------------------------
    // Verificación de Requisitos del Servidor
    //--------------------------------------------------------------------
    'server_check' => [
        'title' => 'Verificación de Requisitos del Servidor',
        'trigger_button' => 'Probar Compatibilidad',
        'header_item' => 'Requisito',
        'header_status' => 'Estado',
        'header_current' => 'Valor Actual',
        'header_required' => 'Requerido',
        'header_notes' => 'Notas',
        'status_ok' => 'OK',
        'status_fail' => 'FALLA',
        'ok_title' => '¡Todo Correcto!',
        'ok_message' =>
            'Su servidor cumple con todos los requisitos críticos para ejecutar la aplicación.',
        'warn_title' => '¡Atención!',
        'warn_message' =>
            'Su servidor tiene algunas advertencias, pero se cumplen los requisitos críticos. La aplicación debería funcionar, pero verifique los puntos a continuación.',
        'fail_title' => '¡Problemas Encontrados!',
        'fail_message' =>
            'Su servidor no cumple con uno o más requisitos críticos. La aplicación no funcionará correctamente hasta que se corrijan los elementos marcados con FALLA.',
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
            'Importante: Necesario para la funcionalidad del Plan de Ejecución.',
        'writable_folder' => 'Permiso de escritura en la carpeta "writable"',
        'writable' => 'Escribible',
        'not_writable' => 'No Escribible',
        'writable_note' =>
            'CodeIgniter necesita permiso para escribir logs, caché y sesiones.',
        'env_file' => 'Archivo de entorno ".env"',
        'found' => 'Encontrado',
        'env_file_note' =>
            'Recomendado para configurar el entorno de producción/desarrollo.',
    ],

    //--------------------------------------------------------------------
    // Trabajos del Agente SQL Server
    //--------------------------------------------------------------------
    'agent' => [
        'title' => 'Trabajos del Agente SQL Server',
        'job_name' => 'Nombre del Trabajo',
        'last_run' => 'Última Ejecución',
        'last_run_status' => 'Resultado',
        'next_run' => 'Próxima Ejecución',
        'no_jobs_found' => 'No se encontraron trabajos del Agente SQL Server.',
        'status_success' => 'Éxito',
        'status_failed' => 'Falló',
        'status_running' => 'En ejecución',
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
        'error_retrieving_history' =>
            'Error al recuperar el historial del trabajo.',
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
    // MySQL Events
    //--------------------------------------------------------------------
    'event' => [
        'title' => 'Eventos do MySQL',
        'event_name' => 'Nombre del Evento',
        'disable_event' => 'Deshabilitar Evento',
        'enable_event' => 'Habilitar Evento',
        'no_events_found' => 'No se encontraron eventos del MySQL.',
    ],

    //--------------------------------------------------------------------
    // Plantillas de Consulta
    //--------------------------------------------------------------------
    'query_templates' => [
        '10_performance' => [
            'title' => 'Rendimiento',
            'scripts' => [
                '10_active_queries.sql' => [
                    'title' => 'Monitorear Consultas Activas',
                    'description' =>
                        'Muestra todas las consultas que se están ejecutando en el servidor en este momento.',
                ],
                '20_slowest_queries_history.sql' => [
                    'title' => 'Top 10 Consultas Más Lentas (Historial)',
                    'description' =>
                        'Analiza la caché para encontrar las consultas que han consumido más tiempo de CPU.',
                ],
                '30_wait_stats.sql' => [
                    'title' => 'Estadísticas de Espera (Wait Stats)',
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
                        'Muestra el tamaño total, el espacio usado y el espacio libre para todas las bases de datos.',
                ],
            ],
        ],
        '30_indexes' => [
            'title' => 'Índices',
            'scripts' => [
                '10_index_fragmentation.sql' => [
                    'title' => 'Verificar Fragmentación de Índices',
                    'description' =>
                        'Analiza y lista los índices con una fragmentación superior al 10%.',
                ],
                '20_unused_indexes.sql' => [
                    'title' => 'Índices no Utilizados',
                    'description' =>
                        'Encuentra índices que se mantienen (actualizaciones) pero que rara vez o nunca se usan en lecturas.',
                ],
                '30_missing_indexes.sql' => [
                    'title' => 'Índices Faltantes (Sugeridos)',
                    'description' =>
                        'Enumera las sugerencias de creación de índices que hace el propio SQL Server.',
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
                    'title' => 'Consultar Bloqueos (Locks) Activos',
                    'description' =>
                        'Muestra qué procesos (sesiones) están bloqueando otros procesos.',
                ],
                '20_active_connections.sql' => [
                    'title' => 'Listar Conexiones Activas',
                    'description' =>
                        'Enumera todas las conexiones activas en el servidor, mostrando usuario, máquina y programa.',
                ],
            ],
        ],
        '50_health_and_config' => [
            'title' => 'Salud y Configuración',
            'scripts' => [
                '10_backup_status.sql' => [
                    'title' => 'Estado de las Copias de Seguridad',
                    'description' =>
                        'Verifica y muestra la fecha y el tipo de la última copia de seguridad para cada base de datos.',
                ],
                '20_database_configs.sql' => [
                    'title' => 'Configuraciones de las Bases de Datos',
                    'description' =>
                        'Enumera configuraciones importantes (Modelo de Recuperación, Nivel de Compatibilidad) para cada base de datos.',
                ],
                '30_transaction_log_vlf_analysis.sql' => [
                    'title' => 'Analizar Uso del Log de Transacciones (VLFs)',
                    'description' =>
                        'Verifica la salud del Log de Transacciones, un factor crítico de rendimiento.',
                ],
            ],
        ],
        '60_security' => [
            'title' => 'Seguridad',
            'scripts' => [
                '10_list_sysadmin_logins.sql' => [
                    'title' => 'Listar Inicios de Sesión con Sysadmin',
                    'description' =>
                        'Auditoría de seguridad que enumera todos los inicios de sesión con control total del servidor.',
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
                        'Enumera todos los trabajos del Agente de SQL Server que han fallado en las últimas 24 horas.',
                ],
                '20_currently_running_jobs.sql' => [
                    'title' => 'Trabajos en Ejecución Actualmente',
                    'description' =>
                        'Muestra qué trabajos del Agente de SQL Server se están ejecutando en este momento.',
                ],
            ],
        ],
        '80_object_management' => [
            'title' => 'Gestión de Objetos',
            'scripts' => [
                '10_check_object_dependencies.sql' => [
                    'title' => 'Verificar Dependencias de Objetos',
                    'description' =>
                        'Use este script para ver de qué otros objetos depende una tabla o procedimiento antes de alterarlo.',
                ],
            ],
        ],
    ],
];
