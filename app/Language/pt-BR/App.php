<?php
return [
    //--------------------------------------------------------------------
    // Itens Gerais da UI
    //--------------------------------------------------------------------
    'general' => [
        'submit' => 'Enviar',
        'close' => 'Fechar',
        'details' => 'Detalhes',
        'loading' => 'Carregando...',
        'page' => 'Página',
        'of' => 'de',
        'records' => 'registros',
        'result' => 'resultado',
        'actions' => 'Ações',
        'delete' => 'Apagar',
        'edit' => 'Editar',
        'previous' => 'Anterior',
        'next' => 'Próximo',
        'language' => 'Idioma',
        'theme' => 'Tema',
        'toggleTheme' => 'Alternar Tema',
        'select' => 'Selecione ...',
        'status' => 'Status',
        'unknown' => 'Desconhecido',
        'enabled' => 'Habilitada',
        'disabled' => 'Desabilitado',
        'not_found' => 'Não encontrada',
        'running' => 'Em execução',
        'success' => 'Sucesso',
        'failed' => 'Falhou',
        'canceled' => 'Cancelado',
        'retry' => 'Tentar Novamente',
        'https_warning' =>
            'Aviso: Esta página deve ser acessada via HTTPS para garantir a segurança.',
        'crypto_warning' =>
            'Erro: Este navegador não suporta a API Web Crypto. Por favor, use um navegador moderno.',
    ],

    //--------------------------------------------------------------------
    // Tela de Conexão e Gerenciamento
    //--------------------------------------------------------------------
    'connection' => [
        'title' => 'Conexão',
        'screenTitle' => 'Conecte-se ao seu SQL Server',
        'connect' => 'Conectar',
        'connecting' => 'Conectando...',
        'disconnect' => 'Desconectar',
        'connectedTo' => 'Conectado a',
        'changing' => 'Alterando ...',
        'db_type' => 'Tipo de Banco de Dados',
        'sql_server' => 'SQL Server',
        'mysql' => 'MySQL',
        'postgresql' => 'PostgreSQL (em breve)',
        'host' => 'Host (Endereço do Servidor)',
        'port' => 'Porta',
        'database' => 'Nome do Banco (Opcional)',
        'user' => 'Usuário',
        'password' => 'Senha',
        'show_password' => 'Mostrar Senha',
        'hide_password' => 'Ocultar Senha',
        'rememberConnection' => 'Salvar dados de conexão',
        'trust_cert' =>
            'Confiar no certificado do servidor (para localhost/autoassinado)',
        'new_connection' => '-- Nova Conexão --',
        'manage_connections' => 'Gerenciar Conexões',
        'select_connection' =>
            'Selecione uma conexão existente ou crie uma nova.',
        'saved_connection' => 'Conexão Salva',
        'saved_connections' => 'Conexões Salvas',
        'no_saved_connections' => 'Nenhuma conexão salva para gerenciar.',
        'connection_deleted' => 'Conexão apagada.',
        'confirm_delete_connection' =>
            'Tem certeza de que deseja apagar a conexão {0}?',
        'prompt_connection_name' => 'Digite um nome para esta nova conexão:',
        'confirm_clear_connections' =>
            'Tem certeza de que deseja apagar todas as conexões salvas?',
        'clear_saved_connections' => 'Apagar Conexões Salvas',
        'connections_cleared' => 'Todas as conexões salvas foram apagadas.',
        'clear_all_connections' => 'Apagar todas as conexões',
    ],

    //--------------------------------------------------------------------
    // Senha Mestra
    //--------------------------------------------------------------------
    'master_password' => [
        'title' => 'Senha Mestra',
        'hint' =>
            'Deve ter pelo menos 8 caracteres com letras, números e símbolos.',
        'enter' => 'Digite a Senha Mestra',
        'change' => 'Alterar Senha Mestra',
        'change_info' =>
            'Você pode alterar sua Senha Mestra aqui. Será necessário digitar a senha atual para verificação.',
        'current' => 'Senha Mestra Atual',
        'new' => 'Nova Senha Mestra',
        'confirm_new' => 'Confirmar Nova Senha Mestra',
        'no_match' => 'Nova Senha Mestra e confirmação não coincidem.',
        'changed_success' => 'Senha Mestra alterada com sucesso.',
        'incorrect' => 'Senha Mestra Atual está incorreta.',
        'invalid' =>
            'Senha Mestra inválida. Deve ter pelo menos 8 caracteres com letras, números e símbolos.',
        'new_prompt' =>
            'CRIE uma Senha Mestra para proteger suas senhas salvas.\nEsta senha NÃO será salva e será solicitada sempre que você precisar carregar uma senha.',
        'ask_prompt' =>
            'Por favor, digite sua Senha Mestra para carregar a senha.',
        'error_decrypting' =>
            'Erro ao descriptografar a senha. Senha Mestra incorreta?',
        'no_saved_passwords' => 'Nenhuma senha salva.',
        'saved_passwords' => 'Senhas Salvas',
    ],

    //--------------------------------------------------------------------
    // Área de Trabalho Principal (Editor, Resultados, Abas)
    //--------------------------------------------------------------------
    'workspace' => [
        'execute' => 'Executar',
        'executing' => 'Executando...',
        'explain' => 'Plano de Execução',
        'formatSQL' => 'Formatar SQL',
        'exportCSV' => 'Exportar CSV',
        'exportJSON' => 'Exportar JSON',
        'objects' => 'Objetos',
        'history' => 'Histórico',
        'saved' => 'Salvos',
        'shared' => 'Compartilhadas',
        'results' => 'Resultados',
        'messages' => 'Mensagens',
        'queryResultsPlaceholder' =>
            'Execute uma consulta para ver os resultados.',
        'search_placeholder' => 'Buscar em {0}...',
        'templates' => 'Templates',
    ],

    //--------------------------------------------------------------------
    // Navegador de Objetos
    //--------------------------------------------------------------------
    'objects_browser' => [
        'search' => 'Buscar objetos',
        'tables' => 'Tabelas',
        'views' => 'Views',
        'stored_procedures' => 'Procedures Armazenadas',
        'functions' => 'Funções',
        'no_parameters' => 'Sem parâmetros',
        'loading_definition_for' => 'Carregando definição para {0}...',
    ],

    //--------------------------------------------------------------------
    // Gerenciamento de Scripts (Salvar, Compartilhar)
    //--------------------------------------------------------------------
    'scripts' => [
        'save' => 'Salvar Script',
        'share' => 'Compartilhar',
        'confirm_delete' => 'Tem certeza de que deseja excluir este script?',
        'prompt_name' => 'Digite o nome do script:',
        'default_name' => 'Meu Script',
        'empty_alert' => 'Não há script para salvar.',
        'empty_shared_alert' => 'Não há script para compartilhar.',
        'prompt_shared_name' => 'Digite um nome para esta query compartilhada:',
        'shared_default_name' => 'Script Compartilhado',
        'prompt_author' => 'Seu nome:',
        'author_default' => 'Usuário',
        'share_fail' => 'Falha ao compartilhar o script.',
        'confirm_delete_shared' =>
            'Tem certeza que deseja apagar esta query compartilhada para todos?',
        'delete_shared_fail' => 'Falha ao apagar a query.',
    ],

    //--------------------------------------------------------------------
    // Gráficos e Visualização
    //--------------------------------------------------------------------
    'charts' => [
        'title' => 'Visualizar Gráfico',
        'modalTitle' => 'Visualização Gráfica',
        'type' => 'Tipo de Gráfico',
        'labelAxis' => 'Eixo X (Rótulos)',
        'valueAxis' => 'Eixo Y (Valores)',
        'generate' => 'Gerar Gráfico',
        'bar' => 'Barras',
        'line' => 'Linhas',
        'pie' => 'Pizza',
    ],

    //--------------------------------------------------------------------
    // Mensagens de Feedback (Sucesso, Erro, Avisos)
    //--------------------------------------------------------------------
    'feedback' => [
        'connection_success' => 'Conexão estabelecida com sucesso!',
        'connection_failed' => 'Falha na conexão.',
        'check_credentials' => 'Verifique o host, a porta e as credenciais.',
        'logout_success' => 'Você foi desconectado.',
        'session_lost' => 'Sessão de conexão perdida.',
        'query_empty' => 'A consulta SQL não pode estar vazia.',
        'noquery_to_export' => 'Nenhuma consulta para exportar.',
        'no_results_found' => 'Nenhum resultado encontrado.',
        'empty_result' => 'Resultado vazio.',
        'commands_executed_successfully' =>
            'Comando(s) executado(s) com sucesso.',
        'result_sets_returned' => 'Conjunto(s) de resultados retornado(s): ',
        'rows_affected' => 'Linha(s) afetada(s): ',
        'execution_time' => 'Tempo de execução',
        'syntax_error' => 'Erro de sintaxe ou execução: ',
        'unknown_error' => 'Erro desconhecido',
        'exec_error' => 'Erro na execução.',
        'format_fail' => 'Falha ao formatar o SQL. Verifique a sintaxe.',
        'execution_plan_generation_failed' =>
            'Falha na geração do plano de execução.',
        'intellisense_error' =>
            'Falha ao carregar o dicionário do IntelliSense.',
        'error_alter_database' => 'Falha ao alterar o banco de dados.',
        'error_loading_definition' =>
            'ERRO: Não foi possível carregar a definição do objeto.',
        'language_not_supported' => 'Idioma não suportado.',
        'invalid_number' => 'Número inválido.',
        'no_templates' => 'Nenhum template encontrado.',
    ],

    //--------------------------------------------------------------------
    // Validação de Requisitos do Servidor
    //--------------------------------------------------------------------
    'server_check' => [
        'title' => 'Validação dos Requisitos do Servidor',
        'trigger_button' => 'Testar Compatibilidade',
        'header_item' => 'Requisito',
        'header_status' => 'Status',
        'header_current' => 'Valor Atual',
        'header_required' => 'Requerido',
        'header_notes' => 'Observações',
        'status_ok' => 'OK',
        'status_fail' => 'FALHA',
        'ok_title' => 'Tudo Certo!',
        'ok_message' =>
            'Seu servidor atende a todos os requisitos críticos para executar a aplicação.',
        'warn_title' => 'Atenção!',
        'warn_message' =>
            'Seu servidor possui alguns avisos, mas os requisitos críticos foram atendidos. A aplicação deve funcionar, mas verifique os pontos abaixo.',
        'fail_title' => 'Problemas Encontrados!',
        'fail_message' =>
            'Seu servidor não atende a um ou mais requisitos críticos. A aplicação não funcionará corretamente até que os itens marcados com FALHA sejam corrigidos.',
        'go_to_app' => 'Ir para a Aplicação',
        'php_version' => 'Versão do PHP',
        'php_version_note' =>
            'A versão mínima recomendada para o projeto é 8.0.',
        'php_extension_item' => 'Extensão PHP: {0}',
        'note_sqlsrv' => 'Crítico: Essencial para conectar ao SQL Server.',
        'note_intl' =>
            'Crítico: Requerido pelo CodeIgniter 4 para internacionalização.',
        'note_mbstring' =>
            'Crítico: Essencial para manipulação de strings multibyte.',
        'note_json' => 'Crítico: Essencial para as respostas da API.',
        'note_xml' =>
            'Importante: Necessário para a funcionalidade de Plano de Execução.',
        'writable_folder' => 'Permissão de escrita na pasta "writable"',
        'writable' => 'Gravável',
        'not_writable' => 'Não gravável',
        'writable_note' =>
            'O CodeIgniter precisa de permissão para escrever logs, cache e sessões.',
        'env_file' => 'Arquivo de ambiente ".env"',
        'found' => 'Encontrado',
        'env_file_note' =>
            'Recomendado para configurar o ambiente de produção/desenvolvimento.',
    ],

    //--------------------------------------------------------------------
    // SQL Server Agent Jobs
    //--------------------------------------------------------------------
    'agent' => [
        'title' => 'Jobs do SQL Server Agent',
        'job_name' => 'Nome do Job',
        'last_run' => 'Última Execução',
        'last_run_status' => 'Resultado',
        'next_run' => 'Próxima Execução',
        'no_jobs_found' => 'Nenhum job do SQL Server Agent encontrado.',
        'status_success' => 'Sucesso',
        'status_failed' => 'Falhou',
        'status_running' => 'Executando',
        'status_canceled' => 'Cancelado',
        'status_retry' => 'Tentar Novamente',
        'start_job' => 'Iniciar Job',
        'stop_job' => 'Parar Job',
        'job_started' => 'Job iniciado com sucesso.',
        'job_start_failed' => 'Falha ao iniciar o job.',
        'job_stopped' => 'Job parado com sucesso.',
        'job_stop_failed' => 'Falha ao parar o job.',
        'history' => 'Histórico do Job',
        'no_history_found' => 'Nenhum histórico encontrado para este job.',
        'error_retrieving_history' => 'Erro ao recuperar o histórico do job.',
        'step_name' => 'Nome do Passo',
        'step_id' => 'ID do Passo',
        'operation' => 'Operação',
        'status_message' => 'Mensagem de Status',
        'duration' => 'Duração',
        'start_time' => 'Hora de Início',
        'end_time' => 'Hora de Término',
        'view_details' => 'Ver Detalhes',
        'hide_details' => 'Ocultar Detalhes',
        'no_details' => 'Nenhum detalhe disponível.',
    ],

    //--------------------------------------------------------------------
    // Templates de Consulta
    //--------------------------------------------------------------------
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
