$("#query-history-list").on("click", "li", function () {
  const query = $(this).data("query");
  if (query) editor.setValue(query);
});

$("#saved-scripts-list").on("click", ".load-script", function () {
  const index = $(this).data("index");
  const scripts = getSavedScripts();
  if (scripts[index]) editor.setValue(scripts[index].sql);
});

$("#saved-scripts-list").on("click", ".delete-script", async function () {
  const index = $(this).data("index");
  const scripts = getSavedScripts();
  try {
    await showConfirmModal(
      LANG.scripts.confirm_delete_script.replace("{0}", scripts[index].name),
    );

    scripts.splice(index, 1);
    localStorage.setItem("querysphere_scripts", JSON.stringify(scripts));
    renderSavedScripts();
  } catch (e) {
    console.log("Delete script operation canceled.");
  }
});

$("#save-script-btn").on("click", () => {
  const sql = editor.getValue();
  if (!sql.trim()) return notifier.show(LANG.empty_script_alert, "error");
  const name = prompt(LANG.prompt_script_name, LANG.script_name_default);
  if (name) {
    const scripts = getSavedScripts();
    scripts.unshift({ name, sql });
    localStorage.setItem("querysphere_scripts", JSON.stringify(scripts));
    renderSavedScripts();
  }
});
