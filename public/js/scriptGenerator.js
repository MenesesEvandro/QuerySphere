const scriptGenerator = {
  /**
   * Gera um script SELECT TOP 1000 (SQL Server) ou LIMIT 1000 (MySQL).
   * @param {object} nodeData - Os dados do nó da árvore.
   */
  selectTop1000: function (nodeData) {
    const { db, schema, table } = nodeData;
    editor.setValue(`-- ${LANG.loading}...`);

    $.get(site_url + "api/objects/columns", { db, table })
      .done((columns) => {
        let sql;
        const columnList = columns?.length
          ? `\n${$.map(columns, (col) => `    ${this._quoteIdentifier(col)}`).join(",\n")}\n`
          : " * ";

        if (DB_TYPE === "mysql") {
          sql = `SELECT${columnList}FROM ${this._quoteIdentifier(db)}.${this._quoteIdentifier(table)}\nLIMIT 1000;`;
        } else {
          // Padrão para sqlsrv
          sql = `SELECT TOP 1000${columnList}FROM ${this._quoteIdentifier(db)}.${this._quoteIdentifier(schema)}.${this._quoteIdentifier(table)}`;
        }
        editor.setValue(sql);
      })
      .fail(() => {
        const tableName =
          DB_TYPE === "mysql"
            ? `${this._quoteIdentifier(db)}.${this._quoteIdentifier(table)}`
            : `${this._quoteIdentifier(db)}.${this._quoteIdentifier(schema)}.${this._quoteIdentifier(table)}`;
        editor.setValue(
          `-- ${LANG.error_loading_definition}\n\nSELECT * FROM ${tableName} LIMIT 1000;`,
        );
      });
  },

  /**
   * Gera um script EXEC para uma procedure ou function.
   * @param {object} node - O nó completo da árvore (necessário para o ID).
   */
  execute: function (node) {
    const { db, schema, routine } = node.data;
    const routineName = `${this._quoteIdentifier(db)}.${this._quoteIdentifier(schema)}.${this._quoteIdentifier(routine)}`;

    $.get(site_url + "api/objects/children", { id: node.id }, (params) => {
      let script = `EXEC ${routineName}\n`;
      if (params?.length && params[0].id) {
        script += $.map(params, (p) => `    ${p.text.split(" ")[0]} = ?`).join(
          ",\n",
        );
      }
      editor.setValue(script);
    });
  },

  /**
   * Obtém o script ALTER para um objeto.
   * @param {object} nodeData - Os dados do nó da árvore.
   */
  alter: function (nodeData) {
    const { db, schema, routine, type, table } = nodeData;
    const objectName = routine || table; // Pega o nome do objeto (seja rotina ou tabela)
    const objectFullName =
      DB_TYPE === "mysql" ? `${objectName}` : `${schema}.${objectName}`;

    editor.setValue(
      `-- ${LANG.loading_definition_for.replace("{0}", objectFullName)}`,
    );

    $.get(site_url + "api/objects/source", {
      db,
      schema,
      object: objectName,
      type,
    })
      .done((data) => {
        let script = data.sql;
        // A API já retorna o script ALTER, mas podemos garantir aqui
        if (
          DB_TYPE !== "mysql" &&
          script.trim().toUpperCase().startsWith("CREATE")
        ) {
          script = script.replace(/CREATE/i, "ALTER");
        }
        editor.setValue(script);
      })
      .fail(() => editor.setValue(`-- ${LANG.error_loading_definition}`));
  },

  /**
   * Coloca o identificador entre aspas corretas para o SGBD.
   * @param {string} identifier - O nome do objeto (tabela, coluna, etc.).
   */
  _quoteIdentifier: function (identifier) {
    if (!identifier) return "";
    if (DB_TYPE === "mysql") {
      return `\`${identifier}\``;
    }
    // Padrão para sqlsrv
    return `[${identifier}]`;
  },
};
