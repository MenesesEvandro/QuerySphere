window.refreshHistory = function () {
  $.get(site_url + 'api/history/get', (history) => {
    const $list = $('#query-history-list').empty();
    if (history?.length) {
      $.each(history, (i, query) => {
        $list.append(`
                        <li class="list-group-item list-group-item-action p-2" 
                            style="cursor:pointer; font-size: 0.8rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" 
                            title="${query}" 
                            data-query="${query}">
                            ${query}
                        </li>
                    `);
      });
    }
  });
};
