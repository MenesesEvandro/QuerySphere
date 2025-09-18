function updateChartButtonAndOptions(resultIndex) {
  if (!lastResultData?.results?.[resultIndex]) {
    $("#show-chart-btn").prop("disabled", true);
    return;
  }

  const result = lastResultData.results[resultIndex];
  let numericCols = [];
  let categoryCols = [];

  if (result.data?.length) {
    $.each(result.headers, (i, header) => {
      const value = result.data[0][header];
      if (value !== null && !isNaN(parseFloat(value)) && isFinite(value)) {
        numericCols.push(header);
      } else {
        categoryCols.push(header);
      }
    });
  }

  $("#show-chart-btn").prop(
    "disabled",
    !(numericCols.length && categoryCols.length),
  );
  $("#chart-label-col").html(
    $.map(categoryCols, (col) => `<option>${col}</option>`).join(""),
  );
  $("#chart-value-col").html(
    $.map(numericCols, (col) => `<option>${col}</option>`).join(""),
  );
}
