$(document).ready(async function () {
    const config = await loadConfig();

    const mail = automaticForm("getSession", ["email"]);
    const rol = automaticForm("getSession", ["rol"]).toLocaleLowerCase();
    const gestion = automaticForm("getSession", ["gestion"]).toLocaleLowerCase();
    const usuario = automaticForm("getSession", ["usuario"]);
    const id_aprobador = automaticForm("getValueSql", [mail, "correo", "id", "Aprobadores"]);

    $datatable = $("#listAprov").DataTable($.extend(datatableParams,
        {
            "processing": true,
            "serverSide": false,
            "order": [[0, "desc"]],
            "ajax": "../controller/ssp.controller.php?ssp=listAprobar"
        }
    )).on("draw", function () {
        $("tr").css({
            cursor: "pointer"
        }).each(function () {
            let status = $(this).find("span").data("status");
            let tr = $(this);
            if (status == id_aprobador) {
                tr.addClass("bg-info");
            }
        });
    }).buttons().container().appendTo($('.col-sm-6:eq(0)'));

    $("#listAprov tbody").on("mouseover", "tr", function () {
        $tr = $("#listAprov tbody tr");
        if (!$tr.hasClass("child")) {
            $tr.removeClass("bg-primary");
            $(this).addClass("bg-primary");
        }
    }).on("mouseout", "tr", function () {
        $tr = $("#listAprov tbody tr");
        if (!$tr.hasClass("child")) {
            $tr.removeClass("bg-primary");
        }
    }).on("click", "tr", function () { // marcar y desmarcar
        $tr = $(this);
        if (!$tr.hasClass("child")) {
            let ident = $tr.find("span").data("ident");
            $tr.toggleClass("bg-info");
            if ($tr.hasClass("bg-info")) {
                automaticForm("updateValueSql", [
                    id_aprobador,
                    "check_user",
                    ident,
                    "ReportesHE"
                ]);
            } else {
                automaticForm("updateValueSql", [
                    0,
                    "check_user",
                    ident,
                    "ReportesHE"
                ]);
            }
        }
    });

    $("#rechazar, #aprobar").on("click", function () { // aprobar y rechazar
        type = $(this).attr("id") == "rechazar" ? 2 : 1;
        aprobadores = [
            "jefe",
            "gerente",
            "rh",
            "contable"
        ];

        if (aprobadores.includes(rol)) {
            if (type == 1) {
                newAprueba();
            } else {
                newRechaza();
            }
        } else {
            alerts({
                "title": `Tu rol no permite realizar cambios: ${rol} - ${gestion}`,
                "icon": "info"
            });
        }
    });

    $("#STodo, #DTodo").on("click", function () { // marcar y desmarcar varios
        let change = $(this).attr("id") == "STodo" ? id_aprobador : 0;
        flujo = {
            "jefe": "APROBACION_JEFE",
            "gerente": "APROBACION_GERENTE",
            "rh": "APROBACION_RH",
            "contable": "APROBACION_CONTABLE",
        };
        if (change == 0) {
            automaticForm("updateValueSql", [change, "check_user", {
                "check_user": id_aprobador
            }, "ReportesHE"]);
        } else {
            if (rol != "na") {
                $a = automaticForm("updateValueSql", [change, "check_user", {
                    "id_aprobador": id_aprobador,
                    "id_estado": config[flujo[rol]] ?? "'id_estado'",
                }, "ReportesHE"]);
                // console.log($a["query"] ?? "noquery", "rol");
            }
            if (gestion != "na") {
                $a = automaticForm("updateValueSql", [change, "check_user", {
                    "id_estado": config[flujo[gestion]] ?? "'id_estado'"
                }, "ReportesHE"]);
                // console.log($a["query"] ?? "noquery", "gestion");
            }
        }
        updateDatable();
    });

});

function updateDatable() {
    $("#listAprov").DataTable().ajax.reload(null, false);
}

