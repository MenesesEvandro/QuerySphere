/**
 * scriptGenerator
 *
 * Este módulo é responsável por gerar scripts SQL dinâmicos
 * com base no tipo de SGBD e nas ações do usuário no menu de contexto.
 */
window.scriptGenerator = {
  tabManager: null, // A instância do TabManager injetada aqui.

  /**
   * Inicializa o gerador de scripts, fornecendo a ele uma referência ao TabManager.
   * @param {object} tabManagerInstance - A instância do TabManager.
   */
  init: function (tabManagerInstance) {
    this.tabManager = tabManagerInstance;
  },

  /**
   * Gera um script SELECT TOP 1000 (SQL Server) ou LIMIT 1000 (MySQL).
   * @param {object} nodeData - Os dados do nó da árvore.
   */
  selectTop1000: function (nodeData) {
    const activeTab = this.tabManager.getActiveTab();
    if (!activeTab) {
      notifier.show('No active editor tab found.', 'error');
      return;
    }

    const { db, schema, table } = nodeData;
    activeTab.editor.setValue(`-- ${LANG.loading}...`);

    $.get(`${site_url}api/objects/columns`, { db, table })
      .done((columns) => {
        let sql;
        const columnList =
          columns?.length > 0
            ? `\n${columns
                .map((col) => `    ${this._quoteIdentifier(col)}`)
                .join(',\n')}\n`
            : ' * ';

        if (DB_TYPE === 'mysql') {
          sql = `SELECT${columnList}FROM ${this._quoteIdentifier(db)}.${this._quoteIdentifier(table)}\nLIMIT 1000;`;
        } else {
          sql = `SELECT TOP 1000${columnList}FROM ${this._quoteIdentifier(db)}.${this._quoteIdentifier(schema)}.${this._quoteIdentifier(table)}`;
        }
        activeTab.editor.setValue(sql);
      })
      .fail(() => {
        const tableName =
          DB_TYPE === 'mysql'
            ? `${this._quoteIdentifier(db)}.${this._quoteIdentifier(table)}`
            : `${this._quoteIdentifier(db)}.${this._quoteIdentifier(schema)}.${this._quoteIdentifier(table)}`;
        activeTab.editor.setValue(
          `-- ${LANG.feedback.error_loading_definition}\n\nSELECT * FROM ${tableName} LIMIT 1000;`
        );
      });
  },

  /**
   * Gera um script EXEC para uma procedure ou function.
   * @param {object} node - O nó completo da árvore (necessário para o ID).
   */
  execute: function (node) {
    const activeTab = this.tabManager.getActiveTab();
    if (!activeTab) return;

    const { db, schema, routine } = node.data;
    const routineName = `${this._quoteIdentifier(db)}.${this._quoteIdentifier(schema)}.${this._quoteIdentifier(routine)}`;

    $.get(`${site_url}api/objects/children`, { id: node.id }, (params) => {
      let script = `EXEC ${routineName}\n`;
      if (params?.length > 0 && params[0].id) {
        script += params
          .map((p) => `    ${p.text.split(' ')[0]} = ?`)
          .join(',\n');
      }
      activeTab.editor.setValue(script);
    });
  },

  /**
   * Obtém o script ALTER ou SHOW CREATE para um objeto.
   * @param {object} nodeData - Os dados do nó da árvore.
   */
  alter: function (nodeData) {
    const activeTab = this.tabManager.getActiveTab();
    if (!activeTab) return;

    const { db, schema, routine, type, table } = nodeData;
    const objectName = routine || table;
    const objectFullName =
      DB_TYPE === 'mysql' ? `${objectName}` : `${schema}.${objectName}`;

    activeTab.editor.setValue(
      `-- ${LANG.feedback.loading_definition_for.replace('{0}', objectFullName)}`
    );

    $.get(`${site_url}api/objects/source`, {
      db,
      schema,
      object: objectName,
      type,
    })
      .done((data) => {
        let script = data.sql;
        if (
          DB_TYPE !== 'mysql' &&
          script.trim().toUpperCase().startsWith('CREATE')
        ) {
          script = script.replace(/CREATE/i, 'ALTER');
        }
        activeTab.editor.setValue(script);
      })
      .fail(() =>
        activeTab.editor.setValue(
          `-- ${LANG.feedback.error_loading_definition}`
        )
      );
  },

  /**
   * Coloca o identificador entre aspas corretas para o SGBD.
   * @param {string} identifier - O nome do objeto.
   */
  _quoteIdentifier: function (identifier) {
    if (!identifier) return '';
    return DB_TYPE === 'mysql' ? `\`${identifier}\`` : `[${identifier}]`;
  },
};
