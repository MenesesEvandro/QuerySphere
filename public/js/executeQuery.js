async function executeQuery(sql, page = 1) {
  const csrfTokenName = window.csrfTokenName;
  const csrfTokenValue = window.csrfTokenValue;

  if (typeof editableGrid !== "undefined" && !editableGrid.reset()) {
    return; // Aborta a execução se o utilizador cancelar
  }

  currentSql = sql;
  const $btn = $("#execute-query-btn")
    .prop("disabled", true)
    .html(
      `<span class="spinner-border spinner-border-sm"></span> ${LANG.executing}`,
    );

  $("#export-csv-btn, #export-json-btn, #show-chart-btn").prop(
    "disabled",
    true,
  );
  $("#pagination-controls").hide();

  if (resultsDataTable) resultsDataTable.destroy();
  $("#resultsTab .dynamic-tab, #resultsTabContent .dynamic-tab-pane").remove();
  $("#results-placeholder").hide();
  $("#messages-content").empty();

  $.ajax({
    url: site_url + "api/query/execute",
    method: "POST",
    data: {
      sql: sql,
      page: page,
      [csrfTokenName]: csrfTokenValue,
    },
    success: (response) => {
      $("#messages-content").html(
        `<pre class="p-2 m-0">${response.message}</pre>`,
      );
      refreshHistory();

      if (!response.results?.length) {
        $("#results-placeholder").html(LANG.no_results_found).show();
        new bootstrap.Tab($("#messages-tab")[0]).show();
        return;
      }

      lastResultData = response;
      $("#export-csv-btn, #export-json-btn").prop("disabled", false);

      $.each(response.results, (index, result) => {
        const tabId = `result-tab-${index}`;
        const paneId = `result-pane-${index}`;
        const tableId = `result-table-${index}`;

        $("#resultsTab").prepend(`
                        <li class="nav-item dynamic-tab" role="presentation">
                            <button class="nav-link" id="${tabId}" data-bs-toggle="tab" data-bs-target="#${paneId}" type="button" role="tab">
                                ${LANG.result} ${index + 1} <span class="badge bg-secondary ms-1">${result.rowCount}</span>
                            </button>
                        </li>
                    `);

        $("#resultsTabContent").append(`
                        <div class="tab-pane fade dynamic-tab-pane" id="${paneId}" role="tabpanel">
                            <div id="results-table-container-${index}" class="table-responsive h-100"></div>
                        </div>
                    `);

        if (result.data?.length) {
          const $table = $(
            `<table id="${tableId}" class="table table-sm table-bordered table-striped table-hover" style="width:100%"></table>`,
          );
          const $thead = $("<thead><tr></tr></thead>");
          const $tfoot = $("<tfoot><tr></tr></tfoot>");
          $.each(result.headers, (i, h) => {
            $thead.find("tr").append(`<th>${h}</th>`);
            $tfoot.find("tr").append(`<th>${h}</th>`);
          });
          $table.append($thead).append($tfoot);
          $(`#results-table-container-${index}`).append($table);

          resultsDataTable = new DataTable(`#${tableId}`, {
            data: result.data,
            columns: $.map(result.headers, (h) => ({ data: h })),
            destroy: true,
            searching: true,
            initComplete: function () {
              this.api()
                .columns()
                .every(function () {
                  const column = this;
                  const title = column.footer().textContent;
                  $(column.footer())
                    .html(
                      `<input type="text" class="form-control form-control-sm" placeholder="${LANG.search_placeholder.replace("{0}", title)}" />`,
                    )
                    .on("keyup change clear", function () {
                      if (column.search() !== this.value) {
                        column.search(this.value).draw();
                      }
                    });
                });
            },
          });
        } else {
          $(`#results-table-container-${index}`).html(
            `<p class="p-2 text-muted">${LANG.empty_result}</p>`,
          );
        }
      });

      const firstResult = response.results[0];
      if (firstResult.hasOwnProperty("totalRows")) {
        $("#pagination-controls").css("display", "flex");
        $("#pagination-info").text(
          `${LANG.page} ${firstResult.currentPage} ${LANG.of} ${firstResult.totalPages} (${firstResult.totalRows} ${LANG.records})`,
        );
        $("#pagination-prev").prop("disabled", firstResult.currentPage <= 1);
        $("#pagination-next").prop(
          "disabled",
          firstResult.currentPage >= firstResult.totalPages,
        );
      }

      if ($("#result-tab-0").length) {
        new bootstrap.Tab($("#result-tab-0")[0]).show();
      }
      updateChartButtonAndOptions(0);

      if (
        response.results.length === 1 &&
        response.results[0].data.length > 0
      ) {
        if (typeof editableGrid !== "undefined") {
          editableGrid.init(sql, "<?= $db_database ?>");
        }
      }
    },
    error: (xhr) => {
      const errorMsg =
        xhr.responseJSON?.messages?.error?.message ||
        xhr.responseText ||
        "Ocorreu um erro.";
      $("#messages-content").html(`
                    <div class="alert alert-danger m-2">
                        <h5 class="alert-heading">${LANG.exec_error}</h5>
                        <hr>
                        <p class="mb-0">${$("<div>").text(errorMsg).html()}</p>
                    </div>
                `);
      new bootstrap.Tab($("#messages-tab")[0]).show();
    },
    complete: () =>
      $btn
        .prop("disabled", false)
        .html(`<i class="fa fa-play me-1"></i> ${LANG.execute} (Ctrl+Enter)`),
  });
}

