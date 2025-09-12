<?php
return [
    //--------------------------------------------------------------------
    // General UI Items
    //--------------------------------------------------------------------
    'general' => [
        'submit' => 'Submit',
        'close' => 'Close',
        'details' => 'Details',
        'loading' => 'Loading...',
        'page' => 'Page',
        'of' => 'of',
        'records' => 'records',
        'result' => 'result',
        'actions' => 'Actions',
        'delete' => 'Delete',
        'edit' => 'Edit',
        'previous' => 'Previous',
        'next' => 'Next',
        'language' => 'Language',
        'theme' => 'Theme',
        'toggleTheme' => 'Toggle Theme',
        'select' => 'Select...',
        'status' => 'Status',
        'unknown' => 'Unknown',
        'enable' => 'Enable',
        'enabled' => 'Enabled',
        'disable' => 'Disable',
        'disabled' => 'Disabled',
        'not_found' => 'Not found',
        'running' => 'Running',
        'success' => 'Success',
        'failed' => 'Failed',
        'canceled' => 'Canceled',
        'retry' => 'Retry',
        'https_warning' =>
            'Warning: This page should be accessed via HTTPS to ensure security.',
        'crypto_warning' =>
            'Error: This browser does not support the Web Crypto API. Please use a modern browser.',
    ],

    //--------------------------------------------------------------------
    // Connection and Management Screen
    //--------------------------------------------------------------------
    'connection' => [
        'title' => 'Connection',
        'screenTitle' => 'Connect to your SQL Server',
        'connect' => 'Connect',
        'connecting' => 'Connecting...',
        'disconnect' => 'Disconnect',
        'connectedTo' => 'Connected to',
        'changing' => 'Changing...',
        'db_type' => 'Database Type',
        'sql_server' => 'SQL Server',
        'mysql' => 'MySQL',
        'postgresql' => 'PostgreSQL (coming soon)',
        'host' => 'Host (Server Address)',
        'port' => 'Port',
        'database' => 'Database Name (Optional)',
        'user' => 'User',
        'password' => 'Password',
        'show_password' => 'Show Password',
        'hide_password' => 'Hide Password',
        'rememberConnection' => 'Save connection data',
        'trust_cert' => 'Trust server certificate (for localhost/self-signed)',
        'new_connection' => '-- New Connection --',
        'manage_connections' => 'Manage Connections',
        'select_connection' =>
            'Select an existing connection or create a new one.',
        'saved_connection' => 'Saved Connection',
        'saved_connections' => 'Saved Connections',
        'no_saved_connections' => 'No saved connections to manage.',
        'connection_deleted' => 'Connection deleted.',
        'confirm_delete_connection' =>
            'Are you sure you want to delete the connection {0}?',
        'prompt_connection_name' => 'Enter a name for this new connection:',
        'confirm_clear_connections' =>
            'Are you sure you want to delete all saved connections?',
        'clear_saved_connections' => 'Clear Saved Connections',
        'connections_cleared' => 'All saved connections have been cleared.',
        'clear_all_connections' => 'Clear all connections',
    ],

    //--------------------------------------------------------------------
    // Master Password
    //--------------------------------------------------------------------
    'master_password' => [
        'title' => 'Master Password',
        'hint' =>
            'Must be at least 8 characters with letters, numbers, and symbols.',
        'enter' => 'Enter Master Password',
        'change' => 'Change Master Password',
        'change_info' =>
            'You can change your Master Password here. You will need to enter the current password for verification.',
        'current' => 'Current Master Password',
        'new' => 'New Master Password',
        'confirm_new' => 'Confirm New Master Password',
        'no_match' => 'New Master Password and confirmation do not match.',
        'changed_success' => 'Master Password changed successfully.',
        'incorrect' => 'Current Master Password is incorrect.',
        'invalid' =>
            'Invalid Master Password. Must be at least 8 characters with letters, numbers, and symbols.',
        'new_prompt' =>
            'CREATE a Master Password to protect your saved passwords.\nThis password will NOT be saved and will be required whenever you need to load a password.',
        'ask_prompt' =>
            'Please enter your Master Password to load the password.',
        'error_decrypting' =>
            'Error decrypting password. Incorrect Master Password?',
        'no_saved_passwords' => 'No saved passwords.',
        'saved_passwords' => 'Saved Passwords',
    ],

    //--------------------------------------------------------------------
    // Main Workspace (Editor, Results, Tabs)
    //--------------------------------------------------------------------
    'workspace' => [
        'execute' => 'Execute',
        'executing' => 'Executing...',
        'explain' => 'Execution Plan',
        'formatSQL' => 'Format SQL',
        'exportCSV' => 'Export CSV',
        'exportJSON' => 'Export JSON',
        'objects' => 'Objects',
        'history' => 'History',
        'saved' => 'Saved',
        'shared' => 'Shared',
        'results' => 'Results',
        'messages' => 'Messages',
        'queryResultsPlaceholder' => 'Execute a query to see the results.',
        'search_placeholder' => 'Search in {0}...',
        'templates' => 'Templates',
        'save_changes' => 'Save Changes',
        'saving_changes' => 'Saving...',
        'error_saving' => 'Error saving changes.',
        'no_primary_key' =>
            'This table cannot be edited because it does not have a primary key.',
        'multiple_tables_not_supported' =>
            'Editing is not supported for results from multiple tables.',
        'no_table_detected' =>
            'Could not detect a table in the query. Editing is disabled.',
        'confirm_discard_changes' =>
            'You have unsaved changes. Are you sure you want to discard them?',
    ],

    //--------------------------------------------------------------------
    // Objects Browser
    //--------------------------------------------------------------------
    'objects_browser' => [
        'search' => 'Search objects',
        'tables' => 'Tables',
        'views' => 'Views',
        'stored_procedures' => 'Stored Procedures',
        'functions' => 'Functions',
        'no_parameters' => 'No parameters',
        'loading_definition_for' => 'Loading definition for {0}...',
        'script_execute' => 'Script as Execute',
    ],

    //--------------------------------------------------------------------
    // Script Management (Save, Share)
    //--------------------------------------------------------------------
    'scripts' => [
        'save' => 'Save Script',
        'share' => 'Share',
        'confirm_delete' => 'Are you sure you want to delete this script?',
        'prompt_name' => 'Enter the script name:',
        'default_name' => 'My Script',
        'empty_alert' => 'There is no script to save.',
        'empty_shared_alert' => 'There is no script to share.',
        'prompt_shared_name' => 'Enter a name for this shared query:',
        'shared_default_name' => 'Shared Script',
        'prompt_author' => 'Your name:',
        'author_default' => 'User',
        'share_fail' => 'Failed to share the script.',
        'confirm_delete_shared' =>
            'Are you sure you want to delete this shared query for everyone?',
        'delete_shared_fail' => 'Failed to delete the query.',
    ],

    //--------------------------------------------------------------------
    // Charts and Visualization
    //--------------------------------------------------------------------
    'charts' => [
        'title' => 'View Chart',
        'modalTitle' => 'Chart Visualization',
        'type' => 'Chart Type',
        'labelAxis' => 'X-Axis (Labels)',
        'valueAxis' => 'Y-Axis (Values)',
        'generate' => 'Generate Chart',
        'bar' => 'Bar',
        'line' => 'Line',
        'pie' => 'Pie',
    ],

    //--------------------------------------------------------------------
    // Feedback Messages (Success, Error, Warnings)
    //--------------------------------------------------------------------
    'feedback' => [
        'connection_success' => 'Connection established successfully!',
        'connection_failed' => 'Connection failed.',
        'check_credentials' => 'Check the host, port, and credentials.',
        'logout_success' => 'You have been disconnected.',
        'session_lost' => 'Connection session lost.',
        'query_empty' => 'The SQL query cannot be empty.',
        'noquery_to_export' => 'No query to export.',
        'no_results_found' => 'No results found.',
        'empty_result' => 'Empty result.',
        'commands_executed_successfully' => 'Command(s) executed successfully.',
        'result_sets_returned' => 'Result set(s) returned: ',
        'rows_affected' => 'Row(s) affected: ',
        'execution_time' => 'Execution time',
        'syntax_error' => 'Syntax or execution error: ',
        'unknown_error' => 'Unknown error',
        'exec_error' => 'Execution error.',
        'format_fail' => 'Failed to format SQL. Check the syntax.',
        'execution_plan_generation_failed' =>
            'Execution plan generation failed.',
        'intellisense_error' => 'Failed to load IntelliSense dictionary.',
        'error_alter_database' => 'Failed to alter database.',
        'error_loading_definition' =>
            'ERROR: Could not load the object definition.',
        'language_not_supported' => 'Language not supported.',
        'invalid_number' => 'Invalid number.',
        'no_templates' => 'No templates found.',
        'db_unsupported_feature' =>
            'This feature is not supported for the connected database.',
        'db_invalid_operation' => 'Invalid operation.',
        'db_object_type_not_supported' =>
            'Object type \'{0}\' is not supported for definition lookup.',
        'db_could_not_retrieve_definition' =>
            'Could not retrieve definition for object `{0}`.',
        'db_event_not_found' =>
            'Could not retrieve definition for event `{0}`.',
        'error_no_db_selected_for_edit' =>
            'Please select a database from the top menu before attempting to edit data.',
        'data_saved' => '¡Éxito! Todos los cambios han sido guardados.',
        'no_pk_edit' => 'Primary key column cannot be edited.',
    ],

    //--------------------------------------------------------------------
    // Server Requirements Check
    //--------------------------------------------------------------------
    'server_check' => [
        'title' => 'Server Requirements Check',
        'trigger_button' => 'Test Compatibility',
        'header_item' => 'Requirement',
        'header_status' => 'Status',
        'header_current' => 'Current Value',
        'header_required' => 'Required',
        'header_notes' => 'Notes',
        'status_ok' => 'OK',
        'status_fail' => 'FAIL',
        'ok_title' => 'All Good!',
        'ok_message' =>
            'Your server meets all the critical requirements to run the application.',
        'warn_title' => 'Warning!',
        'warn_message' =>
            'Your server has some warnings, but the critical requirements are met. The application should work, but please check the points below.',
        'fail_title' => 'Issues Found!',
        'fail_message' =>
            'Your server does not meet one or more critical requirements. The application will not work correctly until the items marked as FAIL are fixed.',
        'go_to_app' => 'Go to Application',
        'php_version' => 'PHP Version',
        'php_version_note' =>
            'The minimum recommended version for the project is 8.0.',
        'php_extension_item' => 'PHP Extension: {0}',
        'note_sqlsrv' => 'Critical: Essential for connecting to SQL Server.',
        'note_intl' =>
            'Critical: Required by CodeIgniter 4 for internationalization.',
        'note_mbstring' =>
            'Critical: Essential for multibyte string manipulation.',
        'note_json' => 'Critical: Essential for API responses.',
        'note_xml' =>
            'Important: Required for the Execution Plan functionality.',
        'writable_folder' => 'Write permission in "writable" folder',
        'writable' => 'Writable',
        'not_writable' => 'Not Writable',
        'writable_note' =>
            'CodeIgniter needs permission to write logs, cache, and sessions.',
        'env_file' => '".env" environment file',
        'found' => 'Found',
        'env_file_note' =>
            'Recommended for configuring the production/development environment.',
    ],

    //--------------------------------------------------------------------
    // SQL Server Agent Jobs
    //--------------------------------------------------------------------
    'agent' => [
        'title' => 'SQL Server Agent Jobs',
        'job_name' => 'Job Name',
        'last_run' => 'Last Run',
        'last_run_status' => 'Result',
        'next_run' => 'Next Run',
        'no_jobs_found' => 'No SQL Server Agent jobs found.',
        'status_success' => 'Success',
        'status_failed' => 'Failed',
        'status_running' => 'Running',
        'status_canceled' => 'Canceled',
        'status_retry' => 'Retry',
        'start_job' => 'Start Job',
        'stop_job' => 'Stop Job',
        'job_started' => 'Job started successfully.',
        'job_start_failed' => 'Failed to start job.',
        'job_stopped' => 'Job stopped successfully.',
        'job_stop_failed' => 'Failed to stop job.',
        'history' => 'Job History',
        'no_history_found' => 'No history found for this job.',
        'error_retrieving_history' => 'Error retrieving job history.',
        'step_name' => 'Step Name',
        'step_id' => 'Step ID',
        'operation' => 'Operation',
        'status_message' => 'Status Message',
        'duration' => 'Duration',
        'start_time' => 'Start Time',
        'end_time' => 'End Time',
        'view_details' => 'View Details',
        'hide_details' => 'Hide Details',
        'no_details' => 'No details available.',
    ],

    //--------------------------------------------------------------------
    // MySQL Events
    //--------------------------------------------------------------------
    'event' => [
        'title' => 'MySQL Events',
        'event_name' => 'Event Name',
        'disable_event' => 'Disable Event',
        'enable_event' => 'Enable Event',
        'no_events_found' => 'No MySQL Events found.',
    ],

    //--------------------------------------------------------------------
    // Schema Editor (Create/Design Table)
    //--------------------------------------------------------------------
    'schema_editor' => [
        'new_table' => 'New Table',
        'design_table' => 'Design Table',
        'drop_table' => 'Drop Table',
        'table_name' => 'Table Name',
        'columns' => 'Columns',
        'column_name' => 'Column Name',
        'data_type' => 'Data Type',
        'size_length' => 'Size/Length',
        'allow_null' => 'Allow Null',
        'primary_key' => 'Primary Key',
        'add_column' => 'Add Column',
        'confirm_drop_table' =>
            'Are you sure you want to drop the table "{0}"? This action cannot be undone.',
        'table_creation_failed' => 'Table creation failed.',
        'table_created_successfully' => 'Table "{0}" created successfully.',
        'script_alter' => 'Display edit script',
        'script_create' => 'Display creation script',
    ],

    //--------------------------------------------------------------------
    // Query Templates
    //--------------------------------------------------------------------
    'query_templates' => [
        '10_performance' => [
            'title' => 'Performance',
            'scripts' => [
                '10_active_queries.sql' => [
                    'title' => 'Monitor Active Queries',
                    'description' =>
                        'Shows all queries currently being executed on the server.',
                ],
                '20_slowest_queries_history.sql' => [
                    'title' => 'Top 10 Slowest Queries (History)',
                    'description' =>
                        'Analyzes the cache to find the queries that have consumed the most CPU time.',
                ],
                '30_wait_stats.sql' => [
                    'title' => 'Wait Statistics (Wait Stats)',
                    'description' =>
                        'Shows the main server bottlenecks, indicating what SQL Server is waiting for.',
                ],
            ],
        ],
        '20_space_usage' => [
            'title' => 'Space Usage',
            'scripts' => [
                '10_largest_tables.sql' => [
                    'title' => 'List Largest Tables',
                    'description' =>
                        'Calculates and lists the tables of the current database, ordered by total space.',
                ],
                '20_space_by_database.sql' => [
                    'title' => 'Space Usage by Database',
                    'description' =>
                        'Shows the total size, used space, and free space for all databases.',
                ],
            ],
        ],
        '30_indexes' => [
            'title' => 'Indexes',
            'scripts' => [
                '10_index_fragmentation.sql' => [
                    'title' => 'Check Index Fragmentation',
                    'description' =>
                        'Analyzes and lists indexes with fragmentation above 10%.',
                ],
                '20_unused_indexes.sql' => [
                    'title' => 'Unused Indexes',
                    'description' =>
                        'Finds indexes that are maintained (updates) but are rarely or never used in reads.',
                ],
                '30_missing_indexes.sql' => [
                    'title' => 'Missing Indexes (Suggested)',
                    'description' =>
                        'Lists the index creation suggestions that SQL Server itself makes.',
                ],
                '40_list_table_constraints.sql' => [
                    'title' => 'List Table Constraints',
                    'description' =>
                        'Displays all constraints (PK, FK, Unique) of a specific table.',
                ],
            ],
        ],
        '40_current_activity' => [
            'title' => 'Current Activity',
            'scripts' => [
                '10_active_locks.sql' => [
                    'title' => 'Query Active Locks',
                    'description' =>
                        'Shows which processes (sessions) are blocking other processes.',
                ],
                '20_active_connections.sql' => [
                    'title' => 'List Active Connections',
                    'description' =>
                        'Lists all active connections on the server, showing user, host, and program.',
                ],
            ],
        ],
        '50_health_and_config' => [
            'title' => 'Health and Configuration',
            'scripts' => [
                '10_backup_status.sql' => [
                    'title' => 'Backup Status',
                    'description' =>
                        'Checks and shows the date and type of the last backup for each database.',
                ],
                '20_database_configs.sql' => [
                    'title' => 'Database Configurations',
                    'description' =>
                        'Lists important configurations (Recovery Model, Compatibility Level) for each database.',
                ],
                '30_transaction_log_vlf_analysis.sql' => [
                    'title' => 'Analyze Transaction Log Usage (VLFs)',
                    'description' =>
                        'Checks the health of the Transaction Log, a critical performance factor.',
                ],
            ],
        ],
        '60_security' => [
            'title' => 'Security',
            'scripts' => [
                '10_list_sysadmin_logins.sql' => [
                    'title' => 'List Logins with Sysadmin',
                    'description' =>
                        'Security audit that lists all logins with full server control.',
                ],
                '20_find_orphan_users.sql' => [
                    'title' => 'Find Orphaned Users',
                    'description' =>
                        'Finds users in a database that are no longer linked to a valid server login.',
                ],
                '30_audit_high_level_permissions.sql' => [
                    'title' => 'Audit High-Level Permissions (Database)',
                    'description' =>
                        'Checks for users with critical permissions (CONTROL, IMPERSONATE) in the current database.',
                ],
                '40_audit_server_level_permissions.sql' => [
                    'title' => 'Audit High-Level Permissions (Server)',
                    'description' =>
                        'Checks for logins with critical permissions (CONTROL SERVER) at the server level.',
                ],
            ],
        ],
        '70_sql_server_agent' => [
            'title' => 'SQL Server Agent',
            'scripts' => [
                '10_failed_jobs_last_24h.sql' => [
                    'title' => 'Recently Failed Jobs',
                    'description' =>
                        'Lists all SQL Server Agent jobs that have failed in the last 24 hours.',
                ],
                '20_currently_running_jobs.sql' => [
                    'title' => 'Currently Running Jobs',
                    'description' =>
                        'Shows which SQL Server Agent jobs are currently running.',
                ],
            ],
        ],
        '80_object_management' => [
            'title' => 'Object Management',
            'scripts' => [
                '10_check_object_dependencies.sql' => [
                    'title' => 'Check Object Dependencies',
                    'description' =>
                        'Use this script to see which other objects depend on a table or procedure before altering it.',
                ],
            ],
        ],
    ],
];
