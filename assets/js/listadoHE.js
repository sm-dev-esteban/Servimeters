$(document).ready(async function () {
    config = await loadConfig();

    $("#listHE").DataTable($.extend(datatableParams, {
        "processing": true,
        "severSide": true,
        "order": [[0, "desc"]],
        "ajax": "../controller/ssp.controller.php?ssp=listEstadoHe"
    })).buttons().container().appendTo($('.col-sm-6:eq(0)'));

    $(`[data-action="print"]`).on("click", function () {
        wPrint("#viewDetail .modal-body")
    });

});

function showinfo(i) {
    $("#viewDetail .modal-title").text(`Detalle #${i}`);
}