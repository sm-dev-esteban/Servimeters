$(document).ready(async function () {
    config = await loadConfig();

    fetch("../controller/CRUD.controller.php?action=listAll&model=CentroCosto&crud=get")
        .then(response => response.json())
        .then(response => { cargarLista(response, "#ceco", "id", "titulo") });

    fetch("../controller/CRUD.controller.php?action=listAll&model=Aprobador&crud=get")
        .then(response => response.json())
        .then(response => {

            cargarLista(response.filter(
                x => x.tipo.toLocaleUpperCase() == "JEFE" ? true : false
            ), "#listJefe", "id", "nombre");

            cargarLista(response.filter(
                x => x.tipo.toLocaleUpperCase() == "GERENTE" ? true : false
            ), "#listGerente", "id", "nombre");


        });

    $("#formReporte").on("submit", function (e) {
        e.preventDefault();
        let totalHorasExtras = Number($(`[name="data[totalHorasExtras]"]`).val());
        if (totalHorasExtras > config.LIMIT_HE) {
            alerts({ title: `Límite de horas extras excedido - válido ${config.LIMIT_HE}`, icon: "error" });
        } else {
            $.ajax("../controller/submit.controller.php?action=registroHE", {
                type: "POST",
                dataType: "JSON",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $(`#formReporte button:submit`).html(`
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    `).attr("disabled", true);
                },
                success: function (response) {
                    if (response.error !== undefined) {
                        alerts({ title: `Error SQL: ${response.error}`, icon: "error", duration: 10000 });
                    } else if (response.status == true) {
                        $(`[href*="estado/listEstado.view"]`).click();
                        alerts({ title: "Horas extras registradas", icon: "success", duration: 5000 });
                    } else {
                        alerts({ title: "Error al registrar las horas extras registradas, inténtalo más tarde", icon: "error" });
                    }
                }
            })
        }
    });

    $(`#listJefe, #listGerente`).on("change", function () {
        $(`[name="data[id_aprobador]"]`).val($(this).val());
    });

    $(`[name="aprobador"]`).on("change", function () {
        $val = $(this).val().toLocaleUpperCase();
        $(`[name="data[id_estado]"]`).val(
            $val == "JEFE" ? config.APROBACION_JEFE : (
                $val == "GERENTE" ? config.APROBACION_GERENTE : (
                    $val == "CONTABLE" ? config.APROBACION_CONTABLE : (
                        $val == "RH" ? config.APROBACION_RH :
                            config.EDICION
                    )
                )
            )
        );

        if ($val == "JEFE") {
            $("#listJefe").attr("required", true).removeAttr("disabled");
            $("#listGerente").attr("disabled", true).removeAttr("required").val("");
        } else if ($val == "GERENTE") {
            $("#listGerente").attr("required", true).removeAttr("disabled");
            $("#listJefe").attr("disabled", true).removeAttr("required").val("");
        }

    }).ready(function () {
        $(`[name="data[id_estado]"]`).val(config.EDICION);
    });

    $(`#addHE`).on("click", function () {
        $($("#bodyTableEdit tr")[0])
            .clone()
            .appendTo("#bodyTableEdit")
            .find("input, button").val("").removeAttr("disabled");
    });

});

