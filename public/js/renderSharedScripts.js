const csrfTokenName = window.csrfTokenName;
const csrfTokenValue = window.csrfTokenValue;

function renderSharedScripts() {
  const $list = $("#shared-scripts-list").html(
    `<li class="list-group-item text-muted">${LANG.loading}</li>`,
  );
  $.get(site_url + "api/shared-queries", (scripts) => {
    $list.empty();
    if (scripts?.length) {
      $.each(scripts, (i, script) => {
        const itemDate = new Date(script.timestamp).toLocaleDateString("pt-BR");
        $list.append(`
                        <li class="list-group-item list-group-item-action p-2">
                            <div class="d-flex w-100 justify-content-between">
                                <span class="load-shared-script" data-sql="${script.sql}" style="cursor:pointer; font-weight: 500;">
                                    ${$("<div>").text(script.name).html()}
                                </span>
                                <button class="btn btn-sm btn-outline-danger delete-shared-script" data-id="${script.id}" title="Apagar Script">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                            <small class="text-muted">Por: ${$("<div>").text(script.author).html()} em ${itemDate}</small>
                        </li>
                    `);
      });
    } else {
      $list.append(
        '<li class="list-group-item text-muted">Nenhuma query compartilhada.</li>',
      );
    }
  });
}

$(async function () {
  $("#shared-scripts-list").on("click", ".load-shared-script", function () {
    const activeTab = TabManager.getActiveTab();
    activeTab.editor.setValue($(this).data("sql"));
  });

  $("#shared-scripts-list").on(
    "click",
    ".delete-shared-script",
    async function () {
      const queryId = $(this).data("id");
      try {
        await showConfirmModal(LANG.confirm_delete_shared);
        $.ajax({
          url: `${site_url}api/shared-queries/${queryId}`,
          method: "DELETE",
          headers: { [csrfTokenName]: csrfTokenValue },
          success: () => {
            notifier.show(LANG.delete_shared_success, "success");
            renderSharedScripts();
          },
          error: () => notifier.show(LANG.delete_shared_fail, "error"),
        });
      } catch (e) {
        console.log("Delete shared query operation canceled.");
      }
    },
  );
});
