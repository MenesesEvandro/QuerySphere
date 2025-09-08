<?php
return [
    'connect' => 'Conectar',
    'connecting' => 'Conectando...',
    'host' => 'Host (Endereço do Servidor)',
    'port' => 'Porta',
    'database' => 'Nome do Banco (Opcional)',
    'user' => 'Usuário',
    'password' => 'Senha',
    'connectionScreenTitle' => 'Conecte-se ao seu SQL Server',
    'execute' => 'Executar',
    'executing' => 'Executando...',
    'explain' => 'Plano de Execução',
    'exportCSV' => 'Exportar CSV',
    'exportJSON' => 'Exportar JSON',
    'chart' => 'Visualizar Gráfico',
    'objects' => 'Objetos',
    'history' => 'Histórico',
    'saved' => 'Salvos',
    'saveScript' => 'Salvar Script',
    'results' => 'Resultados',
    'messages' => 'Mensagens',
    'connectedTo' => 'Conectado a',
    'disconnect' => 'Desconectar',
    'queryResultsPlaceholder' => 'Execute uma consulta para ver os resultados.',
    'chartModalTitle' => 'Visualização Gráfica',
    'chartType' => 'Tipo de Gráfico',
    'chartLabelAxis' => 'Eixo X (Rótulos)',
    'chartValueAxis' => 'Eixo Y (Valores)',
    'chartGenerate' => 'Gerar Gráfico',
    'close' => 'Fechar',
    'bar' => 'Barras',
    'line' => 'Linhas',
    'pie' => 'Pizza',
    'formatSQL' => 'Formatar SQL',
    'toggletheme' => 'Alternar Tema',
    'noquery' => 'Nenhuma consulta para exportar.',
    'tables' => 'Tabelas',
    'views' => 'Views',
    'stored_procedures' => 'Procedures Armazenadas',
    'functions' => 'Funções',
    'no_parameters' => 'Sem parâmetros',
    'query_empty' => 'A consulta SQL não pode estar vazia.',
    'connection_success' => 'Conexão estabelecida com sucesso!',
    'logout_success' => 'Você foi desconectado.',
    'connection_failed' => 'Falha na conexão.',
    'details' => 'Detalhes',
    'check_credentials' => 'Verifique o host, a porta e as credenciais.',
    'session_lost' => 'Sessão de conexão perdida.',
    'syntax_error' => 'Erro de sintaxe ou execução: ',
    'unknown_error' => 'Erro desconhecido',
    'commands_executed_successfully' => 'Comando(s) executado(s) com sucesso.',
    'result_sets_returned' => 'Conjunto(s) de resultados retornado(s): ',
    'rows_affected' => 'Linha(s) afetada(s): ',
    'execution_time' => 'Tempo de execução',
    'execution_plan_generation_failed' =>
        'Falha na geração do plano de execução.',
    'language_not_supported' => 'Idioma não suportado.',
    'php_extension_item' => 'Extensão PHP: {0}',
    'php_version_note' => 'A versão recomendada para o projeto é 8.0.9.',
    'sqlsrv_extension_note' =>
        'Crítico: Essencial para conectar ao SQL Server.',
    'intl_extension_note' =>
        'Crítico: Requerido pelo CodeIgniter 4 para internacionalização.',
    'mbstring_extension_note' =>
        'Crítico: Essencial para manipulação de strings multibyte.',
    'json_extension_note' => 'Crítico: Essencial para as respostas da API.',
    'xml_extension_note' =>
        'Importante: Necessário para a funcionalidade de "Plano de Execução".',
    'enabled' => 'Habilitada',
    'not_found' => 'Não encontrada',
    'writable_permission' => 'Permissão de escrita na pasta "writable"',
    'writable_permission_note' =>
        'O CodeIgniter precisa de permissão para escrever logs, cache e sessões.',
    'env_file' => 'Arquivo de ambiente ".env"',
    'env_file_note' =>
        'Recomendado para configurar o ambiente de produção/desenvolvimento.',
    'server_validation' => 'Validação do Servidor',
    'searchobjects' => 'Buscar objetos',
    'intellisense_error' => 'Falha ao carregar o dicionário do IntelliSense.',
    'select' => 'Selecione ...',
    'error_alter_database' => 'Falha ao alterar o banco de dados.',
    'changing' => 'Alterando ...',
    'rememberConnection' => 'Lembrar dados de conexão (exceto senha)',
    'previous' => 'Anterior',
    'next' => 'Próximo',
    'shared' => 'Compartilhadas',
    'share' => 'Compartilhar',
    'loading_definition_for' => 'Carregando definição para {0}...',
    'error_loading_definition' =>
        'ERRO: Não foi possível carregar a definição do objeto.',
    'confirm_delete_script' => 'Tem certeza de que deseja excluir este script?',
    'prompt_script_name' => 'Digite o nome do script:',
    'script_name_default' => 'Meu Script',
    'empty_script_alert' => 'Não há script para salvar.',
    'empty_shared_script_alert' => 'Não há script para compartilhar.',
    'prompt_shared_name' => 'Digite um nome para esta query compartilhada:',
    'shared_name_default' => 'Script Compartilhado',
    'prompt_author' => 'Seu nome:',
    'author_default' => 'Usuário',
    'share_fail' => 'Falha ao compartilhar o script.',
    'confirm_delete_shared' =>
        'Tem certeza que deseja apagar esta query compartilhada para todos?',
    'delete_shared_fail' => 'Falha ao apagar a query.',
    'format_fail' => 'Falha ao formatar o SQL. Verifique a sintaxe.',
    'exec_error' => 'Erro na execução.',
    'no_results_found' => 'Nenhum resultado encontrado.',
    'empty_result' => 'Resultado vazio.',
    'page' => 'Página',
    'of' => 'de',
    'records' => 'registros',
    'result' => 'resultado',
    'server_compatibility_check' => 'Testar Compatibilidade',
    'check_title' => 'Validação dos Requisitos do Servidor',
    'check_ok_title' => 'Tudo Certo!',
    'check_ok_message' =>
        'Seu servidor atende a todos os requisitos críticos para executar a aplicação.',
    'check_warn_title' => 'Atenção!',
    'check_warn_message' =>
        'Seu servidor possui alguns avisos, mas os requisitos críticos foram atendidos. A aplicação deve funcionar, mas verifique os pontos abaixo.',
    'check_fail_title' => 'Problemas Encontrados!',
    'check_fail_message' =>
        'Seu servidor não atende a um ou mais requisitos críticos. A aplicação não funcionará corretamente até que os itens marcados com FALHA sejam corrigidos.',
    'check_header_item' => 'Requisito',
    'check_header_status' => 'Status',
    'check_header_current' => 'Valor Atual',
    'check_header_required' => 'Requerido',
    'check_header_notes' => 'Observações',
    'check_status_ok' => 'OK',
    'check_status_fail' => 'FALHA',
    'check_php_version' => 'Versão do PHP',
    'check_php_version_note' =>
        'A versão mínima recomendada para o projeto é 8.0.',
    'check_item_extension' => 'Extensão PHP: {0}',
    'check_note_sqlsrv' => 'Crítico: Essencial para conectar ao SQL Server.',
    'check_note_intl' =>
        'Crítico: Requerido pelo CodeIgniter 4 para internacionalização.',
    'check_note_mbstring' =>
        'Crítico: Essencial para manipulação de strings multibyte.',
    'check_note_json' => 'Crítico: Essencial para as respostas da API.',
    'check_note_xml' =>
        'Importante: Necessário para a funcionalidade de Plano de Execução.',
    'check_enabled' => 'Habilitada',
    'check_not_found' => 'Não encontrada',
    'check_writable_folder' => 'Permissão de escrita na pasta "writable"',
    'check_writable' => 'Gravável',
    'check_not_writable' => 'Não gravável',
    'check_writable_note' =>
        'O CodeIgniter precisa de permissão para escrever logs, cache e sessões.',
    'check_env_file' => 'Arquivo de ambiente ".env"',
    'check_found' => 'Encontrado',
    'check_env_file_note' =>
        'Recomendado para configurar o ambiente de produção/desenvolvimento.',
    'check_go_to_app' => 'Ir para a Aplicação',
    'trust_server_certificate' =>
        'Confiar no certificado do servidor (para localhost/autoassinado)',
    'search_placeholder' => 'Buscar em {0}...',
    'no_templates' => 'Nenhum template encontrado.',

    // Traduções para Query Templates
    'query_templates' => [
        '10_performance' => [
            'title' => 'Performance',
            'scripts' => [
                '10_active_queries.sql' => [
                    'title' => 'Monitorar Queries Ativas',
                    'description' =>
                        'Mostra todas as queries sendo executadas no servidor neste exato momento.',
                ],
                '20_slowest_queries_history.sql' => [
                    'title' => 'Top 10 Queries Mais Lentas (Histórico)',
                    'description' =>
                        'Analisa o cache para encontrar as queries que mais consumiram tempo de CPU.',
                ],
                '30_wait_stats.sql' => [
                    'title' => 'Estatísticas de Espera (Wait Stats)',
                    'description' =>
                        'Mostra os principais gargalos do servidor, indicando o que o SQL Server está esperando.',
                ],
            ],
        ],
        '20_space_usage' => [
            'title' => 'Uso de Espaço',
            'scripts' => [
                '10_largest_tables.sql' => [
                    'title' => 'Listar Maiores Tabelas',
                    'description' =>
                        'Calcula e lista as tabelas do banco de dados atual, ordenadas por espaço total.',
                ],
                '20_space_by_database.sql' => [
                    'title' => 'Uso de Espaço por Banco de Dados',
                    'description' =>
                        'Mostra o tamanho total, espaço usado e livre para todos os bancos de dados.',
                ],
            ],
        ],
        '30_indexes' => [
            'title' => 'Índices',
            'scripts' => [
                '10_index_fragmentation.sql' => [
                    'title' => 'Verificar Fragmentação de Índices',
                    'description' =>
                        'Analisa e lista índices com fragmentação acima de 10%.',
                ],
                '20_unused_indexes.sql' => [
                    'title' => 'Índices Não Utilizados',
                    'description' =>
                        'Encontra índices que são mantidos (updates) mas raramente ou nunca são usados em leituras.',
                ],
                '30_missing_indexes.sql' => [
                    'title' => 'Índices Faltantes (Sugeridos)',
                    'description' =>
                        'Lista as sugestões de criação de índices que o próprio SQL Server faz.',
                ],
                '40_list_table_constraints.sql' => [
                    'title' => 'Listar Constraints da Tabela',
                    'description' =>
                        'Exibe todas as constraints (PK, FK, Unique) de uma tabela específica.',
                ],
            ],
        ],
        '40_current_activity' => [
            'title' => 'Atividade Atual',
            'scripts' => [
                '10_active_locks.sql' => [
                    'title' => 'Consultar Bloqueios (Locks) Ativos',
                    'description' =>
                        'Mostra quais processos (sessões) estão bloqueando outros processos.',
                ],
                '20_active_connections.sql' => [
                    'title' => 'Listar Conexões Ativas',
                    'description' =>
                        'Lista todas as conexões ativas no servidor, mostrando usuário, máquina e programa.',
                ],
            ],
        ],
        '50_health_and_config' => [
            'title' => 'Saúde e Configuração',
            'scripts' => [
                '10_backup_status.sql' => [
                    'title' => 'Status dos Backups',
                    'description' =>
                        'Verifica e mostra a data e o tipo do último backup para cada banco de dados.',
                ],
                '20_database_configs.sql' => [
                    'title' => 'Configurações dos Bancos',
                    'description' =>
                        'Lista configurações importantes (Recovery Model, Nível de Compatibilidade) para cada banco.',
                ],
                '30_transaction_log_vlf_analysis.sql' => [
                    'title' => 'Analisar Uso do Transaction Log (VLFs)',
                    'description' =>
                        'Verifica a saúde do Transaction Log, um fator crítico de performance.',
                ],
            ],
        ],
        '60_security' => [
            'title' => 'Segurança',
            'scripts' => [
                '10_list_sysadmin_logins.sql' => [
                    'title' => 'Listar Logins com Sysadmin',
                    'description' =>
                        'Auditoria de segurança que lista todos os logins com controle total do servidor.',
                ],
                '20_find_orphan_users.sql' => [
                    'title' => 'Encontrar Usuários Órfãos',
                    'description' =>
                        'Encontra usuários em um banco que não estão mais ligados a um login válido no servidor.',
                ],
                '30_audit_high_level_permissions.sql' => [
                    'title' => 'Auditoria de Permissões Elevadas (Banco)',
                    'description' =>
                        'Verifica usuários com permissões críticas (CONTROL, IMPERSONATE) no banco de dados atual.',
                ],
                '40_audit_server_level_permissions.sql' => [
                    'title' => 'Auditoria de Permissões Elevadas (Servidor)',
                    'description' =>
                        'Verifica logins com permissões críticas (CONTROL SERVER) a nível de servidor.',
                ],
            ],
        ],
        '70_sql_server_agent' => [
            'title' => 'SQL Server Agent',
            'scripts' => [
                '10_failed_jobs_last_24h.sql' => [
                    'title' => 'Jobs com Falha Recente',
                    'description' =>
                        'Lista todos os jobs do SQL Server Agent que falharam nas últimas 24 horas.',
                ],
                '20_currently_running_jobs.sql' => [
                    'title' => 'Jobs em Execução Agora',
                    'description' =>
                        'Mostra quais jobs do SQL Server Agent estão em execução neste exato momento.',
                ],
            ],
        ],
        '80_object_management' => [
            'title' => 'Gerenciamento de Objetos',
            'scripts' => [
                '10_check_object_dependencies.sql' => [
                    'title' => 'Verificar Dependências de Objetos',
                    'description' =>
                        'Use este script para ver quais outros objetos dependem de uma tabela ou procedure antes de alterá-la.',
                ],
            ],
        ],
    ],
];
