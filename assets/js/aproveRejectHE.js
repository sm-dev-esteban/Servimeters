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
        $("tr").each(function () {
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

    $("#rechazar, #aprobar").on("click", function () { // aprobar y rechazar
        let type = $(this).attr("id") == "rechazar" ? 2 : 1;
        let email = localStorage.getItem("email");
        let rol = localStorage.getItem("rol").toLocaleLowerCase();
        let change = "";
        let aprobadores = [
            "jefe",
            "gerente",
            "rh",
            "contable"
        ];

        if (type == 2) { // rechazo segun area
            change = (
                rol == "jefe" ? config.RECHAZO : (
                    rol == "gerente" ? config.RECHAZO : (
                        rol == "rh" ? config.RECHAZO_RH : (
                            rol == "contable" ? config.RECHAZO_CONTABLE : "Error"
                        )
                    )
                )
            );
        } else if (type == 1) { // aprobación segun area
            change = (
                rol == "jefe" ? config.APROBACION_GERENTE : (
                    rol == "gerente" ? config.APROBACION_RH : (
                        rol == "rh" ? config.APROBACION_CONTABLE : (
                            rol == "contable" ? config.APROBADO : "Error"
                        )
                    )
                )
            );
        }



        if (aprobadores.includes(rol)) {
            let id_aprobador = automaticForm("getValueSql", [
                email,
                "correo",
                "id",
                "Aprobadores"
            ]);
            if (type == 2) {

                if (rol == "gerente") {
                    Swal.fire({
                        title: `Realizar rechazo a un jefe`,
                        showDenyButton: true,
                        confirmButtonText: `Si`,
                        denyButtonText: `No`,
                    }).then((confirm) => {
                        $jefes = automaticForm("getDataSql", ["Aprobadores", "tipo = 'Jefe'", "id, nombre"])
                        options = {};
                        // options = ``;

                        for (data in $jefes) {
                            options[$jefes[data]["id"]] = $jefes[data]["nombre"];
                            // options += `<option value="${[$jefes[data]["id"]]}">${$jefes[data]["nombre"]}</option>`;
                        }

                        // intent ponerle select 2 pero el estilo de la alerta de no deja :c
                        if (confirm.isConfirmed) {
                            Swal.fire({
                                title: "Seleccione al jefe",
                                input: `select`,
                                inputAttributes: {
                                    id: `swal2-input-jefe`
                                },
                                inputOptions: options,
                                customClass: {
                                    // input: "form-control form-control-lg"
                                    input: "form-control-lg"
                                },
                                // html: `
                                // <div class="mb-3">
                                //     <select id="swal2-input-jefe" class="form-control form-control-lg" style="width: 100%">${options}</select>
                                // </div>
                                // <script>
                                // $(document).ready(function () {
                                //     $("#swal2-input-jefe").select2();
                                // })
                                // </script>
                                // `,
                                confirmButtonText: `Confirmar`,
                                cancelButtonText: 'Cancelar',
                                didOpen: () => {
                                    $select = $("select#swal2-input-jefe");
                                    // $select.select2();
                                }
                            }).then((confirmJefe) => {
                                if (confirmJefe.isConfirmed) {
                                    /* gerente a jefe */
                                    change = config.RECHAZO_GERENTE;
                                    rechazo(config, rol, change, id_aprobador, true, [{ "id_aprobador": confirmJefe.value, "checkStatus": 1 }]);
                                }
                            })
                        } else if (confirm.isDenied) {
                            /* gerente */
                            change = config.RECHAZO_GERENTE;
                            rechazo(config, rol, change, id_aprobador, false, [{ "id_aprobador": id_aprobador, "checkStatus": 1 }]);
                        }
                    })
                } else {
                    /* otros roles */
                    rechazo(config, rol, change, id_aprobador, (rol == "jefe" ? false : true), [{ "id_aprobador": id_aprobador, "checkStatus": 1 }]);
                }

            } else if (type == 1) {
                let $sendMail = [];
                let $aprobados = automaticForm("getDataSql", [ // buscamos a los rechazados
                    "ReportesHE",
                    `checkStatus = '2' and id_aprobador = '${id_aprobador}' and id_estado = '${(
                        rol == "jefe" ? config.APROBACION_JEFE : (
                            rol == "gerente" ? config.APROBACION_GERENTE : (
                                rol == "rh" ? config.APROBACION_RH : (
                                    rol == "contable" ? config.APROBACION_CONTABLE : false
                                )
                            )
                        )
                    )}'`,
                    "id, empleado, correoEmpleado"
                ]);
                $aprobados.forEach(aprueba => {
                    if (rol == "jefe" | rol == "gerente" | rol == "rh") {
                        $sendMail.push({
                            name: aprueba.empleado,
                            mail: aprueba.correoEmpleado
                        });
                    } else if (rol == "contable") {
                        sendMail([
                            {
                                name: aprueba.empleado,
                                mail: aprueba.correoEmpleado
                            }],
                            email,
                            "Aprobación de Horas Extra",
                            `
                            Buen dia, Las Horas Extra con el número ${aprueba.id} han sido aprobadas.<br>
                            Este mensaje ha sido generado automáticamente.<br>
                            `
                        )
                    }
                });

                if (rol == "jefe" | rol == "gerente" | rol == "rh") {
                    sendMail(
                        $sendMail,
                        email,
                        "Aprobación de Horas Extra",
                        `
                        Buen día, sus horas extras fueron aprobadas.<br>
                        Este mensaje ha sido generado automáticamente.<br>
                        `
                    )
                }

                let check = automaticForm("updateValueSql", [ // los aprobados
                    change,
                    "id_estado",
                    {
                        "checkStatus": "2",
                        "id_aprobador": id_aprobador,
                        "id_estado": (
                            rol == "jefe" ? config.APROBACION_JEFE : (
                                rol == "gerente" ? config.APROBACION_GERENTE : (
                                    rol == "rh" ? config.APROBACION_RH : (
                                        rol == "contable" ? config.APROBACION_CONTABLE : false
                                    )
                                )
                            )
                        )
                    },
                    "ReportesHE"
                ]);

                if (check.error) {
                    alerts({
                        title: check.error,
                        icon: "Error",
                        duration: 10000
                    });
                } else {
                    alerts({
                        title: "Aprobación realizada",
                        icon: "Success"
                    });
                    updateDatable();
                }
            }
        } else {
            alerts({
                "title": `Tu rol no permite realizar cambios: ${rol}`,
                "icon": "info"
            });
        }

    });

    $("#STodo, #DTodo").on("click", function () { // marcar y desmarcar varios
        let change = $(this).attr("id") == "STodo" ? 2 : 1;
        let email = localStorage.getItem("email");
        let rol = localStorage.getItem("rol").toLocaleLowerCase();
        let id_aprobador = automaticForm("getValueSql", [
            email,
            "correo",
            "id",
            "Aprobadores"
        ]);

        let check = automaticForm("updateValueSql", [
            change,
            "checkStatus",
            {
                "id_aprobador": id_aprobador,
                "id_estado": rol == "jefe"
                    ? config.APROBACION_JEFE
                    : (
                        rol == "gerente"
                            ? config.APROBACION_GERENTE
                            : false
                    )
            },
            "ReportesHE"
        ]);

        if (check.error) {
            alerts({
                "title": check.error,
                "icon": "Error"
            });
        }

        updateDatable();

    });

});

