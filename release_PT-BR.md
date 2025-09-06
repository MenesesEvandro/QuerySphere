# 🚀 Lançamento: QuerySphere v0.1 "Orion"

**Data do Release:** 06 de Setembro de 2025

Anunciamos o lançamento da primeira versão estável do **QuerySphere**! Este projeto nasceu com a missão de eliminar o atrito e a espera no acesso a bancos de dados SQL Server para as tarefas do dia a dia. O "Orion", nosso codinome para a v0.1, representa um navegador estelar para os seus dados, oferecendo agilidade e precisão.

O QuerySphere é uma IDE web "stateless", que não requer instalação, contas de usuário ou um banco de dados próprio. 

Conecte-se, trabalhe e desconecte, com toda a segurança e velocidade que uma ferramenta moderna pode oferecer.

---

## ✨ O que há nesta versão?

A versão 0.1 "Orion" é o resultado de um intenso ciclo de desenvolvimento focado em criar uma experiência de consulta e análise de dados rica em funcionalidades e de alta performance.

### 🌐 Conexão e Exploração
- **Conexão Direta e Rápida:** Conecte-se a qualquer instância SQL Server diretamente do navegador.
- **Lembrar Conexão:** Opção para salvar os dados de conexão (host, porta, usuário, banco) de forma segura no navegador para agilizar futuros logins.
- **Seletor de Banco de Dados Ativo:** Troque o contexto do banco de dados em tempo real com um dropdown na interface. Toda a aplicação, incluindo o IntelliSense e o Navegador de Objetos, se adapta instantaneamente.
- **Navegador de Objetos Avançado:** Explore Bancos de Dados, Tabelas, Views, Stored Procedures e Funções em uma árvore organizada e com busca em tempo real.

### ⚡ Editor de Alta Produtividade
- **IntelliSense (Autocompletar):** O editor agora sugere nomes de tabelas, schemas e colunas (`Ctrl+Espaço`), acelerando drasticamente a escrita de queries.
- **Painéis Redimensionáveis:** Ajuste o layout da forma que preferir, arrastando as divisórias entre os painéis de objetos, editor e resultados.
- **Formatação de SQL:** Um clique para transformar seu código em um script limpo, formatado e profissional.

### 🔬 Análise e Gerenciamento de Dados
- **Múltiplos Conjuntos de Resultados:** Execute scripts com vários `SELECT`s e visualize cada resultado em uma aba dedicada.
- **Paginação do Lado do Servidor:** Execute `SELECT *` em tabelas com milhões de linhas sem medo. Os resultados são trazidos em páginas de 1000 registros, garantindo performance máxima.
- **Grade de Resultados Avançada:** A visualização dos dados agora conta com ordenação e filtros por coluna, permitindo refinar sua análise diretamente na interface.
- **Plano de Execução Gráfico:** Entenda a performance de suas queries com um visualizador gráfico do plano de execução, integrado e fácil de usar.
- **Geração de Gráficos:** Transforme seus dados em insights visuais. Gere gráficos de barra, linha ou pizza a partir dos resultados da sua query.
- **Exportação Rápida:** Exporte os dados da aba de resultado ativa para **CSV** ou **JSON** instantaneamente, com a geração do arquivo feita diretamente no navegador.
- **Gerenciamento de Procedures:** Clique com o botão direito em uma procedure ou função para gerar scripts `ALTER` ou `EXECUTE` automaticamente.

### 🎨 Experiência do Usuário e Colaboração
- **Tema Claro e Escuro:** Alterne entre os temas com um clique. Sua preferência é salva para a próxima sessão.
- **Bibliotecas de Scripts:**
    - **Histórico:** Acesse o histórico de queries da sua sessão.
    - **Salvos:** Salve scripts recorrentes no seu navegador.
    - **Compartilhados:** Uma biblioteca de scripts central para toda a equipe, gerenciada por um simples arquivo no servidor.
- **Internacionalização Completa:** Interface disponível em Português (pt-BR) e Inglês (en-US).

---

## 🏁 Como Começar

1.  Garanta que seu ambiente atende aos pré-requisitos acessando a rota de verificação: `http://localhost:8080/check`.
2.  Acesse a aplicação na raiz: `http://localhost:8080/`.
3.  Conecte-se e comece a explorar!

## 🛣️ Próximos Passos

O desenvolvimento do QuerySphere continua! Nossa prioridade para as próximas versões inclui:
- Gerenciamento completo de objetos (CRUD para tabelas, views, etc.).
- Edição de dados diretamente na grade de resultados (Inline Editing).
- Um modo "com estado" opcional com contas de usuário e perfis de conexão salvos.

Agradecemos por acompanhar esta jornada de desenvolvimento.