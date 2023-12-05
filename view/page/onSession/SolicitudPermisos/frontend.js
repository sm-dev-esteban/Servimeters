/*-- 2023-11-22 10:29:13 --*/

$(document).ready(async () => {
    $('#reservation').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'MM/DD/YYYY'
        }
    })

    const $form = $(`form[data-action]`)
    if ($form.length) $form.on("submit", function (e) {
        e.preventDefault()
        const $this = $(this)
        $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/SolicitudPermisos/backend.php?action=${$this.data("action")}`, {
            cache: false,
            processData: false,
            contentType: false,
            dataType: "JSON",
            type: "POST",
            data: new FormData(this),
            success: (response) => {
                console.log(response);
            }
        })
    })

    const $table = $(`table[data-action]`)
    if ($table.length) $table.DataTable($.extend(GETCONFIG("DATATABLE"), {
        "processing": true,
        "serverSide": true,
        "order": [[0, `desc`]],
        "ajax": `${GETCONFIG("SERVER_SIDE")}/View/page/onSession/SolicitudPermisos/backend.php?action=${$table.data("action")}`
    })).buttons().container().appendTo($('.col-sm-6:eq(0)'))
})