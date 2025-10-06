window.renderQueryTemplates = function () {
  const $accordion = $("#query-templates-accordion").html(
    `<div class="p-2 text-muted">${LANG.loading}</div>`,
  );
  $.get(site_url + "api/templates", (categories) => {
    $accordion.empty();
    if (categories?.length) {
      $.each(categories, (index, cat) => {
        const categoryId = `category-${index}`;
        $accordion.append(`
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-${categoryId}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-${categoryId}">
                                    ${$("<div>").text(cat.category).html()}
                                </button>
                            </h2>
                            <div id="collapse-${categoryId}" class="accordion-collapse collapse" data-bs-parent="#query-templates-accordion">
                                <div class="list-group list-group-flush">
                                    ${$.map(
                                      cat.scripts,
                                      (script) => `
                                        <a href="#" class="list-group-item list-group-item-action load-template" 
                                            data-category="${script.category_key}" 
                                            data-filename="${script.filename}" 
                                            title="${$("<div>").text(script.description).html()}">
                                            ${$("<div>").text(script.name).html()}
                                        </a>
                                    `,
                                    ).join("")}
                                </div>
                            </div>
                        </div>
                    `);
      });
    } else {
      $accordion.html(`<div class="p-2 text-muted">${LANG.no_templates}</div>`);
    }
  });
};
