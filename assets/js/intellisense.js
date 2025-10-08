import CodeMirror from 'codemirror';

/**
 * @typedef {import('codemirror').Editor} Editor
 * @typedef {import('codemirror').Hints} Hints
 */

import notifier from './notifier.js';

/**
 * @typedef {import('codemirror').Editor} Editor
 * @typedef {import('codemirror').Hints} Hints
 */

/**
 * Extrai todas as tabelas e os seus aliases de uma query SQL.
 * Esta versão é robusta e lida com múltiplos JOINs e nomes complexos.
 * @param {string} text - A query SQL completa.
 * @returns {Object<string, string>} Um mapa de alias para o nome da tabela (ex: { "A": "db_sales.dbo.orders" }).
 */
function extractTablesWithAliases(text) {
  const tables = {};
  const cleanText = text
    .replace(/--.*$/gm, '')
    .replace(/\n/g, ' ')
    .replace(/\s+/g, ' ');
  const regex =
    /(?:FROM|JOIN)\s+([\[\]\w\d\._."`]+)(?:\s+(?:AS\s+)?([\w\d_]+))?/gi;
  let match;

  while ((match = regex.exec(cleanText)) !== null) {
    let tableName = match[1].replace(/[\[\]"`]/g, '');
    let alias = match[2];

    // Em vez de adivinhar o schema, usamos o nome completo que o utilizador escreveu
    let qualifiedName = tableName;

    if (
      alias &&
      ![
        'ON',
        'WHERE',
        'GROUP',
        'ORDER',
        'INNER',
        'LEFT',
        'RIGHT',
        'FULL',
      ].includes(alias.toUpperCase())
    ) {
      tables[alias] = qualifiedName;
    } else {
      const simpleTableName = tableName.split('.').pop();
      tables[simpleTableName] = qualifiedName;
    }
  }
  return tables;
}

/**
 * Função de autocompletar (hint) para SQL no CodeMirror.
 * @param {Editor} editor
 * @returns {?Hints}
 */
export function customSqlHint(editor) {
  const options = editor.getOption('hintOptions');
  const schema = options.schemaData || {};
  if (!schema.objects) return null;

  const cursor = editor.getCursor();
  const token = editor.getTokenAt(cursor);
  let currentWord = token.string;

  let suggestions = [];

  const textBeforeCursor = editor.getRange({ line: 0, ch: 0 }, cursor);
  const aliasMatch = textBeforeCursor.match(/(\w+)\.(\w*)$/);

  if (aliasMatch) {
    // Contexto: Sugerir colunas após um ponto (ex: alias.)
    const alias = aliasMatch[1];
    currentWord = aliasMatch[2];
    const tablesInQuery = extractTablesWithAliases(editor.getValue());
    const rawTableName = Object.keys(tablesInQuery).find(
      (k) => k.toLowerCase() === alias.toLowerCase()
    );

    if (rawTableName) {
      const fullTableName = tablesInQuery[rawTableName];
      // Encontra a tabela no schema global (case-insensitive)
      const matchedTableKey = Object.keys(schema.objects).find(
        (k) => k.toLowerCase() === fullTableName.toLowerCase()
      );

      if (matchedTableKey && schema.objects[matchedTableKey].columns) {
        suggestions = Object.keys(schema.objects[matchedTableKey].columns).map(
          (col) => ({
            text: col,
            displayText: `${col} (${schema.objects[matchedTableKey].columns[col]})`,
            className: 'hint-column',
          })
        );
      }
    }
  } else {
    // Contexto geral: Sugere tudo
    const tablesInQuery = extractTablesWithAliases(editor.getValue());

    // 1. Colunas das tabelas na query
    Object.values(tablesInQuery).forEach((tableName) => {
      const matchedTableKey = Object.keys(schema.objects).find(
        (k) => k.toLowerCase() === tableName.toLowerCase()
      );
      if (matchedTableKey && schema.objects[matchedTableKey].columns) {
        Object.keys(schema.objects[matchedTableKey].columns).forEach((col) => {
          suggestions.push({
            text: col,
            displayText: `${col} (${schema.objects[matchedTableKey].columns[col]})`,
            className: 'hint-column',
          });
        });
      }
    });

    // 2. Todos os objetos, schemas, bases de dados, keywords e funções
    (schema.databases || []).forEach((db) =>
      suggestions.push({ text: db, className: 'hint-database' })
    );
    Object.keys(schema.objects).forEach((objName) => {
      suggestions.push({
        text: objName,
        className: `hint-${schema.objects[objName].type || 'table'}`,
      });
    });
    (schema.keywords || []).forEach((kw) =>
      suggestions.push({ text: kw, className: 'hint-keyword' })
    );
    (schema.functions || []).forEach((fn) =>
      suggestions.push({ text: fn, className: 'hint-function' })
    );
  }

  const filteredSuggestions = suggestions.filter(
    (item) =>
      item.text && item.text.toUpperCase().startsWith(currentWord.toUpperCase())
  );

  if (filteredSuggestions.length === 0) return null;

  const uniqueSuggestions = Array.from(
    new Map(
      filteredSuggestions.map((item) => [item.text.toUpperCase(), item])
    ).values()
  );
  uniqueSuggestions.sort((a, b) => a.text.localeCompare(b.text));

  let from = CodeMirror.Pos(cursor.line, token.start);
  if (aliasMatch) {
    from = CodeMirror.Pos(cursor.line, cursor.ch - currentWord.length);
  }

  return { list: uniqueSuggestions, from: from, to: cursor };
}

/**
 * Inicializa o IntelliSense para uma aba específica.
 * @param {object} tab
 */
export function initializeIntellisense(tab) {
  $.get(site_url + 'api/intellisense', (schemaData) => {
    if (schemaData && Object.keys(schemaData).length) {
      tab.editor.getOption('hintOptions').schemaData = schemaData;
    }
  }).fail(() => {
    console.error('Falha ao carregar o esquema do IntelliSense.');
  });
}

/**
 * Força a atualização do esquema do IntelliSense.
 * @param {object} tab
 */
export function refreshIntellisense(tab) {
  if (!tab) return;
  $.get(site_url + 'api/intellisense', { clearCache: 'true' }, (schemaData) => {
    if (schemaData && Object.keys(schemaData).length) {
      tab.editor.getOption('hintOptions').schemaData = schemaData;
      if (window.notifier)
        notifier.show('Esquema do IntelliSense atualizado.', 'success');
    }
  });
}
