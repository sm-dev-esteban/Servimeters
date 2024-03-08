$(document).ready(async () => {
    // Funciones
    // Variables
    const alerts = new Alerts()
    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/app/Views/AdminMode/solicitudPersonal/script/solicitudes/back.php`

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
    // Ejecución
    $(`.content .card-header input[type="search"]`).on(`input`, (e) => dataTable.search(e.target.value).draw())

})