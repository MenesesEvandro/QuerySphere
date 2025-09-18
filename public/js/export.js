function exportToCsv(filename, headers, data) {
  const csvRows = [headers.join(",")];
  $.each(data, (i, row) => {
    const values = $.map(
      headers,
      (header) => `"${("" + row[header]).replace(/"/g, '""')}"`,
    );
    csvRows.push(values.join(","));
  });
  const blob = new Blob([csvRows.join("\n")], {
    type: "text/csv;charset=utf-8;",
  });
  const url = URL.createObjectURL(blob);

  $("<a>", { href: url, download: filename })
    .css("visibility", "hidden")
    .appendTo("body")
    .trigger("click")
    .remove();
}

function exportToJson(filename, data) {
  const blob = new Blob([JSON.stringify(data, null, 2)], {
    type: "application/json;charset=utf-8;",
  });
  const url = URL.createObjectURL(blob);

  $("<a>", { href: url, download: filename })
    .css("visibility", "hidden")
    .appendTo("body")
    .trigger("click")
    .remove();
}

$(async function () {
  $("#export-csv-btn").on("click", function () {
    if (!lastResultData) return;
    const activeTabIndex =
      $("#resultsTab button.dynamic-tab.active").attr("id")?.split("-")[2] || 0;
    const activeResult = lastResultData.results[activeTabIndex];
    if (activeResult) {
      exportToCsv(
        `export_result_${parseInt(activeTabIndex) + 1}.csv`,
        activeResult.headers,
        activeResult.data,
      );
    }
  });

  $("#export-json-btn").on("click", function () {
    if (!lastResultData) return;
    const activeTabIndex =
      $("#resultsTab button.dynamic-tab.active").attr("id")?.split("-")[2] || 0;
    const activeResult = lastResultData.results[activeTabIndex];
    if (activeResult) {
      exportToJson(
        `export_result_${parseInt(activeTabIndex) + 1}.json`,
        activeResult.data,
      );
    }
  });
});
