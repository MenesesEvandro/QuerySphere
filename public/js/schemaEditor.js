const schemaEditor = {
  modal: new bootstrap.Modal(document.getElementById("schema-editor-modal")),
  mode: "create",
  dbName: null,
  schemaName: null,
  tableName: null,
  originalColumns: [], // Para guardar o estado inicial das colunas
  csrfTokenName: window.csrfTokenName,
  csrfTokenValue: window.csrfTokenValue,

  open: function (mode, nodeData) {
    this.mode = mode;
    this.dbName = nodeData?.db || sessionDb;
    this.schemaName =
      nodeData?.schema || (DB_TYPE === "sqlsrv" ? "dbo" : this.dbName);
    this.tableName = nodeData?.table || null;
    this.originalColumns = [];

    const form = $("#schema-editor-form")[0];
    form.reset();
    $("#columns-container").empty();
    $("#indexes-container").empty();
    $("#save-table-btn").show();
    $("#add-column-btn").show();

    if (mode === "create") {
      $("#schemaEditorModalLabel").text(LANG.new_table);
      $("#table-name").val("").prop("disabled", false);
      $("#indexes-tab").hide();
      this.addColumnRow();
    } else {
      // 'design' mode
      $("#schemaEditorModalLabel").text(
        `${LANG.design_table}: ${this.tableName}`,
      );
      $("#table-name").val(this.tableName).prop("disabled", true);
      $("#indexes-tab").show();

      const $indexColumnsSelect = $("#new-index-columns").empty();

      // Carregar Estrutura das Colunas
      $.get(
        `${site_url}api/schema/structure/${encodeURIComponent(this.dbName)}/${encodeURIComponent(this.schemaName)}/${encodeURIComponent(this.tableName)}`,
      )
        .done((columns) => {
          if (columns && columns.length > 0) {
            this.originalColumns = columns.map((c) => c.name.toLowerCase());
            columns.forEach((col) => {
              this.addColumnRow(col, true);
              // Popula o select para criar novos índices
              $indexColumnsSelect.append(
                `<option value="${col.name}">${col.name}</option>`,
              );
            });
          } else {
            this.addColumnRow();
          }
          this.loadIndexes();
        })
        .fail(() => {
          notifier.show("Error: Could not load table structure.", "error");
          this.modal.hide();
        });

      // Carregar Índices
      $.get(
        `${site_url}api/schema/indexes/${encodeURIComponent(this.dbName)}/${encodeURIComponent(this.schemaName)}/${encodeURIComponent(this.tableName)}`,
      ).done((indexes) => {
        const container = $("#indexes-container");
        if (indexes && indexes.length > 0) {
          indexes.forEach((index) => {
            const unique = index.is_unique
              ? '<i class="fa fa-check-circle text-success"></i>'
              : "";
            const row = `
                                    <tr>
                                        <td>${index.index_name}</td>
                                        <td>${index.columns}</td>
                                        <td class="text-center">${unique}</td>
                                        <td>${index.type_desc}</td>
                                    </tr>
                                `;
            container.append(row);
          });
        } else {
          container.html(
            '<tr><td colspan="4" class="text-muted">No indexes found for this table.</td></tr>',
          );
        }
      });
    }

    // Garante que o primeiro separador esteja ativo
    new bootstrap.Tab($("#columns-tab")[0]).show();
    this.modal.show();
  },

  loadIndexes: function () {
    $.get(
      `${site_url}api/schema/indexes/${encodeURIComponent(this.dbName)}/${encodeURIComponent(this.schemaName)}/${encodeURIComponent(this.tableName)}`,
    ).done((indexes) => {
      const container = $("#indexes-container").empty();
      if (indexes && indexes.length > 0) {
        indexes.forEach((index) => {
          const unique = index.is_unique
            ? '<i class="fa fa-check-circle text-success"></i>'
            : "";
          const row = `
                    <tr>
                        <td>${index.index_name}</td>
                        <td>${index.columns}</td>
                        <td class="text-center">${unique}</td>
                        <td>${index.type_desc}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger drop-index-btn" data-index-name="${index.index_name}" title="Drop Index"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>`;
          container.append(row);
        });
      } else {
        container.html(
          `<tr><td colspan="5" class="text-muted text-center">${LANG.no_indexes_found}</td></tr>`,
        );
      }
    });
  },

  addColumnRow: function (column = {}, isExisting = false) {
    const isNullable = column.nullable == 1;
    const isPk = column.is_pk == 1;
    const checked = isNullable ? "checked" : "";
    const pkChecked = isPk ? "checked" : "";
    const disabled = isExisting ? "disabled" : "";

    const newRow = `
                <tr data-existing="${isExisting}">
                    <td class="text-center align-middle"><input class="form-check-input pk-checkbox" type="checkbox" ${pkChecked} ${disabled}></td>
                    <td><input type="text" class="form-control form-control-sm" name="col_name" value="${column.name || ""}" required ${disabled}></td>
                    <td><input type="text" class="form-control form-control-sm" name="col_type" value="${column.type || (DB_TYPE === "mysql" ? "VARCHAR" : "NVARCHAR")}" required></td>
                    <td><input type="text" class="form-control form-control-sm" name="col_size" value="${column.size || "255"}" ></td>
                    <td class="text-center align-middle"><input class="form-check-input" type="checkbox" name="col_nullable" ${checked}></td>
                    <td class="text-center align-middle">
                        ${!isExisting ? '<button type="button" class="btn btn-sm btn-outline-danger remove-column-btn"><i class="fa fa-trash"></i></button>' : ""}
                    </td>
                </tr>
            `;
    $("#columns-container").append(newRow);
  },

  save: function () {
    if (this.mode === "create") {
      this.createTable();
    } else {
      this.alterTable();
    }
  },

  createTable: function () {
    const tableName = $("#table-name").val();
    if (!tableName) {
      notifier.show("Table name is required.", "warning");
      return;
    }

    const columns = [];
    let primaryKeys = [];
    $("#columns-container tr").each(function () {
      const $row = $(this);
      const colName = $row.find('input[name="col_name"]').val();
      if (colName) {
        columns.push({
          name: colName,
          type: $row.find('input[name="col_type"]').val(),
          size: $row.find('input[name="col_size"]').val(),
          nullable: $row.find('input[name="col_nullable"]').is(":checked"),
        });
        if ($row.find(".pk-checkbox").is(":checked")) {
          primaryKeys.push(colName);
        }
      }
    });

    if (columns.length === 0) {
      notifier.show("A table must have at least one column.", "warning");
      return;
    }

    const payload = {
      database: this.dbName,
      schema: this.schemaName,
      table: tableName,
      columns: columns,
      primaryKey: primaryKeys,
    };

    $.ajax({
      url: site_url + "api/schema/create",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify(payload),
      headers: { [this.csrfTokenName]: this.csrfTokenValue },
      success: (response) => {
        notifier.show(
          LANG.table_created_successfully.replace("{0}", tableName),
          "success",
        );
        this.modal.hide();
        $("#object-explorer-tree").jstree(true).refresh();
      },
      error: (xhr) => {
        const errorMsg =
          xhr.responseJSON?.messages?.error || LANG.table_creation_failed;
        notifier.show(errorMsg, "error");
      },
    });
  },

  alterTable: function () {
    const newColumns = [];
    $("#columns-container tr").each((index, row) => {
      const $row = $(row);
      if ($row.data("existing") === false) {
        const colName = $row.find('input[name="col_name"]').val();
        if (colName) {
          newColumns.push({
            name: colName,
            type: $row.find('input[name="col_type"]').val(),
            size: $row.find('input[name="col_size"]').val(),
            nullable: $row.find('input[name="col_nullable"]').is(":checked"),
          });
        }
      }
    });

    if (newColumns.length === 0) {
      notifier.show("No new columns to add.", "info");
      return;
    }

    const promises = newColumns.map((column) => {
      const payload = {
        database: this.dbName,
        schema: this.schemaName,
        table: this.tableName,
        column: column,
      };
      return $.ajax({
        url: site_url + "api/schema/add_column",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(payload),
        headers: { [this.csrfTokenName]: this.csrfTokenValue },
      });
    });

    Promise.all(promises)
      .then(() => {
        notifier.show("Table updated successfully.", "success");
        this.modal.hide();
        $("#object-explorer-tree").jstree(true).refresh();
      })
      .catch((xhr) => {
        const errorMsg =
          xhr.responseJSON?.messages?.error || "Failed to update table.";
        notifier.show(errorMsg, "error");
      });
  },
};

