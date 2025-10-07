window.displayJobHistory = function (jobName) {
  const $tabContainer = $('#resultsTab');
  const $contentContainer = $('#resultsTabContent');
  const tabId = `job-history-tab-${jobName.replace(/\s/g, '-')}`;
  const paneId = `job-history-pane-${jobName.replace(/\s/g, '-')}`;

  $(`#${tabId}`).closest('.nav-item').remove();
  $(`#${paneId}`).remove();

  $tabContainer.append(`
            <li class="nav-item dynamic-tab" role="presentation">
                <button class="nav-link" id="${tabId}" data-bs-toggle="tab" data-bs-target="#${paneId}" type="button" role="tab">
                    History: ${$('<div>').text(jobName).html()}
                </button>
            </li>
        `);

  $contentContainer.append(`
            <div class="tab-pane fade dynamic-tab-pane" id="${paneId}" role="tabpanel">
                <div class="p-2">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        `);

  new bootstrap.Tab($(`#${tabId}`)[0]).show();

  $.get(
    `${site_url}api/agent/history/${encodeURIComponent(jobName)}`,
    (history) => {
      const $container = $(`#${paneId}`).empty();
      if (history?.length) {
        const $table = $(
          '<table class="table table-sm table-bordered table-striped"></table>'
        )
          .append(
            `<thead><tr><th>Run Datetime</th><th>Step Name</th><th>Duration</th><th>Outcome</th><th>Message</th></tr></thead>`
          )
          .append('<tbody></tbody>');

        $.each(history, (i, item) => {
          const runDateTime = item.run_datetime
            ? new Date(item.run_datetime.date).toLocaleString()
            : 'N/A';
          $table.find('tbody').append(`
                        <tr>
                            <td>${runDateTime}</td>
                            <td>${item.step_name}</td>
                            <td>${formatDuration(item.run_duration)}</td>
                            <td>${formatRunStatus(item.run_status)}</td>
                            <td><div class="dvJobHistory">${item.message}</div></td>
                        </tr>
                    `);
        });
        $container.append($table);
      } else {
        $container.html(
          `<p class="text-muted p-2">${LANG.no_history_found}</p>`
        );
      }
    }
  ).fail(() => {
    $(`#${paneId}`).html(
      `<p class="text-danger p-2">${LANG.error_retrieving_history}</p>`
    );
  });
};
