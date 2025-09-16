# 🚀 QuerySphere

### _Seu SQL Server e MySQL, a uma aba de distância._

Temos o prazer de apresentar uma nova abordagem para a interação com bancos de dados.

Cansado de abrir um IDE pesado apenas para rodar uma consulta rápida ou verificar a estrutura de uma tabela?

O **QuerySphere** nasceu dessa necessidade de agilidade.

Desenvolvido para desenvolvedores, DBAs e analistas de dados que valorizam o tempo e a eficiência, o **QuerySphere** é uma ferramenta web ultraleve e moderna para gerenciamento de bancos de dados SQL Server e MySQL. Ele funciona inteiramente no seu navegador, sem necessidade de instalação, contas de usuário ou um banco de dados próprio.

Conecte-se, consulte, analise e feche. Simples assim.

### O Fim da Espera: Uma Ferramenta Verdadeiramente "Stateless"

O **QuerySphere** reinventa o acesso rápido a bancos de dados. Sua arquitetura "stateless" (sem estado) significa que não há nada para configurar. Use as credenciais do seu banco de dados e comece a trabalhar em segundos. Todos os dados da sua sessão são temporários e seguros, existindo apenas enquanto você precisa deles. É a ferramenta perfeita para o "_get in, get it done, get out_".

---

## ✨ Principais Funcionalidades

O **QuerySphere** é repleto de recursos projetados para maximizar sua produtividade.

### 🗺️ Conexão Multi-BD e Exploração Inteligente

- **Conexão Instantânea:** Conecte-se de forma transparente a bancos de dados **SQL Server** e **MySQL** a partir de uma interface única e limpa.
- **Navegador de Objetos Avançado:** Explore a hierarquia do seu banco de dados com uma árvore de objetos organizada. Navegue por Tabelas, Views, Stored Procedures e Funções com busca em tempo real.
- **Seletor de Contexto de Banco de Dados:** Troque facilmente entre os bancos de dados disponíveis num servidor. Toda a ferramenta adapta-se instantaneamente ao novo contexto.

### ⚡ Editor de Consultas de Alta Produtividade

Nosso editor foi construído para fazer você escrever SQL de forma mais rápida e precisa.

- **"IntelliSense" (Autocompletar Código):** O editor conhece o schema do seu banco de dados! Pressione `Ctrl+Espaço` para autocompletar nomes de tabelas, views e colunas.
- **Múltiplos Conjuntos de Resultados e Paginação Automática:** Execute scripts complexos e veja os resultados em abas organizadas. Tabelas grandes são paginadas de forma inteligente para garantir a estabilidade.
- **Formatação de SQL e Bibliotecas de Scripts:** Formate o seu SQL com um clique. Guarde e reutilize scripts com o Histórico da Sessão, Scripts Salvos localmente e Queries Partilhadas pela equipa.

### 🔬 Análise e Manipulação de Dados

Vá além da simples consulta. Transforme dados brutos em insights e faça alterações em tempo real.

- **Edição de Dados na Grelha (Inline):** Dê um duplo clique em qualquer célula no resultado de uma consulta a uma única tabela para editar os dados diretamente. As alterações podem ser guardadas com um único clique, gerando uma instrução `UPDATE` segura automaticamente.
- **Grade de Resultados Avançada:** A tabela de resultados permite ordenação, filtro global instantâneo e filtros por coluna.
- **Exportação com Um Clique e Visualização Gráfica:** Exporte qualquer conjunto de resultados para os formatos **CSV** ou **JSON**, ou visualize-os instantaneamente com gráficos de Barras, Linhas e Pizza.
- **Análise de Plano de Execução:** Entenda como a sua consulta está a ser executada, visualizando o plano de execução gráfico (SQL Server) ou o plano em JSON (MySQL).

### 🛠️ Gestão Visual de Esquemas (CRUD)

Faça a gestão do esquema do seu banco de dados sem escrever DDL manualmente.

- **Criação de Novas Tabelas:** Uma opção "Nova Tabela" no menu de contexto abre um modal de design para definir colunas, tipos de dados, tamanhos e chaves primárias.
- **Design e Alteração de Tabelas:** Uma opção "Design" em tabelas existentes carrega a sua estrutura no modal, permitindo a adição de novas colunas.
- **Exclusão de Tabelas (`DROP`):** Uma opção segura "Excluir Tabela" no menu de contexto exige a confirmação do utilizador antes da execução.
- **Geração de Scripts de Objetos:** Clique com o botão direito num objeto para gerar instantaneamente scripts `ALTER`, `EXECUTE` ou `SHOW CREATE`.

---

### 🛡️ Arquitetura Moderna e Segura

- **Backend:** Construído sobre o robusto e performático **CodeIgniter 4** rodando em **PHP 8.x**.
- **Frontend:** Uma interface de usuário reativa e moderna, sem frameworks pesados, garantindo leveza e velocidade.
- **Segurança:** As credenciais do banco de dados nunca são expostas no navegador e são mantidas apenas na sessão do servidor durante o uso.

### 🛣️ O Futuro: v0.4 "Quasar"

O projeto está em constante evolução. A próxima grande versão, **v0.4 "Quasar"**, focará na interoperabilidade e em ferramentas avançadas, com as principais funcionalidades a incluir:

- **Suporte completo para PostgreSQL.**
- Gestão visual avançada para Índices e Estatísticas.
- Um sistema opcional de contas de utilizador para uma colaboração em equipa melhorada.
- Um Dashboard de Saúde do Servidor em tempo real.

> _Acompanhe o desenvolvimento da próxima versão:_ [_v0.4 "Quasar"_](https://github.com/MenesesEvandro/QuerySphere/tree/v0.4-quasar)

**QuerySphere** não é apenas uma ferramenta, é uma filosofia: acesso a dados de forma rápida, segura e sem complicações.

**Pronto para acelerar seu workflow com bases de dados?**