$(function () {
  $("#add-column-btn").on("click", () => schemaEditor.addColumnRow());
  $("#schema-editor-modal").on("click", ".remove-column-btn", function () {
    $(this).closest("tr").remove();
  });
  $("#save-table-btn").on("click", () => schemaEditor.save());
  // Manipulador para o formulário de adicionar índice
  $("#add-index-form").on("submit", function (e) {
    e.preventDefault();
    const indexName = $("#new-index-name").val();
    const columns = $("#new-index-columns").val();
    const isUnique = $("#new-index-unique").is(":checked");

    if (!indexName || !columns || columns.length === 0) {
      notifier.show("Nome do índice e colunas são obrigatórios.", "warning");
      return;
    }

    const payload = {
      database: schemaEditor.dbName,
      schema: schemaEditor.schemaName,
      table: schemaEditor.tableName,
      index_name: indexName,
      columns: columns,
      is_unique: isUnique,
    };

    $.ajax({
      url: site_url + "api/schema/create_index",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify(payload),
      headers: { [schemaEditor.csrfTokenName]: schemaEditor.csrfTokenValue },
      success: () => {
        notifier.show(
          LANG.index_created_successfully.replace("{0}", indexName),
          "success",
        );
        schemaEditor.loadIndexes();
        $("#add-index-form")[0].reset();
      },
      error: (xhr) => {
        const errorMsg =
          xhr.responseJSON?.messages?.error || LANG.index_creation_failed;
        notifier.show(errorMsg, "error");
      },
    });
  });

  // Manipulador para o botão de apagar índice
  $("#schema-editor-modal").on("click", ".drop-index-btn", async function () {
    const indexName = $(this).data("index-name");
    try {
      await showConfirmModal(LANG.confirm_drop_index.replace("{0}", indexName));

      const payload = {
        database: schemaEditor.dbName,
        schema: schemaEditor.schemaName,
        table: schemaEditor.tableName,
        index_name: indexName,
      };

      $.ajax({
        url: site_url + "api/schema/drop_index",
        method: "DELETE",
        contentType: "application/json",
        data: JSON.stringify(payload),
        headers: { [schemaEditor.csrfTokenName]: schemaEditor.csrfTokenValue },
        success: () => {
          notifier.show(
            LANG.index_dropped_successfully.replace("{0}", indexName),
            "success",
          );
          schemaEditor.loadIndexes();
        },
        error: (xhr) => {
          const errorMsg =
            xhr.responseJSON?.messages?.error || LANG.index_drop_failed;
          notifier.show(errorMsg, "error");
        },
      });
    } catch (e) {
      console.log("Drop index operation canceled.");
    }
  });
});
