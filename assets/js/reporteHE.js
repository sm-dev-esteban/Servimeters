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
    let ident = Date.now();
    let edit = sessionStorage.getItem("edit");

    sessionStorage.removeItem("edit");

    if (edit) {
        cargarDatos(edit);
    }

    $("#formReporte").createDropzone({
        url: "../controller/submit.controller.php?action=registroHE",
        table: "ReportesHE",
        preview: edit ? "adjuntos" : false,
        ident: edit ? edit : ident,
        init: function () {
            var myDropzone = this;

            this.element.querySelector("button[type=submit]").addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();

                $(`#formReporte button:submit`).html(`
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                `).attr("disabled", true);

                let $date = new Date().toLocaleString(locale, { timeZone: timezone, year: 'numeric', month: '2-digit', day: '2-digit', weekday: "long", hour: '2-digit', hour12: false, minute: '2-digit', second: '2-digit' });
                let totalHorasExtras = Number($(`[name="data[totalHorasExtras]"]`).val());

                $(`[name="data[fechaRegistro]"]`).val($date);
                $(`[name="data[timezone]"]`).val(timezone);

                if (totalHorasExtras > config.LIMIT_HE) {
                    console.log("no se envia");
                    alerts({ title: `Límite de horas extras excedido - válido hasta ${config.LIMIT_HE} horas`, icon: "error" });
                } else {
                    if (myDropzone.files.length == 0) {
                        myDropzone._uploadData(
                            [
                                {
                                    upload: {
                                        filename: ''
                                    }
                                }
                            ],
                            [
                                {
                                    filename: '',
                                    name: '',
                                    data: new Blob()
                                }
                            ]
                        );
                    }
                    myDropzone.processQueue();
                }
            });

            // this.element.querySelector(`#actions${ident} .cancel`).onclick = function () {
            //     myDropzone.removeAllFiles(true);
            // }

            // this.on("totaluploadprogress", function (progress) {
            //     document.querySelector(`#total-progress${ident} .progress-bar`).style.width = `${progress}%`
            // });

            // this.on("queuecomplete", function (progress) {
            //     document.querySelector(`#total-progress${ident}`).style.opacity = "0"
            // });


            // this.on("sendingmultiple", function () { });
            this.on("successmultiple", function (files, response) {
                response = JSON.parse(response);
                if (response.error) {
                    alerts({ title: `Error SQL: ${response.error}`, icon: "error", duration: 10000 });
                } else if (response.status == true) {
                    $(`[href*="estado/listEstado.view"]`).click();
                    alerts({ title: "Horas extras registradas", icon: "success", duration: 5000 });
                } else {
                    alerts({ title: "Error al registrar las horas extras registradas, inténtalo más tarde", icon: "error" });
                }
            });
            this.on("errormultiple", function (files, response) {
                console.log(response);
            });
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

    let P = Number(descuentototal);
    let E = Number(EDOtotal + ENOtotal + EDFtotal + ENFtotal);
    let R = Number(RNtotal + RFDtotal + RFNtotal + ROFtotal);
    let Ctotal = Number(P + E + R);

    // cargamos la inforamción en un input hidden y en la tabla que es la parte visual
    $(`#totalPermisos`).html(P);
    $(`[name="data[totalPermisos]"]`).val(P); // suma de todos los valores

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
        let AñoA = MesA == 0 ? Number(Año - 1) : Año;
        MesA = MesA == 0 ? 12 : (MesA <= 9 ? `0${MesA}` : `${MesA}`);
        return [
            `${AñoA}-${MesA}-01`,
            `${Año}-${Mes}-${ultimoDia(Año, Mes)}`
        ];
    }
}

function cargarDatos(x) {
    $.when(
        $.ajax(`../controller/CRUD.controller.php?action=list&model=ReportesHE&crud=getHE`, { dataType: "JSON", type: "POST", data: { "object": x } }),
        $.ajax(`../controller/CRUD.controller.php?action=list&model=ReportesHE&crud=get`, { dataType: "JSON", type: "POST", data: { "object": x } })
    ).then(function (
        response1,
        response2
    ) {
        // let response = response1[0].concat(response2[0]);
        // for (identN in response) {
        //     for (identL in response[identN]) {
        //         $(`[name*="[${identL}"]`).val(response[identN][identL]);
        //     }
        // }

        for (let index = 0; index < Number(response1[0].length - 1); index++) {
            $("#addHE").click();
        }

        for (identN in response1[0]) {
            for (identL in response1[0][identN]) {
                c = $($(`[name*="HorasExtra[${identL}"]`)[identN]);
                c.val(response1[0][identN][identL]);
            }
        }

        for (identN in response2[0]) {
            for (identL in response2[0][identN]) {
                c = $($(`[name*="data[${identL}"]`)[identN]);
                if (c.hasClass("select2")) {
                    c.select2("destroy");
                    c.val(response2[0][identN][identL]);
                    c.select2();
                } else {
                    c.val(response2[0][identN][identL]);
                }
            }
        }
        total();
        fechas();
    });
}