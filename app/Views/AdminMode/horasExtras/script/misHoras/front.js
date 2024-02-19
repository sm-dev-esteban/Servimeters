$(document).ready(async () => {
    const showTimeline = (e) => {
        const $modal = $(e.target)
        const $btn = $(e.relatedTarget)
        const id = $btn.data("id")
        $.ajax(`${URL_BACKEND}?action=showTimeline`, {
            type: "POST",
            dataType: "HTML",
            data: { id: id },
            success: (response) => $modal.find(".modal-body").html(response)
        })
    }, showReport = (e) => {
        const $modal = $(e.target)
        const $btn = $(e.relatedTarget)
        const id = $btn.data("id")
        $.ajax(`${URL_BACKEND}?action=showReport`, {
            type: "POST",
            dataType: "HTML",
            data: { id: id },
            success: (response) => $modal.find(".modal-body").html(response)
        })
    }

    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/app/Views/AdminMode/horasExtras/script/misHoras/back.php`

    // datatable
    const $table = $(`table[data-action]`)
    const tableAction = $table.data("action")

    const dataTable = $table.DataTable($.extend(DATATABLE_ALL, {
        processing: true,
        serverSide: true,
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        ajax: `${URL_BACKEND}?action=${tableAction}`
    }))

    $('.dataTables_filter').remove();

    $(`.content .card-header input[type="search"]`).on(`input`, (e) => dataTable.search(e.target.value).draw())
    $(`button[data-action="refresh"]`).on(`click`, () => updateDatatable($table))

    // modal
    const $modalTimeline = $(`#modal-timeline`)
    const $modalReport = $(`#modal-report`)

    $modalTimeline.on("show.bs.modal", showTimeline)
    $modalReport.on("show.bs.modal", showReport)
})