$(document).ready(async () => {
    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/app/Views/AdminMode/script/index/back.php`

    const chartJS = new ChartJS("#line-chart")

    chartJS.setLabels(data.labels || null)
    chartJS.setDatasets(data.datasets || null)

    chartJS.createChart("line")

    const $table = $(`#table-users`)

    const dataTable = $table.DataTable($.extend(DATATABLE_ALL, {
        processing: true,
        serverSide: true,
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        ajax: `${URL_BACKEND}?action=sspAprov`
    }))


    $('.dataTables_filter').remove();

    $(`.content .card-header input[type="search"]`).on(`input`, (e) => dataTable.search(e.target.value).draw())
})
