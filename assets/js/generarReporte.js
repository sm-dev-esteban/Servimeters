$(document).ready(async function () {
    config = await loadConfig();

    $('#formExcel').on("submit", function (e) {
        e.preventDefault();
        $form = $(this);
        $table = $("#tableExcel");
        $btn_excel = $(`[data-action="excel"]`);

        let fInicio = $('#fechaInicio').val();
        fInicio = fInicio.split("-");
        fInicio.push("01");
        fInicio = fInicio.join("-");

        let fFin = $('#fechaFin').val();
        fFin = fFin.split("-");
        fFin.push(ultimoDia(...fFin));
        fFin = fFin.join("-");

        switch ($form.data("type")) {
            case 1:
                $table.find("thead, tfoot").remove();
                $data = automaticForm("getDataSql", [
                    `
                    ReportesHE RHE
                    inner join HorasExtra HE on HE.id_reporteHE = RHE.id
                    `,
                    `RHE.fechaInicio >= '${fInicio}' and RHE.fechaFin <= '${fFin}'`,
                    `
                    RHE.codigo,
                    RHE.cc,
                    RHE.fechaInicio,
                    RHE.fechaFin,
                    RHE.total,
                    HE.E_Diurna_Ord,
                    HE.E_Nocturno_Ord,
                    HE.E_Diurna_Fest,
                    HE.E_Nocturno_Fest,
                    HE.R_Nocturno,
                    HE.R_Fest_Diurno,
                    HE.R_Fest_Nocturno,
                    HE.R_Ord_Fest_Noct
                    `,
                    {
                        "checkTableExists": false
                    }
                ]);

                $table.find("tbody").html("");
                if ($data.length && $data.length > 0) {
                    $btn_excel.removeAttr("disabled");
                    $data.forEach(e => {
                        e.codigo.split("|/|").forEach(f => {
                            t = e[columnCode(f)].split(".");
                            t_e = t[0] ?? 0
                            t_d = t[1] ?? 0
                            $table.find(`tbody`).append(`
                            <tr>
                                <td>${f}</td>
                                <td>${e.cc}</td>
                                <td>${e.fechaInicio}</td>
                                <td>${e.fechaFin}</td>
                                <td>${e.fechaFin}</td>
                                <td>Plano Horas Extras</td>
                                <td>0</td>
                                <td>4</td>
                                <td></td>
                                <td></td>
                                <td>${Number(t_e)}</td>
                                <td>${Number(t_d * 6)}</td>
                                <td>OCASIONAL</td>
                            </tr>
                            `);
                        });
                    });
                } else {
                    $btn_excel.attr("disabled", true);
                    $table.find("tbody").append(`
                    <tr>
                        <th>Sin resultados</th>
                    </tr>
                    `);
                }
                break;
            case 2:
                $table.find("tfoot").remove();
                $data = automaticForm("getDataSql", [
                    `
                    ReportesHE RHE
                    inner join HorasExtra   HE  on HE.id_reporteHE = RHE.id
                    inner join CentrosCosto CC  on CC.id = RHE.id_ceco
                    inner join Clase        C   on C.id = CC.id_clase
                    inner join Estados      E   on E.id = RHE.id_estado
                    inner join Aprobadores  A   on A.id = RHE.id_aprobador
                    `,
                    `RHE.fechaInicio >= '${fInicio}' and RHE.fechaFin <= '${fFin}'`,
                    `
                    RHE.id,
                    RHE.empleado,
                    RHE.cc,
                    RHE.cargo,
                    RHE.fechaInicio,
                    RHE.fechaFin,
                    CC.titulo ceco,
                    E.nombre estado,
                    A.nombre aprobador,

                    C.titulo clase`,
                    {
                        "checkTableExists": false
                    }
                ]);
                $table.find("thead").html(`
                <tr>
                    <th>id</th>
                    <th>empleado</th>
                    <th>cc</th>
                    <th>cargo</th>
                    <th>fechaInicio</th>
                    <th>fechaFin</th>
                    <th>CECO</th>
                    <th>Estado</th>
                    <th>Aprobador</th>
                    <th>CantidadDesc</th>
                    <th>Extras Diurn Ordinaria</th>
                    <th>Extras Noct Ordinaria</th>
                    <th>Extras Diurn Fest Domin</th>
                    <th>Extras Noct Fest Domin</th>
                    <th>Recargo Nocturno</th>
                    <th>Recargo Festivo Diurno</th>
                    <th>Recargo Festivo Noctur</th>
                    <th>Recargo Ord Fest Noct</th>
                </tr>
                `);

                $table.find("tbody").html("");
                if ($data.length && $data.length > 0) {
                    $btn_excel.removeAttr("disabled");
                    $data.forEach(e => {
                        $table.find("tbody").append(`
                        <tr>
                            <td style="width: auto">${e.id}</td>
                            <td style="width: auto">${e.empleado}</td>
                            <td style="width: auto">${e.cc}</td>
                            <td style="width: auto">${e.cargo}</td>
                            <td style="width: auto">${e.fechaInicio}</td>
                            <td style="width: auto">${e.fechaFin}</td>
                            <td style="width: auto">${e.ceco}</td>
                            <td style="width: auto">${e.estado}</td>
                            <td style="width: auto">${e.aprobador}</td>
                            <td style="width: auto">${sumaDeHoras(e.id, "descuento")}</td>
                            <td style="width: auto">${sumaDeHoras(e.id, "E_Diurna_Ord")}</td>
                            <td style="width: auto">${sumaDeHoras(e.id, "E_Nocturno_Ord")}</td>
                            <td style="width: auto">${sumaDeHoras(e.id, "E_Diurna_Fest")}</td>
                            <td style="width: auto">${sumaDeHoras(e.id, "E_Nocturno_Fest")}</td>
                            <td style="width: auto">${sumaDeHoras(e.id, "R_Nocturno")}</td>
                            <td style="width: auto">${sumaDeHoras(e.id, "R_Fest_Diurno")}</td>
                            <td style="width: auto">${sumaDeHoras(e.id, "R_Fest_Nocturno")}</td>
                            <td style="width: auto">${sumaDeHoras(e.id, "R_Ord_Fest_Noct")}</td>
                        </tr>
                        `);
                    });
                } else {
                    $btn_excel.attr("disabled", true);
                    $table.find("tbody").append(`
                    <tr>
                        <th>Sin resultados</th>
                    </tr>
                    `);
                }
                break;
            default:
                break;
        }
    });

    $(`[data-action="excel"]`).on("click", function () {
        xls(`#tableExcel`, {
            title: false
        });
    });

});

function columnCode(code) {
    return {
        11001: "E_Diurna_Ord",
        11002: "E_Nocturno_Ord",
        11003: "E_Diurna_Fest",
        11004: "E_Nocturno_Fest",
        11501: "R_Nocturno",
        11502: "R_Fest_Diurno",
        11503: "R_Fest_Nocturno",
        11504: "R_Ord_Fest_Noct"
    }[code] ?? false;
}

function sumaDeHoras(id_reporte, column) {
    return automaticForm(
        "getDataSql", [
        `HorasExtra`,
        `id_reporteHE = '${id_reporte}'`,
        `SUM(${column} + 0) count`,
    ]
    )[0]["count"] ?? 0;
}