async function newAprueba() {
    idTipoComentario = automaticForm("getValueSql", ["Apro", "nombre", "id", "TipoComentario", { like: true }]);
    let config, mail, rol, gestion, usuario, id_aprobador, $aprueba;
    // config
    config = await loadConfig();
    // user
    mail = automaticForm("getSession", ["email"]);
    rol = automaticForm("getSession", ["rol"]).toUpperCase();
    gestion = automaticForm("getSession", ["gestion"]).toUpperCase();
    usuario = automaticForm("getSession", ["usuario"]);
    id_aprobador = automaticForm("getValueSql", [mail, "correo", "id", "Aprobadores"]);
    // mail
    $to = [];
    $cc = mail;
    $subject = "Aprobación de Horas Extra";
    $body = "";
    // reportsChecked
    $aprueba = automaticForm("getDataSql", [
        "ReportesHE",
        `check_user = '${id_aprobador}'`,
        "id, empleado, correoEmpleado, id_estado"
    ]);
    // ------------------------rol------------------------ //
    if (rol != "NA") if (rol == "JEFE") {
        const Apr_jefe = $aprueba.filter((q) => {
            return q.id_estado == config.APROBACION_JEFE || q.id_estado == config.RECHAZO_GERENTE;
        });
        if (Apr_jefe.length > 0) {
            newApproverRol = await solicitarAprovador(["GERENTE"]);

            if (newApproverRol) Apr_jefe.forEach(async $Apr => {
                await registrarMotivo({
                    titulo: `Aprobación ${rol}`,
                    cuerpo: `Reporte aprobado por "${usuario}"`,
                    id_reporte: $Apr.id,
                    creadoPor: usuario,
                    idTipoComentario: idTipoComentario
                })
                $check = automaticForm("updateValueSql", [config.APROBACION_GERENTE, `check_user = 0, id_aprobador = ${newApproverRol}, id_estado`, $Apr.id, "ReportesHE"]);
                if ($check.status == true) {
                    $to.push({
                        name: $Apr.empleado,
                        mail: $Apr.correoEmpleado
                    });
                    $body += `${$Apr.empleado} - ${$Apr.id}<br>`;
                } else {
                    // console.log("Error:", ($check.error ? $check.error : "request"));
                }
            });
        }
    } else if (rol == "GERENTE") {
        const Apr_gerente = $aprueba.filter((q) => {
            return q.id_estado == config.APROBACION_GERENTE || q.id_estado == config.RECHAZO_RH || q.id_estado == config.RECHAZO_CONTABLE;
        })
        if (Apr_gerente.length > 0) {
            newApproverRol = await solicitarAprovador(["RH"]);

            if (newApproverRol) Apr_gerente.forEach(async $Apr => {
                await registrarMotivo({
                    titulo: `Aprobación ${rol}`,
                    cuerpo: `Reporte aprobado por "${usuario}"`,
                    id_reporte: $Apr.id,
                    creadoPor: usuario,
                    idTipoComentario: idTipoComentario
                })
                $check = automaticForm("updateValueSql", [config.APROBACION_RH, `check_user = 0, id_aprobador = ${newApproverRol}, id_estado`, $Apr.id, "ReportesHE"]);
                if ($check.status == true) {
                    $to.push({
                        name: $Apr, empleado,
                        mail: $Apr.correoEmpleado
                    });
                    $body += `${$Apr.empleado} - #${$Apr.id}<br>`
                } else {
                    // console.log("Error:", ($check.error ? $check.error : "request"));
                }
            });
        }
    }
    // ------------------------gestion------------------------ //
    if (gestion != "NA") if (gestion == "RH") {
        const Apr_rh = $aprueba.filter((q) => {
            return q.id_estado == config.APROBACION_RH;
        })
        if (Apr_rh.length > 0) {
            newApproverGestion = await solicitarAprovador(["CONTABLE"]);

            if (newApproverGestion) Apr_rh.forEach(async $Apr => {
                await registrarMotivo({
                    titulo: `Aprobación ${gestion}`,
                    cuerpo: `Reporte aprobado por "${usuario}"`,
                    id_reporte: $Apr.id,
                    creadoPor: usuario,
                    idTipoComentario: idTipoComentario
                })
                $check = automaticForm("updateValueSql", [config.APROBACION_CONTABLE, `check_user = 0, id_aprobador = ${newApproverGestion}, id_estado`, $Apr.id, "ReportesHE"]);
                if ($check.status == true) {
                    $to.push({
                        name: $Apr, empleado,
                        mail: $Apr.correoEmpleado
                    });
                    $body += `${$Apr.empleado} - #${$Apr.id}<br>`
                } else {
                    // console.log("Error:", ($check.error ? $check.error : "request"));
                }
            });
        }
    } else if (gestion == "CONTABLE") {
        const Apr_contable = $aprueba.filter((q) => {
            return q.id_estado == config.APROBACION_CONTABLE;
        })
        if (Apr_contable.length > 0) Apr_contable.forEach(async $Apr => {
            await registrarMotivo({
                titulo: `Aprobación ${gestion}`,
                cuerpo: `Reporte aprobado por "${usuario}"`,
                id_reporte: $Apr.id,
                creadoPor: usuario,
                idTipoComentario: idTipoComentario
            })
            $check = automaticForm("updateValueSql", [config.APROBADO, `check_user = 0, id_aprobador = ${id_aprobador}, id_estado`, $Apr.id, "ReportesHE"]);
            if ($check.status == true) {
                if (false) sendMail([{
                    name: $Apr.empleado,
                    mail: $Apr.correoEmpleado
                }], $cc, $subject,
                    `Buen día, Las horas con el numero ${$Apr.id} han sido aprobadas.<br>
                        Gestiona: ${usuario} - ${mail}.<br>
                        Este mensaje ha sido generado automáticamente.<br>`);
                $body += `${$Apr.empleado} - #${$Apr.id}<br>`
            } else {
                // console.log("Error:", ($check.error ? $check.error : "request"));
            }
        });

    }
    // despues de ejecutar todo el codigó enviamos el correo y actualizamos la tabla
    if ($to.length > 0 && false) sendMail($to, $cc, $subject,
        `Buen día, Las siguientes horas han sido aprobadas.<br>
        ${$body}
        Gestiona: ${usuario} - ${mail}.<br>
        Este mensaje ha sido generado automáticamente.<br>`);
    updateDatable();
}

