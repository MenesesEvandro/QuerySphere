$('#query-history-list').on('click', 'li', function () {
  const query = $(this).data('query');
  const activeTab = TabManager.getActiveTab();
  if (query) activeTab.editor.setValue(query);
});

$('#saved-scripts-list').on('click', '.load-script', function () {
  const index = $(this).data('index');
  const scripts = getSavedScripts();
  const activeTab = TabManager.getActiveTab();
  if (scripts[index]) activeTab.editor.setValue(scripts[index].sql);
});

$('#saved-scripts-list').on('click', '.delete-script', async function () {
  const index = $(this).data('index');
  const scripts = getSavedScripts();
  try {
    await showConfirmModal(
      LANG.scripts.confirm_delete_script.replace('{0}', scripts[index].name)
    );

    scripts.splice(index, 1);
    localStorage.setItem('querysphere_scripts', JSON.stringify(scripts));
    renderSavedScripts();
  } catch (e) {
    console.log('Delete script operation canceled.');
  }
});
