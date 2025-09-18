$("#agent-jobs-container").on("click", ".start-job", function () {
  const csrfTokenName = window.csrfTokenName;
  const csrfTokenValue = window.csrfTokenValue;
  const jobName = $(this).data("job-name");
  $.post(site_url + "api/agent/start", {
    job_name: jobName,
    [csrfTokenName]: csrfTokenValue,
  })
    .done(() => {
      notifier.show(`${jobName}: ${LANG.job_started}`, "success");
      renderAgentJobs();
    })
    .fail(() => notifier.show(`${jobName}: ${LANG.job_start_failed}`, "error"));
});

$("#agent-jobs-container").on("click", ".stop-job", function () {
  const jobName = $(this).data("job-name");
  $.post(site_url + "api/agent/stop", {
    job_name: jobName,
    [csrfTokenName]: csrfTokenValue,
  })
    .done(() => {
      notifier.show(`${jobName}: ${LANG.job_stopped}`, "success");
      renderAgentJobs();
    })
    .fail(() => notifier.show(`${jobName}: ${LANG.job_stop_failed}`, "error"));
});

$("#agent-jobs-container").on("click", ".view-job-history", function (e) {
  e.preventDefault();
  displayJobHistory($(this).data("job-name"));
});