$(async function () {
  $("#execute-query-btn").on("click", () => {
    executeQuery(editor.getSelection() || editor.getValue(), 1);
  });

  $("#resultsTab").on("shown.bs.tab", "button.dynamic-tab", function (event) {
    updateChartButtonAndOptions(parseInt(event.target.id.split("-")[2]));
  });

  $("#pagination-prev").on("click", () => {
    const result = lastResultData.results[0];
    if (result && result.currentPage > 1) {
      executeQuery(currentSql, result.currentPage - 1);
    }
  });

  $("#pagination-next").on("click", () => {
    const result = lastResultData.results[0];
    if (result && result.currentPage < result.totalPages) {
      executeQuery(currentSql, result.currentPage + 1);
    }
  });

  $("#explain-query-btn").on("click", function () {
    const sql = editor.getSelection() || editor.getValue();
    if (!sql.trim()) return;

    const $btn = $(this)
      .prop("disabled", true)
      .html('<span class="spinner-border spinner-border-sm"></span>');

    $.ajax({
      url: site_url + "api/query/explain",
      method: "POST",
      data: {
        sql: sql,
        [csrfTokenName]: csrfTokenValue,
      },
      success: (response) => {
        const $planContainer = $("#execution-plan").empty();
        if (response.db_type === "mysql") {
          $planContainer.append(
            $(
              '<pre style="white-space: pre-wrap; word-wrap: break-word;"></pre>',
            ).text(JSON.stringify(JSON.parse(response.plan), null, 2)),
          );
        } else {
          QP.showPlan($planContainer.get(0), response.plan);
        }
        new bootstrap.Tab($("#plan-tab")[0]).show();
      },
      error: (xhr) => {
        const errorMsg =
          xhr.responseJSON?.messages?.error?.message ||
          xhr.responseText ||
          "Ocorreu um erro.";
        $("#messages-content").html(`
                    <div class="alert alert-danger m-2">
                        <h5 class="alert-heading">${LANG.exec_error}</h5>
                        <hr>
                        <p class="mb-0">${$("<div>").text(errorMsg).html()}</p>
                    </div>
                `);
        new bootstrap.Tab($("#messages-tab")[0]).show();
      },
      complete: () =>
        $btn.prop("disabled", false).html(
          `<i class="fa fa-sitemap me-1"></i> <?= lang(
                    'App.workspace.explain',
                ) ?>`,
        ),
    });
  });
});
