$(document).ready(async function () {
    config = await loadConfig();

    $("#listHE").DataTable($.extend(datatableParams, {
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        "ajax": "../controller/Datatable.controller.php?ssp=listEstadoHe"
    })).buttons().container().appendTo($('.col-sm-6:eq(0)'));

    $(`[data-action="qrcode"]`).on("click", function () {
        mail = $(`[data-report="mail"]`).text().split(" ")[1];
        $modalTitle = `${$("#detail-title").html().replace(" ", "")}.html`;
        $("#viewQrcode").modal("show").find("[data-show-qr]").html(
            generateQR(
                `${config.URL_SITE}files/${mail}/${encodeURIComponent($modalTitle)}`,
                {
                    width: 200,
                    height: 200
                }
            )
        );
    });

    $(`[data-action="print"]`).on("click", function () {
        mail = $(`[data-report="mail"]`).text().split(" ")[1];
        wPrint("#viewDetail .modal-body", {
            createFile: true,
            folder: mail,
            filename: $("#detail-title").html()
        });
    });

    $(`[data-action="excel"]`).on("click", function () {
        xls(`[data-report="detailContent"]`, {
            title: $("#detail-title").html()
        })
    });

});

function showinfo(x) {
    $("#viewDetail .modal-content").append(`
    <div class="overlay">
        <i class="fas fa-2x fa-sync fa-spin"></i>
    </div>
    `);
    $("#viewDetail .modal-title").text(`Detalle #${x}`);
    $("#detail-title").text(`Reporte #${x}`);
    $.when(
        $.ajax(`../controller/CRUD.controller.php?action=list&model=ReportesHE&crud=getHE`, { dataType: "JSON", type: "POST", data: { "object": x } }),
        $.ajax(`../controller/CRUD.controller.php?action=list&model=ReportesHE&crud=get`, { dataType: "JSON", type: "POST", data: { "object": x } })
    ).then(function (
        response1,
        response2
    ) {
        let resp1 = response1[0];
        let resp2 = response2[0][0];
        let id_clase = automaticForm("getValueSql", [resp2.id_ceco, "id", "id_clase", "CentrosCosto"]);
        let titulo = automaticForm("getValueSql", [id_clase, "id", "titulo", "Clase"]);
        let nombreA = automaticForm("getValueSql", [resp2.id_aprobador, "id", "nombre", "Aprobadores", { "notResult": "N/A" }]);
        let clase = automaticForm("getValueSql", [id_clase, "id", "titulo", "Clase"]);
        let tbody = "";
        $(`[data-report="document"]`).html(`<b>Documento: </b>${resp2.cc}`);
        $(`[data-report="user"]`).html(`<b>Usuario: </b>${resp2.empleado}`);
        $(`[data-report="mail"]`).html(`<b>Correo: </b>${resp2.correoEmpleado}`);

        $(`[data-report="ceco"]`).html(`<b>Centro de costo: </b>${titulo}`);
        $(`[data-report="clase"]`).html(`<b>Clase: </b>${clase}`);

        $(`[data-report="proyect"]`).html(`<b>Proyecto asociado: </b>${resp2.proyecto}`);
        $(`[data-report="code"]`).html(`<b>Código: </b>${resp2.codigo.split("|/|").join(", ")}`);
        $(`[data-report="aprobador"]`).html(`<b>Aprobador: </b>${nombreA}`);

        for (data in resp1) {
            tbody += `
            <tr>
                <td>${Number(resp1[data].descuento)}</td>
                <td>${resp1[data].novedad}</td>
                <td>${Number(resp1[data].E_Diurna_Ord)}</td>
                <td>${Number(resp1[data].E_Nocturno_Ord)}</td>
                <td>${Number(resp1[data].E_Diurna_Fest)}</td>
                <td>${Number(resp1[data].E_Nocturno_Fest)}</td>
                <td>${Number(resp1[data].R_Nocturno)}</td>
                <td>${Number(resp1[data].R_Fest_Diurno)}</td>
                <td>${Number(resp1[data].R_Fest_Nocturno)}</td>
                <td>${Number(resp1[data].R_Ord_Fest_Noct)}</td>
            </tr>
            `;
        }

        tbody += `
            <tr>
                <td align="right" colspan="9"><b>Permisos y Descuentos</b></td>
                <td>${Number(resp2.totalPermisos)}</td>
            </tr>
            <tr>
                <td align="right" colspan="9"><b>Extras</b></td>
                <td>${Number(resp2.totalHorasExtras)}</td>
            </tr>
            <tr>
                <td align="right" colspan="9"><b>Recargos</b></td>
                <td>${Number(resp2.totalRecargos)}</td>
            </tr>
            <tr>
                <td align="right" colspan="9"><b>Total</b></td>
                <td>${Number(resp2.total)}</td>
            </tr>
            `;

        $(`[data-report="detailContent"] tbody`).html(tbody);
    });
    $("#viewDetail .modal-content .overlay").remove();
}