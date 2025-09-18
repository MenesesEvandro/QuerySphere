const editor = CodeMirror.fromTextArea($("#query-editor")[0], {
  lineNumbers: true,
  mode: "text/x-mssql",
  theme: "material-darker",
  indentWithTabs: true,
  smartIndent: true,
  extraKeys: {
    "Ctrl-Space": "autocomplete",
    "Alt-Space": "autocomplete",
    "Ctrl-Enter": () => $("#execute-query-btn").trigger("click"),
    F5: () => $("#execute-query-btn").trigger("click"),
  },
  hintOptions: { tables: {} },
});
editor.setSize("100%", "100%");
editor.focus();

editor.on("change", () => {
  isTemplateQuery = false;
});

$(async function () {});