async function newRechaza() {
    idTipoComentario = automaticForm("getValueSql", ["Rech", "nombre", "id", "TipoComentario", { like: true }]);
    let config, mail, rol, gestion, usuario, id_aprobador, $rechaza;
    // config
    config = await loadConfig();
    // user
    mail = automaticForm("getSession", ["email"]);
    rol = automaticForm("getSession", ["rol"]).toUpperCase();
    gestion = automaticForm("getSession", ["gestion"]).toUpperCase();
    usuario = automaticForm("getSession", ["usuario"]);
    id_aprobador = automaticForm("getValueSql", [mail, "correo", "id", "Aprobadores"]);
    // mail
    $to = [];
    $cc = mail;
    $subject = "Rechazo de Horas Extra";
    $body = "";
    // reportsChecked
    $rechaza = automaticForm("getDataSql", [
        "ReportesHE",
        `check_user = '${id_aprobador}'`,
        "id, empleado, correoEmpleado, id_estado"
    ]);

    if (rol != "NA") if (rol == "JEFE") {
        const Rec_jefe = $rechaza.filter((q) => {
            return q.id_estado == config.APROBACION_JEFE && q.id_estado == config.APROBACION_JEFE;
        });
        if (Rec_jefe.length > 0) {
            motivo = await solicitarRechazo();

            if (motivo.cuerpo ?? false) Rec_jefe.forEach(async $Rec => {
                await registrarMotivo($.extend(motivo, {
                    id_reporte: $Rec.id,
                    creadoPor: usuario,
                    idTipoComentario: idTipoComentario
                }));
                $check = automaticForm("updateValueSql", [config.RECHAZO, "check_user = 0, id_estado", $Rec.id, "ReportesHE"]);
                if ($check.status == true) {
                    $to.push({
                        name: $Rec.empleado,
                        mail: $Rec.correoEmpleado
                    });
                    $body += `${$Rec.empleado} - ${$Rec.id}<br>`;
                } else {
                    // console.log("Error:", ($check.error ? $check.error : "request"));
                }
            });
        }
    } else if (rol == "GERENTE") {
        const Rec_gerente = $rechaza.filter((q) => {
            return q.id_estado == config.APROBACION_GERENTE || q.id_estado == config.RECHAZO_RH || q.id_estado == config.RECHAZO_CONTABLE;
        })
        if (Rec_gerente.length > 0) {
            const { value: rechazo_jefe } = await Swal.fire({
                title: 'Realizar rechazo a un jefe',
                showDenyButton: true,
                confirmButtonText: 'Si',
                denyButtonText: `No`
            });

            if (rechazo_jefe) newApprover = await solicitarAprovador(["JEFE"]);
            else newApprover = await continuar("¿Desea continuar con el rechazo?");

            if (newApprover) {
                motivo = await solicitarRechazo();

                if (motivo.cuerpo ?? false) Rec_gerente.forEach(async $Rec => {
                    await registrarMotivo($.extend(motivo, {
                        id_reporte: $Rec.id,
                        creadoPor: usuario,
                        idTipoComentario: idTipoComentario
                    }));
                    estado = (newApprover === true ? config.RECHAZO : config.RECHAZO_GERENTE);
                    aprobador = (newApprover === true ? id_aprobador : newApprover);
                    $check = automaticForm("updateValueSql", [estado, `check_user = 0, id_aprobador = '${aprobador}', id_estado`, $Rec.id, "ReportesHE"]);
                    if ($check.status === true) {
                        $to.push({
                            name: $Rec.empleado,
                            mail: $Rec.correoEmpleado
                        });
                        $body += `${$Rec.empleado} - ${$Rec.id}<br>`;
                    } else {
                        // console.log("Error:", ($check.error ? $check.error : "request"));
                    }
                });
            }
        }
    }
    if (gestion != "NA") if (gestion == "RH") {
        const Rec_rh = $rechaza.filter((q) => {
            return q.id_estado == config.APROBACION_RH;
        })
        if (Rec_rh.length > 0) {
            approver = await solicitarAprovador(["GERENTE"]);
            motivo = await solicitarRechazo();

            if (motivo.cuerpo ?? false) Rec_rh.forEach(async $Rec => {
                await registrarMotivo($.extend(motivo, {
                    id_reporte: $Rec.id,
                    creadoPor: usuario,
                    idTipoComentario: idTipoComentario
                }));
                $check = automaticForm("updateValueSql", [config.RECHAZO_RH, `check_user = 0, id_aprobador = '${approver}', id_estado`, $Rec.id, "ReportesHE"]);
                if ($check.status === true) {
                    $to.push({
                        name: $Rec.empleado,
                        mail: $Rec.correoEmpleado
                    });
                    $body += `${$Rec.empleado} - ${$Rec.id}<br>`;
                } else {
                    // console.log("Error:", ($check.error ? $check.error : "request"));
                }
            })
        }
    } else if (gestion == "CONTABLE") {
        const Rec_gestion = $rechaza.filter((q) => {
            return q.id_estado == config.APROBACION_CONTABLE;
        })
        if (Rec_gestion.length > 0) {
            approver = await solicitarAprovador(["GERENTE"]); // esperando hasta que obtenga la respuesta
            motivo = await solicitarRechazo();

            if (motivo.cuerpo ?? false) Rec_gestion.forEach(async $Rec => {
                await registrarMotivo($.extend(motivo, {
                    id_reporte: $Rec.id,
                    creadoPor: usuario,
                    idTipoComentario: idTipoComentario
                }));
                $check = automaticForm("updateValueSql", [config.RECHAZO_CONTABLE, `id_aprobador = ${approver}, id_estado`, $Rec.id, "ReportesHE"]);
                if ($check) {
                    $to.push({
                        name: $Rec.empleado,
                        mail: $Rec.correoEmpleado
                    });
                    $body += `${$Rec.empleado} - ${$Rec.id}<br>`;
                } else {
                    // console.log("Error:", ($check.error ? $check.error : "request"));
                }
            })
        }
    }
    // despues de ejecutar todo el codigó enviamos el correo y actualizamos la tabla
    if ($to.length > 0) sendMail($to, $cc, $subject,
        `Buen día, Las siguientes horas han sido aprobadas.<br>
        ${$body}
        Gestiona: ${usuario} - ${mail}.<br>
        Este mensaje ha sido generado automáticamente.<br>`);
    updateDatable();
}

