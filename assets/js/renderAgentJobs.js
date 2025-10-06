window.renderAgentJobs = function () {
  const $container = $("#agent-jobs-container").html(
    `<div class="text-center p-3"><div class="spinner-border" role="status"><span class="visually-hidden">${LANG.loading}</span></div></div>`,
  );

  $.get(site_url + "api/agent/jobs", (jobs) => {
    $container.empty();
    if (jobs?.length) {
      const $table = $('<table class="table table-sm table-hover"></table>')
        .append(
          `<thead><tr><th>${LANG.job_name}</th><th>${LANG.status}</th><th>${LANG.last_run}</th><th>${LANG.last_run_status}</th><th>${LANG.next_run}</th><th>${LANG.actions}</th></tr></thead>`,
        )
        .append("<tbody></tbody>");

      $.each(jobs, (i, job) => {
        const status = job.enabled
          ? job.last_run_step === "Running"
            ? `<span class="badge bg-primary">${LANG.running}</span>`
            : `<span class="badge bg-success">${LANG.enabled}</span>`
          : `<span class="badge bg-secondary">${LANG.disabled}</span>`;
        const outcome =
          job.run_status === 1
            ? `<span class="text-success">${LANG.success}</span>`
            : job.run_status === 0
              ? `<span class="text-danger">${LANG.failed}</span>`
              : LANG.unknown;
        const lastRun = job.last_run_datetime
          ? new Date(job.last_run_datetime.date).toLocaleString()
          : "N/A";
        const nextRun =
          job.next_run_date && job.next_run_date !== "1900-01-01 00:00:00.000"
            ? new Date(
                job.next_run_date + " " + job.next_run_time,
              ).toLocaleString()
            : "N/A";

        $table.find("tbody").append(`
                        <tr>
                            <td><a href="#" class="view-job-history" data-job-name="${job.job_name}">${job.job_name}</a></td>
                            <td>${status}</td>
                            <td>${lastRun}</td>
                            <td>${outcome}</td>
                            <td>${nextRun}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-success start-job" data-job-name="${job.job_name}" title="${LANG.start_job}"><i class="fa fa-play"></i></button>
                                <button class="btn btn-sm btn-outline-danger stop-job" data-job-name="${job.job_name}" title="${LANG.stop_job}"><i class="fa fa-stop"></i></button>
                            </td>
                        </tr>
                    `);
      });
      $container.append($table);
    } else {
      $container.html(`<p class="text-muted p-2">${LANG.no_jobs_found}</p>`);
    }
  });
};
