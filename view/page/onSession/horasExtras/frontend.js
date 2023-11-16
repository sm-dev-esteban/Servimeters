$(document).ready(async () => {
    ldapAutoComplete([
        {
            element: `[name="data[correoEmpleado]"]`,
            event: `input`,
            search: `mail`
        }
    ])

    ldapAutoComplete([
        {
            event: `input`,
            elements: [],
            element: `[name="data[correoEmpleado]"]`,
            search: `mail`
        }
    ])

    ldapAutoComplete([
        {
            element: `[name="data[CC]"]`,
            event: `input`,
            search: `CC`
        }, {
            element: `[name="data[cargo]"]`,
            event: `input`,
            search: `cargo`
        }, {
            element: `[name="data[proyecto]"]`,
            event: `input`,
            search: `proyecto`
        }
    ], {
        url: `${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=autoComplete`,
        dataForLDAP: false
    })

    bsCustomFileInput.init()

    $(`[data-toggle="popover"]`).popover({
        container: 'body'
    })

    var systemConfig = await getSystemConfig()

    const $form = $(`form[data-mode]`)
    if ($form.data("mode") === "UPDATE") loadData($(`#report`).val())

    $form.on("submit", async function (e) {
        e.preventDefault()

        const $this = $(this)

        let send = true

        const $cEditable = $this.find(`[contenteditable][name]`)
        const $cEditableRequired = $this.find(`[contenteditable][name][required]`)
        const $btnSubmit = $this.find(`button:submit:eq(0)`)
        const btnHTML = $btnSubmit.html()
        const $tableDatail = $(`#tableDatail`)


        const $cERequired = $cEditableRequired.filter(function () {
            return $(this).text().trim() === ''
        })

        if ($cERequired.length > 0) {
            send = false

            $cERequired.addClass("isInvalid")

            $('html, body').animate({
                scrollTop: $tableDatail.eq(0).offset().top
            }, 1000, () => {
                $cEditableRequired.removeClass("isInvalid")
            })
        }

        const $Total_Extras = $(`[name="data[Total_Extras]"]`)
        const Total_Extras = Number($Total_Extras.text())
        const Limit_Extras = Number(systemConfig[0].LIMIT_HE)

        if (Total_Extras > Limit_Extras) {
            alerts({
                title: "Número de horas extras excedido",
                text: `Maximo: ${Limit_Extras}`,
                icon: "info"
            })
        } else if (send) {
            $cEditable.each(function () {
                const $q = $(this)
                $this.append(`<input type="hidden" name="${$q.attr(`name`)}" value="${$q.text()}" data-temporal-send-data>`)
            })
            $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=${$this.data("mode")}`, {
                cache: false,
                processData: false,
                contentType: false,
                dataType: "JSON",
                type: "POST",
                data: new FormData(this),
                beforeSend: () => {
                    $btnSubmit.html(elementCreator("span", {
                        class: "spinner-grow spinner-grow-sm",
                        role: "status",
                        "aria-hidden": "true"
                    })).attr("disabled", true)
                },
                success: (response) => {
                    if (response.status && response.status === true) {
                        window.location.href = `${GETCONFIG("SERVER_SIDE")}/horasExtras/misHoras`
                    } data - mode
                },
                complete: () => {
                    $(`[data-temporal-send-data]`).remove()
                    $btnSubmit.html(btnHTML).removeAttr("disabled")
                }
            })
        }
    })

    $(`table#tableDatail`).on(`input`, `[contenteditable][step]`, function () {
        const $this = $(this);
        const val = parseFloat($this.text())
        const step = parseFloat($this.attr(`step`))

        if (!isNaN(val) && !isNaN(val) && step > 0 && (val % step != 0)) {
            const round = Math.round(val / step) * step
            $this.text(round).trigger(`input`)
        }
    })

    $(`table#tableDatail`).on(`input`, `[contenteditable][type="number"]`, function () {
        const $this = $(this);
        const val = $this.text()

        if (/[^0-9.]/.test(val)) $this.text(val.replace(/[^0-9.]/g, '')).trigger(`input`)
    })

    const $table = $(`table[data-action]`)
    $table.DataTable($.extend(GETCONFIG("DATATABLE"), {
        "processing": true,
        "serverSide": true,
        "order": [[0, `desc`]],
        "ajax": `${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=${$table.data("action")}`
    })).buttons().container().appendTo($('.col-sm-6:eq(0)'))

    const $tableGestion = $(`table#ssp_gestion`)
    $tableGestion.DataTable($.extend(GETCONFIG("DATATABLE"), {
        "responsive": true,
        "processing": true,
        "serverSide": true,
        "order": [[0, `desc`]],
        "ajax": `${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=${$tableGestion.attr("id")}`
    })).on("draw", function () {
        const $this = $(this)
        $this.find("tbody tr").css({
            "cursor": "pointer"
        }).each(function () {
            const $this = $(this)
            const checked = $this.find(`[data-check]`).data("check")
            if (checked === true) $this.addClass("bg-primary")
        })
    }).buttons().container().appendTo($('.col-sm-6:eq(0)'))

    $tableGestion.find("tbody").on("click", "tr.odd", function () {
        const $this = $(this)
        const id = $this.find(`[data-id]`).data("id")
        $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=gestion_checked`, {
            dataType: "JSON",
            type: "POST",
            data: {
                report: id
            },
            success: (response) => {
                if (response.status && response.status === true) $this.toggleClass("bg-primary")
                // updateDatatable()
            }
        })
    })

    const $mesReportado = $(`[name="data[mes]"]`)
    $mesReportado.on(`change`, () => {
        dates()
    })

    const $checkAdjuntos = $(`#checkAdjuntos`)
    $checkAdjuntos.on(`change`, function () {
        const $adjuntos = $(`#adjuntos`)
        const $labelAdjuntos = $(`[for="adjuntos"]`)
        if (this.checked) {
            $labelAdjuntos.text(`Choose file`)
            $adjuntos.attr(`required`, true).removeAttr(`disabled`)
        }
        else {
            $labelAdjuntos.text(`Disabled`)
            $adjuntos.attr(`disabled`, true).removeAttr(`required`).val(``)
        }
    })

    if (window["getStatusHE"] ?? false) $(`[name="data[id_estado]"]`).val(getStatusHE("nombre", "EDICION")[0]["id"] ?? 0)

    const $chechAprobador = $(`[name="data[chechAprobador]"]`)
    $chechAprobador.on(`change`, function () {
        const $this = $(this)
        const $id_aprobador = $(`[name="data[id_aprobador]"]`)
        const $id_estado = $(`[name="data[id_estado]"]`)

        var $Jefes = $(`select#Jefes`)
        var $Gerentes = $(`select#Gerentes`)


        $id_aprobador.val({
            "GERENTES": () => {
                $id_estado.val(getStatusHE("nombre", "APROBACION_GERENTE")[0]["id"] ?? 0)

                $Jefes.attr(`disabled`, true)
                $Jefes.removeAttr(`required`)
                $Jefes.val(``)

                $Gerentes.removeAttr(`disabled`)
                $Gerentes.attr(`required`, true)

                $Gerentes.val(``)
                return $Gerentes.val()
            },
            "JEFES": () => {
                $id_estado.val(getStatusHE("nombre", "APROBACION_JEFE")[0]["id"] ?? 0)

                $Gerentes.attr(`disabled`, true)
                $Gerentes.removeAttr(`required`)
                $Gerentes.val(``)

                $Jefes.removeAttr(`disabled`)
                $Jefes.attr(`required`, true)

                $Jefes.val(``)
                return $Jefes.val()
            },
            "EDICIÓN": () => {
                $id_estado.val(getStatusHE("nombre", "EDICION")[0]["id"] ?? 0)

                const $A = $(`select#Gerentes, select#Jefes`)
                $A.attr(`disabled`, true)
                $A.removeAttr(`requires`)
                $A.val(``)
                return "0"
            }
        }[$this.val().toUpperCase()] ?? "como eh posible ete suceso")
    })

    const $Aprobador = $(`select#Gerentes, select#Jefes`)
    $Aprobador.on(`change`, function () {
        const $this = $(this)
        const $id_aprobador = $(`[name="data[id_aprobador]"]`)
        $id_aprobador.val($this.val())
    })

    const $id_aprobador = $(`[name="data[id_aprobador]"]`)
    $id_aprobador.on(`change`, function () {
        const $this = $(this)

        // const $selectA = $(`[value="${$this.val()}"]`).parent()

        $selectA = $($(`select#Gerentes, select#Jefes`)
            .find(`[value="${$this.val()}"]`)
            .get(0).parentNode
        ).val($this.val());

        console.log($selectA);


        $selectA.removeAttr(`disabled`)
        $selectA.attr(`required`, true)
    })


    $("#rechazar, #aprobar").on("click", async function () {
        const $this = $(this)
        const action = $this.data("action")

        if (action == "aprobar_horas") aprueba(action)
        else if (action == "rechazar_horas") rechaza(action)
    })

    $("#STodo, #DTodo").on("click", function () {
        const $this = $(this)
        const action = $this.data("action")
        $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=${action}`, {
            dataType: "JSON",
            success: (response) => {
                if (response.status && response.status === true) updateDatatable()
            }
        })
    })

    const $formExcel = $(`#formExcel`)
    $formExcel.on(`submit`, function (e) {
        e.preventDefault()
        const $this = $(this)
        const type = $(this).data(`type`)
        const $table = $("#tableExcel")
        const $thead = $table.find(`thead`).html(``)
        const $tbody = $table.find(`tbody`).html(``)
        const $tfoot = $table.find(`tfoot`).html(``)

        switch (type) {
            case 1:
                $table.find("thead, tfoot").remove()
                $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=Excel${type}`, {
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    type: "POST",
                    data: new FormData(this),
                    success: (response) => {
                        if (typeof response === "object") response.forEach((fData, i) => {
                            fData.codigos.split(`/`).forEach((sData, i) => {

                                t = getTotalHours(sData, fData).split(".")
                                t_e = t[0] ?? 0
                                t_d = t[1] ?? 0

                                $tbody.append(`
                                    <tr>
                                        <td>${sData}</td>
                                        <td>${fData.CC}</td>
                                        <td>${fData.fecha_inicio}</td>
                                        <td>${fData.fecha_fin}</td>
                                        <td>${fData.fecha_fin}</td>
                                        <td>Plano Horas Extras</td>
                                        <td>0</td>
                                        <td>4</td>
                                        <td></td>
                                        <td></td>
                                        <td>${Number(t_e)}</td>
                                        <td>${Number(t_d * 6)}</td>
                                        <td>OCASIONAL</td>
                                    </tr>
                                `)
                            })
                        })
                    }
                })
                break
            case 2:
                $table.find("tfoot").remove()
                $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=Excel${type}`, {
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    type: "POST",
                    data: new FormData(this),
                    success: (response) => {
                        if (typeof response === "object") response.forEach((data, i) => {

                            $tbody.append(`
                                    <tr>
                                        <td style="width: auto">${data.id}</td>
                                        <td style="width: auto">${data.correoEmpleado}</td>
                                        <td style="width: auto">${data.CC}</td>
                                        <td style="width: auto">${data.cargo}</td>
                                        <td style="width: auto">${data.fecha_inicio}</td>
                                        <td style="width: auto">${data.fecha_fin}</td>
                                        <td style="width: auto">${data.ceco}</td>
                                        <td style="width: auto">${data.estado}</td>
                                        <td style="width: auto">${data.aprobador}</td>
                                        <td style="width: auto">${data.Total_descuento}</td>
                                        <td style="width: auto">${data.Total_Ext_Diu_Ord}</td>
                                        <td style="width: auto">${data.Total_Ext_Noc_Ord}</td>
                                        <td style="width: auto">${data.Total_Ext_Diu_Fes}</td>
                                        <td style="width: auto">${data.Total_Ext_Noc_Fes}</td>
                                        <td style="width: auto">${data.Total_Rec_Noc}</td>
                                        <td style="width: auto">${data.Total_Rec_Fes_Diu}</td>
                                        <td style="width: auto">${data.Total_Rec_Fes_Noc}</td>
                                        <td style="width: auto">${data.Total_Rec_Ord_Fes_Noc}</td>
                                    </tr>
                                `)

                        })
                    }
                })
                break
            default:
                break
        }
    })

})
//----------------------------------------------------------------------------------------------------------------//
const add = async () => {
    const $tableDatail = $(`#tableDatail`)
    const $tbody = $tableDatail.find(`tbody`)
    const $tr = $tbody.find(`tr`).eq(0)
    const clone = $tr.clone()

    clone.find(`[contenteditable]`).text(``)
    clone.find(`[type="number"]`).text(`0`)
    clone.appendTo($tbody).find(`input, button`).val("").removeAttr(`disabled`)
    await addHours()
}, remove = async (e) => {
    $(e.parentNode.parentNode).remove()
    await addHours()
}, codes = () => {
    return [
        {
            find: "descuento", code: ""
        },
        {
            find: "Ext_Diu_Ord", code: "11001"
        },
        {
            find: "Ext_Noc_Ord", code: "11002"
        },
        {
            find: "Ext_Diu_Fes", code: "11003"
        },
        {
            find: "Ext_Noc_Fes", code: "11004"
        },
        {
            find: "Rec_Noc", code: "11501"
        },
        {
            find: "Rec_Fes_Diu", code: "11502"
        },
        {
            find: "Rec_Fes_Noc", code: "11503"
        },
        {
            find: "Rec_Ord_Fes_Noc", code: "11504"
        },
    ]
}, addHours = async () => {
    const horas = []
    const arraySum = codes().forEach((x, i) => {
        horas.push({
            element: x.find,
            suma: 0,
            codigo: x.code
        })

        $(`[name="HorasExtra[${x.find}][]"]`).each(function () {
            horas[i]["suma"] += Number($(this).text())
        })

        $(`[name="data[Total_${x.find}]"]`).text(horas[i]["suma"])
    })
    //----descuentos----//
    total_des = 0
    horas.filter((x) => {
        return x.element.includes("descuento")
    }).forEach((q) => { total_des += q.suma })
    $(`[name="data[Total_Descuentos]"]`).text(total_des)

    //----Extras----//
    total_Ext = 0
    horas.filter((x) => {
        return x.element.includes("Ext_")
    }).forEach((q) => { total_Ext += q.suma })
    $(`[name="data[Total_Extras]"]`).text(total_Ext)

    //----Recaudos----//
    total_Rec = 0
    horas.filter((x) => {
        return x.element.includes("Rec_")
    }).forEach((q) => { total_Rec += q.suma })
    $(`[name="data[Total_Recargos]"]`).text(total_Rec)

    //----Codigos----//
    codigos = []
    horas.filter((x) => {
        return x.suma > 0 && x.codigo && x.codigo !== ""
    }).forEach((q) => { codigos.push(q.codigo) })
    $(`[name="data[codigos]"]`).val(codigos.join("/"))

    //----Total----//
    $(`[name="data[Total_Horas]"]`).text(Number(total_des + total_Ext + total_Rec))
}, getDates = () => {
    let MesR = $('#mes').val().split("-")
    if (MesR.length != 2)
        return [``, ``]
    else {
        let Año = MesR[0]
        let Mes = MesR[1]
        let MesA = Number(Mes - 1)
        let AñoA = MesA == 0 ? Number(Año - 1) : Año
        MesA = MesA == 0 ? 12 : (MesA <= 9 ? `0${MesA}` : `${MesA}`)
        return [
            `${AñoA}-${MesA}-01`,
            `${Año}-${Mes}-${lastDay(Año, Mes)}`
        ]
    }

}, dates = () => {
    let f = getDates()
    $(`[name="data[fecha_inicio]"]`).val(f[0])
    $(`[name="data[fecha_fin]"]`).val(f[1])

    if ((f[0] ?? false) && (f[1] ?? false))
        $(`[name="HorasExtra[fecha][]"]`).attr("min", f[0]).attr("max", f[1])
    else
        $(`[name="HorasExtra[fecha][]"]`).removeAttr("min").removeAttr("max")
}, loadData = (e = 0) => {
    loadData_ReportesHE(e)
    loadData_HorasExtra(e)
}, loadData_ReportesHE = (e) => {
    $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=getReportesHE`, {
        type: "POST",
        dataType: "JSON",
        data: { id: e },
        success: function (response) {
            response.forEach((x) => {
                for (data in x) {
                    $find = $(`[name="data[${data}]"]`)
                    $find2 = $(`[name="data[${data}]"][value="${x[data]}"]`)
                    switch ($find.prop(`tagName`)) {
                        case `INPUT`:
                        case `SELECT`:
                            if ($find.length)
                                if ($find.attr("type") === "radio")
                                    $find2.prop("checked", true).trigger("input").trigger("change")
                                else
                                    $find.val(x[data]).trigger("input").trigger("change")
                            break
                        default:
                            if ($find.length) $find.val(x[data]).html(x[data]).trigger("input").trigger("change")
                            break
                    }
                }
            })
        }
    })
}, loadData_HorasExtra = (e) => {
    $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=getHorasExtra`, {
        type: "POST",
        dataType: "JSON",
        data: { id: e },
        success: async (response) => {
            response.forEach(async (x, i) => {
                if (i !== 0) await add()
                for (data in x) {
                    $find = $(`[name="HorasExtra[${data}][]"]:eq(${i})`)
                    if ($find.length) $find.val(x[data]).html(x[data]).trigger("input").trigger("change")
                }
            })
            dates()
            await addHours()
        }
    })
}, approvedRequest = async (find = "") => {
    const listApprovers = $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=approvedRequest`, {
        type: `POST`,
        dataType: `JSON`,
        data: { find: find },
        async: false
    }).responseJSON

    options = {}
    for (data in listApprovers) options[listApprovers[data][`id`]] = listApprovers[data][`nombre`]

    const { value: Approver } = await Swal.fire({
        title: `Seleccione el aprobador`,
        input: `select`,
        inputAttributes: {
            id: `swal2-select-list-approvers`
        },
        inputOptions: options,
        customClass: {
            input: `form-control-lg`
        }
    })

    return Approver
}, getTotalHours = (code, data) => {
    const arrayCodes = codes().filter((x) => {
        return x.code == code
    })

    if (arrayCodes.length) return data[`Total_${arrayCodes[0]["find"]}`] ?? 0
}, aprueba = async (action) => {
    const request = await fetch(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=pendientes`)
    const response = await request.json()

    const aprobadores = {}

    /*----APROBADORES----*/
    if (response.filter((q) => { // JEFE
        return q.estado == "APROBACION_JEFE" || q.estado == "RECHAZO_GERENTE"
    }).length) aprobadores[":GERENTE"] = await approvedRequest("GERENTE")
    // --------------------------------------------------------------------
    if (response.filter((q) => { // GERENTE
        return q.estado == "APROBACION_GERENTE" || q.estado == "RECHAZO_RH" || q.estado == "RECHAZO_CONTABLE"
    }).length) aprobadores[":RH"] = await approvedRequest("RH")
    // --------------------------------------------------------------------
    if (response.filter((q) => { // RH
        return q.estado == "APROBACION_RH"
    }).length) aprobadores[":CONTABLE"] = await approvedRequest("CONTABLE")
    // --------------------------------------------------------------------
    if (response.filter((q) => { // CONTABLE
        return q.estado == "APROBACION_CONTABLE"
    }).length) aprobadores[":APROBADO"] = "?"
    /*----APROBADORES----*/

    $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=${action}`, {
        dataType: "JSON",
        type: "POST",
        data: {
            aprobadores: aprobadores
        },
        success: (response) => {
            if (response.status && response.status === true) updateDatatable()
        }
    })

}, rechaza = async (action) => {
    const request = await fetch(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=pendientes`)
    const response = await request.json()

    const aprobadores = {}

    const motivo = await swalFire({
        title: `Motivo de rechazo`,
        inputValidator: (value) => { if (!value) return "Motivo es obligatorio" }
    })

    let tipoRechazo;

    if (motivo) {
        /*----APROBADORES----*/
        const Rech_jefe = response.filter((q) => {
            return q.estado == "APROBACION_JEFE" || q.estado == "RECHAZO_GERENTE"
        })
        if (Rech_jefe.length) $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=${action}&type=empleado`, {
            type: "POST",
            dataType: "JSON",
            data: {
                motivo: motivo,
                rechazar: Rech_jefe
            },
            success: (response) => {
                updateDatatable()
            }
        })
        // --------------------------------------------------------------------
        const Rech_gerente = response.filter((q) => {
            return q.estado == "APROBACION_GERENTE" || q.estado == "RECHAZO_RH" || q.estado == "RECHAZO_CONTABLE"
        })
        if (Rech_gerente.length) {
            // jefe 
            tipoRechazo = await swalFire({
                title: `¿Realizar rechazo a un jefe?`,
                showDenyButton: true,
                confirmButtonText: 'Si',
                denyButtonText: `No`,
            }, ["input"])

            if (tipoRechazo === true) {
                const aprobador = await approvedRequest("JEFE")
                if (aprobador && aprobador.length) $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=${action}&type=jefe`, {
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        motivo: motivo,
                        rechazar: Rech_gerente,
                        aprobador: aprobador
                    },
                    success: (response) => {
                        updateDatatable()
                    }
                })
            }
            else $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=${action}&type=empleado`, {
                type: "POST",
                dataType: "JSON",
                data: {
                    motivo: motivo,
                    rechazar: Rech_gerente
                },
                success: (response) => {
                    updateDatatable()
                }
            })
        }
        // --------------------------------------------------------------------
        const Rech_rh = response.filter((q) => {
            return q.estado == "APROBACION_RH"
        })
        if (Rech_rh.length) {
            const aprobador = await approvedRequest("GERENTE")
            if (aprobador && aprobador.length) $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=${action}&type=gerente`, {
                type: "POST",
                dataType: "JSON",
                data: {
                    motivo: motivo,
                    rechazar: Rech_rh,
                    aprobador: aprobador
                },
                success: (response) => {
                    updateDatatable()
                }
            })
        }
        // --------------------------------------------------------------------
        const Rech_contable = response.filter((q) => {
            return q.estado == "APROBACION_CONTABLE"
        })
        if (Rech_contable.length) {
            const aprobador = await approvedRequest("GERENTE")
            if (aprobador && aprobador.length) $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=${action}&type=rh`, {
                type: "POST",
                dataType: "JSON",
                data: {
                    motivo: motivo,
                    rechazar: Rech_contable,
                    aprobador: aprobador
                },
                success: (response) => {
                    updateDatatable()
                }
            })
        }
        /*----APROBADORES----*/
    }
}, getSystemConfig = async () => {
    const request = await fetch(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/horasExtras/backend.php?action=getSystemConfig`)
    const response = await request.json()
    return response;
}