# 🚀 QuerySphere

### *Seu SQL Server, a uma aba de distância.*

Temos o prazer de apresentar uma nova abordagem para a interação com bancos de dados. 

Cansado de abrir um IDE pesado apenas para rodar uma consulta rápida ou verificar a estrutura de uma tabela? 

O **QuerySphere** nasceu dessa necessidade de agilidade.

Desenvolvido para desenvolvedores, DBAs e analistas de dados que valorizam o tempo e a eficiência, o **QuerySphere** é uma ferramenta web ultraleve e moderna para gerenciamento de bancos de dados SQL Server. Ele funciona inteiramente no seu navegador, sem necessidade de instalação, contas de usuário ou um banco de dados próprio. 

Conecte-se, consulte, analise e feche. Simples assim.

### O Fim da Espera: Uma Ferramenta Verdadeiramente "Stateless"

O **QuerySphere** reinventa o acesso rápido a bancos de dados. Sua arquitetura "stateless" (sem estado) significa que não há nada para configurar. Use as credenciais do seu banco de dados SQL Server e comece a trabalhar em segundos. Todos os dados da sua sessão são temporários e seguros, existindo apenas enquanto você precisa deles. É a ferramenta perfeita para o "*get in, get it done, get out*".

---

## ✨ Principais Funcionalidades

O **QuerySphere** é repleto de recursos projetados para maximizar sua produtividade.

### 🗺️ Conexão e Exploração Inteligente

* **Conexão Instantânea:** Uma tela de login limpa e direta. Forneça as credenciais e conecte-se instantaneamente, com a opção de salvar os dados (exceto a senha) no navegador para acessos futuros.
* **Seletor de Contexto de Banco de Dados:** Conecte-se a um servidor e troque facilmente entre os bancos de dados disponíveis através de um dropdown na interface principal. Toda a ferramenta (Navegador de Objetos, IntelliSense) se adapta instantaneamente ao novo contexto.
* **Navegador de Objetos Avançado:** Explore a hierarquia do seu banco de dados com uma árvore de objetos organizada. Navegue por Tabelas, Views, Stored Procedures e Funções.
* **Busca em Tempo Real:** Não consegue encontrar uma tabela em um banco com centenas de objetos? Use a busca integrada para filtrar a árvore em tempo real e encontrar o que você precisa em segundos.

### ⚡ Editor de Consultas de Alta Produtividade

Nosso editor foi construído para fazer você escrever SQL de forma mais rápida e precisa.

* **"IntelliSense" (Autocompletar Código):** O editor conhece o schema do seu banco de dados! Pressione `Ctrl+Espaço` para autocompletar nomes de tabelas, views e colunas, reduzindo erros e acelerando o desenvolvimento.
* **Múltiplos Conjuntos de Resultados:** Execute um script com vários `SELECT`s de uma vez. Cada resultado será exibido em sua própria aba, de forma organizada.
* **Paginação Automática de Resultados:** `SELECT *` em uma tabela com milhões de linhas? Sem problemas. O **QuerySphere** busca os dados de forma inteligente em páginas de 1000 registros, garantindo performance e estabilidade, não importa o tamanho da sua tabela.
* **Formatação de SQL:** Com um clique, transforme um SQL mal formatado em um código limpo, indentado e profissional.
* **Bibliotecas de Scripts:**
    * **Histórico da Sessão:** Revise e reutilize queries executadas na sessão atual.
    * **Scripts Salvos (Locais):** Salve seus scripts mais úteis no armazenamento do seu navegador para uso pessoal.
    * **Queries Compartilhadas (Equipe):** Contribua e utilize uma biblioteca de scripts central, compartilhada com toda a equipe, através de um simples arquivo no servidor.

### 🔬 Análise e Visualização de Dados

Vá além da simples consulta. Transforme dados brutos em insights.

* **Grade de Resultados Avançada:** A tabela de resultados é turbinada com a biblioteca DataTables.js, permitindo:
    * **Ordenação** por qualquer coluna.
    * **Filtro global** instantâneo.
    * **Filtros por coluna**, permitindo refinar sua busca diretamente na interface.
* **Exportação com Um Clique:** Exporte qualquer conjunto de resultados para os formatos **CSV** ou **JSON** diretamente do navegador.
* **Visualização Gráfica Integrada:** Após executar uma query, clique em "Visualizar Gráfico". Escolha as colunas para os eixos X e Y, selecione o tipo de gráfico (Barras, Linhas, Pizza) e veja seus dados ganharem vida instantaneamente.
* **Análise de Plano de Execução:** Entenda como o SQL Server está executando sua query. Com um clique, visualize o plano de execução gráfico para identificar gargalos e otimizar a performance.

### 🛠️ Gerenciamento e Experiência do Usuário

* **Gerenciamento de Procedures:** Clique com o botão direito em uma Stored Procedure para gerar automaticamente um script `EXECUTE` (com placeholders para os parâmetros) ou um script `ALTER`, pronto para ser editado e executado.
* **Layout Flexível e Adaptável:** Ajuste seu espaço de trabalho como preferir! Arraste as divisórias entre os painéis para dar mais espaço ao seu código ou aos seus resultados.
* **Tema Claro e Escuro:** Escolha o tema que melhor se adapta ao seu ambiente de trabalho ou preferência. Sua escolha é salva para a próxima visita.

---

### 🛡️ Arquitetura Moderna e Segura

* **Backend:** Construído sobre o robusto e performático **CodeIgniter 4** rodando em **PHP 8.x**.
* **Frontend:** Uma interface de usuário reativa e moderna, sem frameworks pesados, garantindo leveza e velocidade.
* **Segurança:** A segurança é um pilar do projeto. As credenciais do banco de dados nunca são expostas no navegador e são mantidas apenas na sessão do servidor durante o uso.

### 🛣️ O Futuro do QuerySphere

O projeto está em constante evolução. Os próximos passos incluem:
* Edição de dados diretamente na grade de resultados (Inline Editing).
* Gerenciamento de CRUD para tabelas e outros objetos.
* Suporte para outros sistemas de banco de dados, como MySQL e PostgreSQL.

**QuerySphere** não é apenas uma ferramenta, é uma filosofia: acesso a dados de forma rápida, segura e sem complicações. 

**Pronto para acelerar seu workflow com SQL Server?**