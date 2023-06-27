$(document).ready(async function () {
    config = await loadConfig();

    $datatable = $("#listAprov").DataTable($.extend(datatableParams,
        {
            "processing": true,
            "serverSide": true,
            "order": [[0, "desc"]],
            "ajax": "../controller/ssp.controller.php?ssp=listAprobar",
            "deferRender": true
        }
    )).on("draw", function () {
        $("tr").css({
            cursor: "pointer"
        }).each(function () {
            let status = $(this).find("span").data("status");
            let tr = $(this);
            if (status == 2) {
                tr.addClass("bg-info");
            }
        });
    }).buttons().container().appendTo($('.col-sm-6:eq(0)'));


    $("#listAprov tbody").on("mouseover", "tr", function () {
        $trS = $("#listAprov tbody tr");
        if (!$trS.hasClass("child")) {
            $trS.removeClass("bg-primary");
            $(this).addClass("bg-primary");
        }
    }).on("mouseout", "tr", function () {
        $trS = $("#listAprov tbody tr");
        if (!$trS.hasClass("child")) {
            $trS.removeClass("bg-primary");
        }
    }).on("click", "tr", function () { // marcar y desmarcar
        $trS = $(this);
        if (!$trS.hasClass("child")) {
            let ident = $trS.find("span").data("ident");
            $trS.toggleClass("bg-info");
            if ($trS.hasClass("bg-info")) {
                automaticForm("updateValueSql", [
                    2,
                    "checkStatus",
                    ident,
                    "ReportesHE"
                ]);
            } else {
                automaticForm("updateValueSql", [
                    1,
                    "checkStatus",
                    ident,
                    "ReportesHE"
                ]);
            }
        }
    });

    mail = automaticForm("getSession", ["email"]);
    rol = automaticForm("getSession", ["rol"]).toLocaleLowerCase();
    gestion = automaticForm("getSession", ["gestion"]).toLocaleLowerCase();
    usuario = automaticForm("getSession", ["usuario"]);
    id_aprobador = automaticForm("getValueSql", [mail, "correo", "id", "Aprobadores"]);

    $("#rechazar, #aprobar").on("click", function () { // aprobar y rechazar
        let type = $(this).attr("id") == "rechazar" ? 2 : 1;
        let aprobadores = [
            "jefe",
            "gerente",
            "rh",
            "contable"
        ];

        if (aprobadores.includes(rol)) {
            if (type == 1) {
                aprueba(config);
            } else {
                rechaza(config);
            }
        } else {
            alerts({
                "title": `Tu rol no permite realizar cambios: ${rol} - ${gestion}`,
                "icon": "info"
            });
        }
    });

    $("#STodo, #DTodo").on("click", function () { // marcar y desmarcar varios
        let change = $(this).attr("id") == "STodo" ? 2 : 1;
        flujo = {
            "jefe": "APROBACION_JEFE",
            "gerente": "APROBACION_GERENTE",
            "rh": "APROBACION_RH",
            "contable": "APROBACION_CONTABLE",
        };
        if (rol != "na") {
            automaticForm("updateValueSql", [change, "checkStatus", {
                "id_aprobador": id_aprobador,
                "id_estado": config[flujo[rol]] ?? "'id_estado'",
            }, "ReportesHE"]);
        }
        if (gestion != "na") {
            automaticForm("updateValueSql", [change, "checkStatus", {
                "id_aprobador": id_aprobador,
                "id_estado": config[flujo[gestion]] ?? "'id_estado'"
            }, "ReportesHE"]);
        }
        updateDatable();
    });

});

function updateDatable() {
    $("#listAprov").DataTable().ajax.reload(null, false);
}