async function continuar(msg) {
    const { value: Rec_continue } = await Swal.fire({
        title: msg,
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
    })

    return Rec_continue;
}

async function solicitarAprovador(arrayFilter = [false]) {

    if (typeof arrayFilter !== "object") return false;

    aFilter = arrayFilter.map(q => `(tipo like '%${q}%' or gestiona like '%${q}%')`).join(" or ");

    // listApprovers = automaticForm("getDataSql", ["Aprobadores", "tipo like '%RH%' or gestiona like '%RH%'"]);
    listApprovers = automaticForm("getDataSql", ["Aprobadores", aFilter]);

    options = {};
    for (data in listApprovers) options[listApprovers[data]["id"]] = listApprovers[data]["nombre"];

    const { value: Approver } = await Swal.fire({
        title: `Seleccione ${arrayFilter.join(" - ")}`,
        input: "select",
        inputAttributes: {
            id: "swal2-select-list-approvers"
        },
        inputOptions: options,
        customClass: {
            input: "form-control-lg"
        }
    });

    return Approver;
}

async function solicitarRechazo() {
    rol = automaticForm("getSession", ["rol"]).toUpperCase();

    const { value: motivo } = await Swal.fire({
        title: 'MOTIVO DE RECHAZO',
        html: `
            <div class="mb-3">
                <input id="swal2-input-titulo" class="form-control form-control-lg" placeholder="Titulo" type="text" value="Rechazo ${rol}">
            </div>
            <div class="mb-3">
                <textarea id="swal2-input-cuerpo" class="form-control form-control-lg" placeholder="Cuerpo" required></textarea>
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
    });

    return motivo;
}

async function registrarMotivo(d) {
    return $.ajax("../controller/submit.controller.php?action=Comentarios", {
        data: {
            data: d
        },
        dataType: "JSON",
        type: "POST",
        async: false
    }).responseJSON;
}