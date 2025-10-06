/**
 * Gera e baixa um arquivo CSV a partir dos dados fornecidos.
 * @param {string} filename - O nome do arquivo a ser baixado.
 * @param {string[]} headers - Um array com os nomes das colunas.
 * @param {object[]} data - Um array de objetos, onde cada objeto é uma linha.
 */
window.exportToCsv = function (filename, headers, data) {
  const csvRows = [headers.join(',')]; // Cabeçalho

  // Adiciona as linhas
  data.forEach((row) => {
    const values = headers.map((header) => {
      const escaped = ('' + row[header]).replace(/"/g, '""'); // Aspas duplas
      return `"${escaped}"`;
    });
    csvRows.push(values.join(','));
  });

  const blob = new Blob(['\uFEFF' + csvRows.join('\n')], {
    type: 'text/csv;charset=utf-8;',
  }); // \uFEFF é o BOM para UTF-8
  const url = URL.createObjectURL(blob);

  const a = document.createElement('a');
  a.setAttribute('hidden', '');
  a.setAttribute('href', url);
  a.setAttribute('download', filename);
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
};

/**
 * Gera e baixa um arquivo JSON a partir dos dados fornecidos.
 * @param {string} filename - O nome do arquivo a ser baixado.
 * @param {object[]} data - Um array de objetos.
 */
window.exportToJson = function (filename, data) {
  const jsonString = JSON.stringify(data, null, 2);
  const blob = new Blob([jsonString], {
    type: 'application/json;charset=utf-8;',
  });
  const url = URL.createObjectURL(blob);

  const a = document.createElement('a');
  a.setAttribute('hidden', '');
  a.setAttribute('href', url);
  a.setAttribute('download', filename);
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
};