function aprueba(config) {
    flujo = {
        "APROBACION_JEFE": config.APROBACION_GERENTE,
        "APROBACION_GERENTE": config.APROBACION_RH,
        "APROBACION_RH": config.APROBACION_CONTABLE,
        "APROBACION_CONTABLE": config.APROBADO
    };

    let aprobadores = [
        "jefe",
        "gerente",
        "rh",
        "contable"
    ];

    // user
    mail = automaticForm("getSession", ["email"]);
    rol = automaticForm("getSession", ["rol"]).toLocaleLowerCase();
    gestion = automaticForm("getSession", ["gestion"]).toLocaleLowerCase();
    usuario = automaticForm("getSession", ["usuario"]);
    // user

    if (aprobadores.includes(rol) || aprobadores.includes(gestion)) {
        // mail
        $to = [];
        $cc = mail;
        $subject = "Aprobación de Horas Extra";
        $body = "";
        // mail
        id_aprobador = automaticForm("getValueSql", [mail, "correo", "id", "Aprobadores"]);
        $aprueba = automaticForm("getDataSql", [
            "ReportesHE",
            `id_aprobador = '${id_aprobador}' and checkStatus = 2`,
            "id, empleado, correoEmpleado, id_estado"
        ]);
        $aprueba.forEach($a => {
            // update
            change = flujo[Object.keys(Object.filter(config, q => q == $a.id_estado))[0]];
            $check = automaticForm("updateValueSql", [change, "checkStatus = 1, id_estado", $a.id, "ReportesHE"]);
            // update
            if ($check.status == true) {
                // mail
                if (gestion == "contable") { // correo individual
                    sendMail(
                        [
                            {
                                name: $a.empleado,
                                mail: $a.correoEmpleado,
                            }
                        ],
                        $cc,
                        $subject,
                        `Buen dia, Las horas con el numero ${$a.id} han sido aprobadas.<br>
                        Gestiona: ${usuario} - ${mail}.<br>
                        Este mensaje ha sido generado automáticamente.<br>`
                    );
                } else { // correo agrupado
                    $to.push({
                        name: $a.empleado,
                        name: $a.correoEmpleado
                    });
                    $body += `${$a.empleado} - ${$a.id}<br>`;
                }
                // mail
            }
        });
        if (gestion != "contable") {
            sendMail(
                $to,
                $cc,
                $subject,
                `Buen día el siguiente lote fue aprobado.<br>
                ${$body}
                Gestiona: ${usuario} - ${mail}.<br>
                Este mensaje ha sido generado autoamticamente.<br>`
            );
        }
    } else {
        alerts({ title: "Tu rol no está habilitado para realizar esta acción", icon: "info" });
    }
    updateDatable();
}
function rechaza(config) {
    Swal.fire({
        title: 'MOTIVO DE RECHAZO',
        // input: 'text',
        html: `
        <div class="mb-3">
            <input id="swal2-input-titulo" class="form-control form-control-lg" placeholder="Titulo" type="text" value="Rechazo ${rol}">
        </div>
        <div class="mb-3">
            <textarea id="swal2-input-cuerpo" class="form-control form-control-lg" placeholder="Cuerpo"></textarea>
        </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        focusConfirm: false,
        preConfirm: () => {
            return {
                "titulo": $('#swal2-input-titulo').val(),
                "cuerpo": $('#swal2-input-cuerpo').val()
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            flujo = {
                "RECHAZO_GERENTE": config.RECHAZO,
                "APROBACION_JEFE": config.RECHAZO,
                "APROBACION_GERENTE": config.RECHAZO_GERENTE,
                "APROBACION_RH": config.RECHAZO_RH,
                "APROBACION_CONTABLE": config.RECHAZO_CONTABLE
            }
            let aprobadores = [
                "jefe",
                "gerente",
                "rh",
                "contable"
            ];
            // user
            mail = automaticForm("getSession", ["email"]);
            rol = automaticForm("getSession", ["rol"]).toLocaleLowerCase();
            gestion = automaticForm("getSession", ["gestion"]).toLocaleLowerCase();
            usuario = automaticForm("getSession", ["usuario"]);
            // user
            if (aprobadores.includes(rol) || aprobadores.includes(gestion)) {
                // mail
                $to = [];
                $cc = mail;
                $subject = "Rechazo de Horas Extra";
                $body = "";
                // mail
                id_aprobador = automaticForm("getValueSql", [mail, "correo", "id", "Aprobadores"]);
                $rechaza = automaticForm("getDataSql", [
                    "ReportesHE",
                    `id_aprobador = '${id_aprobador}' and checkStatus = 2`,
                    "id, empleado, correoEmpleado, id_estado"
                ]);
                if (rol == "gerente" && false) {
                    gerente_to_jefe();
                } else {
                    $rechaza.forEach($r => {
                        // update
                        change = flujo[Object.keys(Object.filter(config, q => q == $r.id_estado))[0]];
                        $check = automaticForm("updateValueSql", [change, "checkStatus = 1, id_estado", $r.id, "ReportesHE"]);
                        // update
                        if ($check.status == true) {
                            // ajax
                            $.ajax(`../controller/submit.controller.php?t=${timezone}&action=rechazo`, { // lo enviamos a el ajax para registrar el comentario de cada uno
                                type: "POST",
                                dataType: "JSON",
                                data: {
                                    data: $.extend(result.value, {
                                        id_reporte: $r.id,
                                        creadoPor: localStorage.getItem("usuario")
                                    })
                                },
                                success: function (response) {
                                    if (response.error) { // si ocurre un error en el registro lo mostramos
                                        alerts({
                                            title: response.error,
                                            icon: "Error",
                                            duration: 10000
                                        });
                                    } else { // caso contrario buscamos a ese usuario y le emitimos un mensaje
                                        if (server) {
                                            let duration = Number(result.value.length * 100);
                                            duration = duration <= 2000 ? 3000 : duration;
                                            // send server
                                            sendWS({
                                                general: true,
                                                usuario: $r.empleado,
                                                type: "alerts",
                                                data: {
                                                    arrayAlert: {
                                                        title: "Un aprobador ha rechazado tu solicitud.",
                                                        text: `Motivo: ${result.value.cuerpo}`,
                                                        icon: "info",
                                                        duration: duration
                                                    }
                                                }
                                            });
                                            // send server
                                        }
                                    }
                                }
                            });
                            // ajax
                            // mail
                            if (gestion == "contable") { // correo individual
                                sendMail(
                                    [
                                        {
                                            name: $r.empleado,
                                            mail: $r.correoEmpleado,
                                        }
                                    ],
                                    $cc,
                                    $subject,
                                    `Buen dia, Las horas con el numero ${$r.id} han sido aprobadas.<br>
                                    Motivo de rechazo: ${result.value.cuerpo}.<br>
                                    Gestiona: ${usuario} - ${mail}.<br>
                                    Este mensaje ha sido generado automáticamente.<br>`
                                );
                            } else { // correo agrupado
                                $to.push({
                                    name: $r.empleado,
                                    name: $r.correoEmpleado
                                });
                                $body += `${$r.empleado} - ${$r.id}<br>`;
                            }
                            // mail
                        }
                    });
                    if (gestion != "contable") {
                        sendMail(
                            $to,
                            $cc,
                            $subject,
                            `Buen día el siguiente lote fue rechazado.<br>
                            ${$body}
                            Gestiona: ${usuario} - ${mail}.<br>
                            Este mensaje ha sido generado autoamticamente.<br>`
                        );
                    }
                }
            } else {
                alerts({ title: "Tu rol no está habilitado para realizar esta acción", icon: "info" });
            }
        }
    });
    updateDatable();
}

function gerente_to_jefe() {
    Swal.fire({
        title: `Realizar rechazo a un jefe`,
        showDenyButton: true,
        confirmButtonText: `Si`,
        denyButtonText: `No`,
    }).then((confirm) => {
        $jefes = automaticForm("getDataSql", ["Aprobadores", "tipo = 'Jefe'", "id, nombre"])
        options = {}

        for (data in $jefes) {
            options[$jefes[data]["id"]] = $jefes[data]["nombre"]
        }

        if (confirm.isConfirmed) {
            Swal.fire({
                title: "Seleccione al jefe",
                input: `select`,
                inputAttributes: {
                    id: `swal2-input-jefe`
                },
                inputOptions: options,
                customClass: {
                    input: "form-control-lg"
                },
                confirmButtonText: `Confirmar`,
                cancelButtonText: 'Cancelar',
                didOpen: () => {
                    $select = $("select#swal2-input-jefe")
                }
            }).then((confirmJefe) => {
                if (confirmJefe.isConfirmed) {
                    /* gerente a jefe */
                }
            })
        } else if (confirm.isDenied) {
            /* gerente */
        }
    })
}

/* https://stackoverflow.com/questions/5072136/javascript-filter-for-objects */
Object.filter = (obj, predicate) => Object.keys(obj).filter(key => predicate(obj[key])).reduce((res, key) => (res[key] = obj[key], res), {});