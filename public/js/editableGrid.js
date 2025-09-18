const editableGrid = {
  enabled: false,
  pkColumn: null,
  dbName: null,
  schemaName: null,
  tableName: null,
  changedData: {},
  csrfTokenName: window.csrfTokenName,
  csrfTokenValue: window.csrfTokenValue,

  init: function (sql, sessionDb) {
    this.reset();

    if (!sessionDb) {
      console.warn(LANG.error_no_db_selected_for_edit);
      console.warn("Editing disabled: No database selected.");
      return;
    }

    const tableInfo = this.parseTableName(sql, sessionDb);

    //console.log("Table Info Parsed:", tableInfo);

    if (!tableInfo) {
      console.warn(LANG.no_table_detected);
      return;
    }

    this.dbName = tableInfo.db;
    this.schemaName = tableInfo.schema;
    this.tableName = tableInfo.table;

    $.get(
      `${site_url}api/editor/pk/${encodeURIComponent(this.dbName)}/${encodeURIComponent(this.tableName)}`,
    )
      .done((data) => {
        this.pkColumn = data.primaryKey;
        this.enabled = true;
        console.log(
          `Editing enabled for table '${this.tableName}' with PK '${this.pkColumn}'`,
        );
      })
      .fail(() => {
        console.warn(LANG.no_primary_key);
        this.enabled = false;
      });
  },

  reset: async function () {
    if (Object.keys(this.changedData).length > 0) {
      if (!(await showConfirmModal(LANG.confirm_discard_changes))) {
        return false;
      }
    }
    this.enabled = false;
    this.pkColumn = null;
    this.dbName = null;
    this.schemaName = null;
    this.tableName = null;
    this.changedData = {};
    $("#save-changes-btn").hide();
    $(".datatable-row-changed").removeClass("datatable-row-changed");
    return true;
  },

  parseTableName: function (sql, sessionDb) {
    const cleanSql = sql.replace(/--.*$/gm, "").replace(/\s+/g, " ").trim();

    if (/JOIN\s+/i.test(cleanSql)) {
      console.warn(LANG.multiple_tables_not_supported);
      return null;
    }

    const fromMatch = /FROM\s+([^\s;]+)/i.exec(cleanSql);
    if (!fromMatch || !fromMatch[1]) {
      return null;
    }

    const fullName = fromMatch[1].replace(/[`\[\]]/g, "");
    const parts = fullName.split(".");

    let db, schema, table;

    if (DB_TYPE === "mysql") {
      table = parts[parts.length - 1];
      db = parts.length > 1 ? parts[0] : sessionDb;
      schema = db; // Em MySQL, schema e db são o mesmo.
    } else {
      // sqlsrv
      table = parts[parts.length - 1];
      schema = parts.length > 1 ? parts[parts.length - 2] : "dbo";
      db = parts.length > 2 ? parts[0] : sessionDb;
    }

    return { db, schema, table };
  },

  cellDoubleClicked: function (cell) {
    if (!this.enabled) return;
    const td = $(cell);
    if (td.find("input").length > 0) return;

    const originalValue = td.text();
    const input = $(
      '<input type="text" class="form-control form-control-sm">',
    ).val(originalValue);
    td.html(input);
    input.focus();

    const finishEditing = () => {
      const newValue = input.val();
      td.text(newValue);

      if (newValue !== originalValue) {
        const row = td.closest("tr");
        const rowData = resultsDataTable.row(row).data();
        const pkValue = rowData[this.pkColumn];
        const columnName = resultsDataTable
          .column(td.index())
          .header().textContent;

        if (columnName === this.pkColumn) {
          notifier.show(LANG.no_pk_edit, "warning");
          td.text(originalValue);
          return;
        }

        if (!this.changedData[pkValue]) {
          this.changedData[pkValue] = { changes: {} };
        }
        this.changedData[pkValue].changes[columnName] = newValue;

        row.addClass("datatable-row-changed");
        $("#save-changes-btn").show();
      }
    };

    input.on("blur", finishEditing);
    input.on("keydown", (e) => {
      if (e.key === "Enter") input.blur();
      if (e.key === "Escape") td.text(originalValue);
    });
  },

  save: function () {
    const btn = $("#save-changes-btn");
    btn
      .prop("disabled", true)
      .html(
        `<span class="spinner-border spinner-border-sm"></span> ${LANG.saving_changes}`,
      );

    const promises = Object.entries(this.changedData).map(([pkValue, data]) => {
      const payload = {
        [csrfTokenName]: csrfTokenValue,
        database: this.dbName,
        schema: this.schemaName,
        table: this.tableName,
        pkColumn: this.pkColumn,
        pkValue: pkValue,
        changes: data.changes,
      };

      return $.post(site_url + "api/editor/update", payload);
    });

    Promise.all(promises)
      .then(() => {
        notifier.show(LANG.data_saved, "success");
        this.changedData = {};
        $("#save-changes-btn").hide();
        $(".datatable-row-changed").removeClass("datatable-row-changed");
      })
      .catch((err) => {
        const errorMsg = err.responseJSON?.messages?.error || LANG.error_saving;
        notifier.show(errorMsg, "error");
      })
      .finally(() => {
        btn
          .prop("disabled", false)
          .html(`<i class="fa fa-save me-1"></i> ${LANG.save_changes}`);
      });
  },
};
