import { showConfirmModal } from './confirmModal.js';

$(async function () {
  const csrfTokenName = window.csrfTokenName;
  const csrfTokenValue = window.csrfTokenValue;

  if ($('#object-explorer-panel').length) {
    Split(['#object-explorer-panel', '.main-panel'], {
      sizes: [20, 80],
      minSize: 280,
      gutterSize: 7,
      direction: 'horizontal',
      cursor: 'col-resize',
    });
  }

  $('#object-explorer-tree').jstree({
    core: {
      data: {
        url: (node) =>
          node.id === '#'
            ? site_url + 'api/objects/databases'
            : site_url + 'api/objects/children',
        data: (node) => ({ id: node.id }),
      },
    },
    plugins: ['contextmenu', 'search'],
    search: {
      ajax: {
        url: site_url + 'api/objects/search',
        data: (str) => ({ str }),
      },
      show_only_matches: true,
      close_opened_onclear: true,
    },
    contextmenu: {
      items: (node) => {
        const menu = {};
        const nodeData = node.data;
        if (!nodeData) return menu;

        if (
          nodeData?.type === 'database' ||
          nodeData?.type === 'folder_tables'
        ) {
          menu.createTable = {
            label: LANG.new_table,
            icon: 'fa-solid fa-plus-square',
            action: () => {
              let dbName;
              if (nodeData.type === 'database') {
                dbName = node.text;
              } else {
                const parentNode = $('#object-explorer-tree')
                  .jstree(true)
                  .get_node(node.parent);
                dbName = parentNode.text;
              }
              schemaEditor.open('create', { db: dbName });
            },
          };
        }

        if (nodeData.type === 'table' || nodeData.type === 'view') {
          menu.selectTop1000 = {
            label:
              DB_TYPE === 'mysql'
                ? 'SELECT LIMIT 1000 Rows'
                : 'SELECT TOP 1000 Rows',
            icon: 'fa-solid fa-bolt',
            action: () => scriptGenerator.selectTop1000(nodeData),
          };
        }

        if (nodeData.type === 'table') {
          menu.designTable = {
            label: LANG.design_table,
            icon: 'fa-solid fa-edit',
            action: () => schemaEditor.open('design', nodeData),
          };
          menu.viewData = {
            label: LANG.view_data,
            icon: 'fa-solid fa-search',
            _separator_before: true,
            action: () => TabManager.openDataViewerTab(nodeData),
          };
          menu.dropTable = {
            label: LANG.drop_table,
            icon: 'fa-solid fa-trash-alt',
            _separator_before: true,
            action: async () => {
              const { db, schema, table } = nodeData;
              try {
                await showConfirmModal(
                  LANG.schema_editor.confirm_drop_table.replace('{0}', table)
                );

                const payload = { database: db, schema: schema, table: table };
                $.ajax({
                  url: site_url + 'api/schema/drop',
                  method: 'DELETE',
                  contentType: 'application/json',
                  data: JSON.stringify(payload),
                  headers: { [csrfTokenName]: csrfTokenValue },
                  success: () => {
                    notifier.show(
                      `Table "${table}" dropped successfully.`,
                      'success'
                    );
                    $('#object-explorer-tree')
                      .jstree(true)
                      .refresh_node(node.parent);
                  },
                  error: (xhr) => {
                    const errorMsg =
                      xhr.responseJSON?.messages?.error ||
                      'Failed to drop table.';
                    notifier.show(errorMsg, 'error');
                  },
                });
              } catch (e) {
                console.log('Drop table operation canceled by user.');
              }
            },
          };
        }

        if (nodeData.type === 'procedure' || nodeData.type === 'function') {
          if (DB_TYPE === 'sqlsrv') {
            menu.scriptAsExecute = {
              label: LANG.script_execute,
              icon: 'fa-solid fa-play-circle',
              action: () => scriptGenerator.execute(node),
            };
          }
        }

        if (
          nodeData.type !== 'database' &&
          !nodeData.type.startsWith('folder')
        ) {
          menu.scriptAsAlterOrCreate = {
            label: DB_TYPE === 'mysql' ? LANG.script_create : LANG.script_alter,
            icon: 'fa-solid fa-pencil-alt',
            _separator_before: true,
            action: () => scriptGenerator.alter(nodeData),
          };
        }

        return menu;
      },
    },
  });

  let searchTimeout = false;
  $('#object-search-input').on('keyup', function () {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      const searchTerm = $(this).val();
      const tree = $('#object-explorer-tree').jstree(true);
      if (searchTerm.length >= 3) {
        tree.search(searchTerm);
      } else {
        tree.clear_search();
      }
    }, 300);
  });
});
