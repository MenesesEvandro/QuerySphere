window.getSavedScripts = function () {
  return JSON.parse(localStorage.getItem("querysphere_scripts")) || [];
};

window.formatRunStatus = function (status) {
  const statusMap = {
    0: `<span class="badge bg-danger">${LANG.failed}</span>`,
    1: `<span class="badge bg-success">${LANG.success}</span>`,
    2: `<span class="badge bg-info">${LANG.retry}</span>`,
    3: `<span class="badge bg-warning text-dark">${LANG.canceled}</span>`,
  };
  return (
    statusMap[status] ||
    `<span class="badge bg-secondary">${LANG.unknown}</span>`
  );
};

window.formatDuration = function (duration) {
  if (!duration) return "N/A";
  const str = duration.toString().padStart(6, "0");
  return `${str.substring(0, 2)}:${str.substring(2, 4)}:${str.substring(4, 6)}`;
};

/**
 * Escapa caracteres HTML para evitar XSS.
 *
 * @param {string} str - A string a ser escapada.
 */
window.escapeHtml = function (str) {
  return str
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");
};

$(async function () {
  $("#format-sql-btn").on("click", () => {
    const activeTab = TabManager.getActiveTab();
    try {
      activeTab.editor.setValue(
        sqlFormatter.format(activeTab.editor.getValue(), {
          language: "tsql",
          tabWidth: 4,
          keywordCase: "upper",
        }),
      );
    } catch (e) {
      notifier.show(LANG.format_fail, "error");
    }
  });

  $("#templates-tab").on("click", ".load-template", function (e) {
    e.preventDefault();
    const categoryKey = $(this).data("category");
    const filename = $(this).data("filename");
    const activeTab = TabManager.getActiveTab();

    $.get(
      `${site_url}api/templates/get/${categoryKey}/${filename}`,
      (response) => {
        let finalSql = response.sql;
        const placeholders =
          finalSql.match(/'NOME_DA_SUA_TABELA'|'schema.NomeDoObjeto'/g) || [];

        if (placeholders.length) {
          const objectName = prompt(
            "Este script requer um nome de objeto (ex: dbo.MinhaTabela):",
          );
          if (objectName) {
            finalSql = finalSql.replace(
              /'NOME_DA_SUA_TABELA'|'schema.NomeDoObjeto'/g,
              objectName,
            );
          } else {
            return;
          }
        }
        activeTab.editor.setValue(finalSql);
        isTemplateQuery = true;
      },
    );
  });
});