function total() {
    let descuentototal = 0;
    $(`[data-he="descuento"]`).each(function () {
        descuentototal += Number($(this).val());
    });
    $(`[data-info-he="descuento"]`).html(descuentototal);
    // -----------------------------------------------------
    let EDOtotal = 0;
    $(`[data-he="EDO"]`).each(function () {
        EDOtotal += Number($(this).val());
    });
    $(`[data-codigo="EDO"]`).val(EDOtotal > 0 ? `11001` : ``);
    $(`[data-info-he="EDO"]`).html(EDOtotal);
    // -----------------------------------------------------
    let ENOtotal = 0;
    $(`[data-he="ENO"]`).each(function () {
        ENOtotal += Number($(this).val());
    });
    $(`[data-codigo="ENO"]`).val(ENOtotal > 0 ? `11002` : ``);
    $(`[data-info-he="ENO"]`).html(ENOtotal);
    // -----------------------------------------------------
    let EDFtotal = 0;
    $(`[data-he="EDF"]`).each(function () {
        EDFtotal += Number($(this).val());
    });
    $(`[data-codigo="EDF"]`).val(EDFtotal > 0 ? `11003` : ``);
    $(`[data-info-he="EDF"]`).html(EDFtotal);
    // -----------------------------------------------------
    let ENFtotal = 0;
    $(`[data-he="ENF"]`).each(function () {
        ENFtotal += Number($(this).val());
    });
    $(`[data-codigo="ENF"]`).val(ENFtotal > 0 ? `11004` : ``);
    $(`[data-info-he="ENF"]`).html(ENFtotal);
    // -----------------------------------------------------
    let RNtotal = 0;
    $(`[data-he="RN"]`).each(function () {
        RNtotal += Number($(this).val());
    });
    $(`[data-codigo="RN"]`).val(RNtotal > 0 ? `11501` : ``);
    $(`[data-info-he="RN"]`).html(RNtotal);
    // -----------------------------------------------------
    let RFDtotal = 0;
    $(`[data-he="RFD"]`).each(function () {
        RFDtotal += Number($(this).val());
    });
    $(`[data-codigo="RFD"]`).val(RFDtotal > 0 ? `11502` : ``);
    $(`[data-info-he="RFD"]`).html(RFDtotal);
    // -----------------------------------------------------
    let RFNtotal = 0;
    $(`[data-he="RFN"]`).each(function () {
        RFNtotal += Number($(this).val());
    });
    $(`[data-codigo="RFN"]`).val(RFNtotal > 0 ? `11503` : ``);
    $(`[data-info-he="RFN"]`).html(RFNtotal);
    // -----------------------------------------------------
    let ROFtotal = 0;
    $(`[data-he="ROF"]`).each(function () {
        ROFtotal += Number($(this).val());
    });
    $(`[data-codigo="ROF"]`).val(ROFtotal > 0 ? `11504` : ``);
    $(`[data-info-he="ROF"]`).html(ROFtotal);
    // -----------------------------------------------------

    let E = Number(EDOtotal + ENOtotal + EDFtotal + ENFtotal);
    let R = Number(RNtotal + RFDtotal + RFNtotal + ROFtotal);
    let Ctotal = Number(descuentototal + EDOtotal + ENOtotal + EDFtotal + ENFtotal + RNtotal + RFDtotal + RFNtotal + ROFtotal);

    // cargamos la inforamción en un input hidden y en la tabla que es la parte visual
    $(`#totalHorasExtras`).html(E).attr("class",
        E <= 19 ? `text-success` : `text-danger`
    );
    $(`[name="data[totalHorasExtras]"]`).val(E); // suma de todos los valores

    $(`#totalRecargos`).html(R);
    $(`[name="data[totalRecargos]"]`).val(R); // suma de todos los valores

    $(`#total`).html(Ctotal);
    $(`[name="data[total]"]`).val(Ctotal); // suma de todos los valores
}

function deleteT(x) {
    $(x.parentNode.parentNode).remove();
    total();
}

function fechas() {
    let f = getFechas();
    $(`[name="data[fechaInicio]"]`).val(f[0]);
    $(`[name="data[fechaFin]"]`).val(f[1]);

    if (f[0] && f[1]) {
        $(`[name="HorasExtra[fecha][]"]`).attr("min", f[0]).attr("max", f[1]);
    } else {
        $(`[name="HorasExtra[fecha][]"]`).removeAttr("min").removeAttr("max");
    }
}

function ultimoDia(Año, Mes) {
    return new Date(Año, Mes, 0).getDate();
}

function getFechas() {

    let MesR = $('#mes').val().split("-");

    if (MesR.length != 2) {
        return [``, ``];
    } else {
        let Año = MesR[0];
        let Mes = MesR[1];
        let MesA = Number(Mes - 1);
        MesA = MesA <= 9 ? `0${MesA}` : `${MesA}`;
        return [
            `${Año}-${MesA}-01`,
            `${Año}-${Mes}-${ultimoDia(Año, Mes)}`
        ];
    }
}