function updateDatable() {
    $("#listAprov").DataTable().ajax.reload(null, false);
}


function rechazo(config, rol, change, id_aprobador, envioMasivo = true, modifyHE) {
    email = localStorage.getItem("email");
    userA = localStorage.getItem("usuario");

    let user_id_aprobador = automaticForm("getValueSql", [
        email,
        "correo",
        "id",
        "Aprobadores"
    ]);

    let $where = [];

    if (modifyHE && modifyHE.length > 0) {
        modifyHE.forEach(element => {
            for (data in element) {
                $where.push(`${data} = '${element[data]}'`);
            }
        });
    }

    $where = $where.join(", ");

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

            let $sendMail = [];

            let $rechazados = automaticForm("getDataSql", [ // buscamos a los rechazados
                "ReportesHE",
                `checkStatus = '2' and id_aprobador = '${id_aprobador}' and id_estado = '${(
                    rol == "jefe" ? config.APROBACION_JEFE : (
                        rol == "gerente" ? config.APROBACION_GERENTE : (
                            rol == "rh" ? config.APROBACION_RH : (
                                rol == "contable" ? config.APROBACION_CONTABLE : false
                            )
                        )
                    )
                )}'`,
                "id, empleado, correoEmpleado"
            ]);

            let check = automaticForm("updateValueSql", [ // los rechazamos
                change,
                `${$where}, id_estado`,
                {
                    "checkStatus": "2",
                    "id_aprobador": id_aprobador,
                    "id_estado": rol == "jefe"
                        ? config.APROBACION_JEFE
                        : (
                            rol == "gerente"
                                ? config.APROBACION_GERENTE
                                : false
                        )
                },
                "ReportesHE"
            ]);

            if (check.error) { // si ocurre un error lo mostramos en una alerta
                alerts({
                    title: check.error,
                    icon: "Error",
                    duration: 10000
                });
            } else { // si todo bien hacemos un recorrido de los rechazados
                $rechazados.forEach(rechazo => {
                    if (envioMasivo == false) {
                        sendMail(
                            [
                                {
                                    name: rechazo.empleado,
                                    mail: rechazo.correoEmpleado
                                }
                            ],
                            email,
                            `Rechazo de Horas Extra`,
                            `
                            Buen dia, el usuario ${userA} ha rechazado un lote de Horas Extra.<br>
                            Motivo: ${result.value.cuerpo}.<br>
                            Este mensaje ha sido generado automáticamente.<br>
                            `
                        );
                    } else if (envioMasivo == true) {
                        $sendMail.push({
                            name: rechazo.empleado,
                            mail: rechazo.correoEmpleado
                        });
                    }
                    // ajax
                    $.ajax(`../controller/submit.controller.php?action=rechazo`, { // lo enviamos a el ajax para registrar el comentario de cada uno
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            data: $.extend(result.value, {
                                id_reporte: rechazo.id,
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
                                    server.send(
                                        JSON.stringify({
                                            general: true,
                                            usuario: rechazo.empleado,
                                            type: "alerts",
                                            data: {
                                                arrayAlert: {
                                                    title: "Un aprobador ha rechazado tu solicitud.",
                                                    text: `Motivo: ${result.value.cuerpo}`,
                                                    icon: "info",
                                                    duration: duration
                                                }
                                            }
                                        })
                                    );
                                    // send server
                                }
                            }
                        }
                    });
                    // ajax
                });
                if (envioMasivo == true) {
                    if ($sendMail.length > 0) {
                        sendMail(
                            $sendMail,
                            email,
                            `Rechazo de Horas Extra`,
                            `
                            Buen día, sus horas extras fueron rechazadas.<br>
                            Motivo: ${result.value.cuerpo}.<br>
                            Este mensaje ha sido generado automáticamente.<br>
                            `
                        );
                    }
                }
                updateDatable();
            }

        } else {
            alerts({
                title: "Rechazo no confirmado",
                icon: "info"
            })
        }
    });
}