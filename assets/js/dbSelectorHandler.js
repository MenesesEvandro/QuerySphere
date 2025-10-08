import notifier from './notifier.js';

$('#db-selector-list').on('click', 'a', function (e) {
  e.preventDefault();
  const dbName = $(this).data('dbname');
  const currentDbName = $('#active-database-name').text().trim();
  if (dbName && dbName !== currentDbName) {
    $('#active-database-name').text(LANG.changing);
    $.ajax({
      url: site_url + 'api/session/database',
      method: 'POST',
      data: {
        database: dbName,
        [csrfTokenName]: csrfTokenValue,
      },
      success: () => window.location.reload(),
      error: () => {
        notifier.show(LANG.error_alter_database, 'error');
        $('#active-database-name').text(currentDbName);
      },
    });
  }
});
