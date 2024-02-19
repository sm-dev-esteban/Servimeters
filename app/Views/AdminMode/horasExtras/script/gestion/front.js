$(document).ready(async () => {
    const showReport = (e) => console.log(e.target);
    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/app/Views/AdminMode/horasExtras/script/gestion/back.php`

    const $table = $(`table[data-action]`)
    const tableAction = $table.data("action")

    const $modalReport = $(`#modal-report`)

    const dataTable = $table.DataTable($.extend(DATATABLE_ALL, {
        processing: true,
        serverSide: true,
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        select: {
            style: "multi"
        },
        ajax: `${URL_BACKEND}?action=${tableAction}`
    }))

    $('.dataTables_filter').remove();
    $(`.content .card-header input[type="search"]`).on(`input`, (e) => dataTable.search(e.target.value).draw())

    $modalReport.on("show.bs.modal", showReport)
})