window.renderSavedScripts = function () {
  const scripts = getSavedScripts();
  const $list = $('#saved-scripts-list').empty();
  if (scripts?.length) {
    $.each(scripts, (index, script) => {
      $list.append(`
                    <li class="list-group-item list-group-item-action p-2 d-flex justify-content-between align-items-center">
                        <span class="load-script" data-index="${index}" title="${script.sql}" style="cursor:pointer; font-size: 0.8rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            ${script.name}
                        </span>
                        <button class="btn btn-sm btn-outline-danger delete-script" data-index="${index}" title="Apagar Script">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </li>
                `);
    });
  } else {
    $list.append(
      '<li class="list-group-item text-muted">Nenhum script salvo.</li>'
    );
  }
};
