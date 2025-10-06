window.renderMySqlEvents = function () {
  const $container = $('#mysql-events-container').html(
    `<div class="text-center p-3"><div class="spinner-border" role="status"><span class="visually-hidden">${LANG.loading}</span></div></div>`
  );

  $.get(site_url + 'api/mysql/events', (events) => {
    $container.empty();
    if (events?.length) {
      const $table = $('<table class="table table-sm table-hover"></table>')
        .append(
          `<thead><tr><th>${LANG.event_name}</th><th>${LANG.status}</th><th>${LANG.next_run}</th><th>${LANG.actions}</th></tr></thead>`
        )
        .append('<tbody></tbody>');

      $.each(events, (i, event) => {
        const status =
          event.status === 'ENABLED'
            ? `<span class="badge bg-success">${LANG.enabled}</span>`
            : `<span class="badge bg-secondary">${LANG.general.disabled}</span>`;
        const nextRun = event.next_execution
          ? new Date(event.next_execution).toLocaleString()
          : 'N/A';
        const toggleAction =
          event.status === 'ENABLED' ? LANG.disable : LANG.enable;
        const toggleIcon =
          event.status === 'ENABLED' ? 'fa-stop-circle' : 'fa-play-circle';
        const toggleTitle =
          event.status === 'ENABLED' ? LANG.disable_event : LANG.enable_event;

        $table.find('tbody').append(`
                        <tr>
                            <td><a href="#" class="view-event-definition" data-event-name="${event.name}">${event.name}</a></td>
                            <td>${status}</td>
                            <td>${nextRun}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary toggle-event-status" 
                                    data-event-name="${event.name}" 
                                    data-status="${toggleAction}" 
                                    title="${toggleTitle}">
                                    <i class="fa ${toggleIcon}"></i>
                                </button>
                            </td>
                        </tr>
                    `);
      });
      $container.append($table);
    } else {
      $container.html(`<p class="text-muted p-2">${LANG.no_events_found}</p>`);
    }
  });
};

$(async function () {
  $('body').on('click', '.toggle-event-status', function () {
    const eventName = $(this).data('event-name');
    const status = $(this).data('status');

    $.post(site_url + 'api/mysql/events/toggle', {
      event_name: eventName,
      status: status,
      [csrfTokenName]: csrfTokenValue,
    })
      .done(() => renderMySqlEvents())
      .fail(() =>
        notifier.show(`Failed to update event ${eventName}`, 'error')
      );
  });

  $('body').on('click', '.view-event-definition', function (e) {
    e.preventDefault();
    const eventName = $(this).data('event-name');
    const activeTab = TabManager.getActiveTab();
    activeTab.editor.setValue(
      `-- Loading definition for event ${eventName}...`
    );

    $.get(
      `${site_url}api/mysql/events/definition'/${encodeURIComponent(eventName)}`,
      (response) => {
        activeTab.editor.setValue(
          response.sql ||
            `-- Could not retrieve definition for event ${eventName}.`
        );
      }
    ).fail(() =>
      activeTab.editor.setValue(
        `-- Error loading definition for event ${eventName}.`
      )
    );
  });
});
