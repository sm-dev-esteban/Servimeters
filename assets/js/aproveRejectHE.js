$(document).ready(async function () {
    config = await loadConfig();

    $datatable = $("#listAprov").DataTable($.extend(datatableParams,
        {
            "processing": true,
            "severSide": true,
            "order": [[0,
                "desc"]],
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
        $("#listAprov tbody tr").removeClass("bg-primary");
        $(this).addClass("bg-primary");
    }).on("mouseout", "tr", function () {
        $("#listAprov tbody tr").removeClass("bg-primary");
    }).on("click", "tr", function () { // marcar y desmarcar
        let ident = $(this).find("span").data("ident");
        $(this).toggleClass("bg-info");
        if ($(this).hasClass("bg-info")) {
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
                rol == "jefe" ? config.RECHAZO_GERENTE : (
                    rol == "gerente" ? config.RECHAZO_RH : (
                        rol == "rh" ? config.RECHAZO_CONTABLE : (
                            rol == "contable" ? config.RECHAZO : "Error"
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

        console.log(change);

        if (aprobadores.includes(rol)) {
            let id_aprobador = automaticForm("getValueSql", [
                email,
                "correo",
                "id",
                "Aprobadores"
            ]);
            if (type == 2) {

                Swal.fire({
                    title: 'MOTIVO DE RECHAZO',
                    input: 'text',
                    showCancelButton: true,
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if (result.isConfirmed == true) {

                        let $rechazados = automaticForm("getDataSql", [ // buscamos a los rechazados
                            "ReportesHE",
                            `checkStatus = '2' and id_aprobador = '${id_aprobador}' and id_estado = '${rol == "jefe"
                                ? config.APROBACION_JEFE
                                : (
                                    rol == "gerente"
                                        ? config.APROBACION_GERENTE
                                        : false
                                )}'`,
                            "*"
                        ]);

                        let check = automaticForm("updateValueSql", [ // los rechazamos
                            change,
                            "id_estado",
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
                        } else { // si todo bien hacemos un rerrido de los rechazados
                            $rechazados.forEach(rechazo => {
                                // ajax
                                $.ajax(`../controller/submit.controller.php?action=rechazo`, { // lo enviamos a el ajax para registrar el comentario de cada uno
                                    type: "POST",
                                    dataType: "JSON",
                                    data: {
                                        data: {
                                            cuerpo: result.value,
                                            id_reporte: rechazo.id,
                                            creadoPor: localStorage.getItem("usuario")
                                        }
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
                                                                text: `Motivo: ${result.value}`,
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
                            updateDatable();
                        }

                        // alerts({
                        //     title: "Motivo de rechazo confirmado",
                        //     text: "Todos los usuarios activos recibiran una notificación con el motivo de rechazo",
                        //     icon: "info"
                        // });
                    } else {
                        alerts({
                            title: "Rechazo no confirmado",
                            icon: "info"
                        })
                    }
                });

            } else if (type == 1) {
                let check = automaticForm("updateValueSql", [ // los aprobados
                    change,
                    "id_estado",
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


