function initializeIntellisense() {
  $.get(site_url + "api/intellisense", (data) => {
    if (Object.keys(data).length) {
      editor.setOption("hintOptions", { tables: data });
    }
  }).fail(() => console.error(LANG.intellisense_error));
}
