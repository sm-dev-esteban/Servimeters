$(document).ready(async () => {
    const aprobar = async (e) => {
        const aprobacion = {}
        const selecteds = rowsSelecteds()

        if (!selecteds.length) {
            alerts.sweetalert2({
                icon: "info",
                title: "No hay filas seleccionadas."
            })

            return
        }

        // Jefe > Gerente
        const paraGerente = selecteds.filter(el => $(el).find("[data-estado='APROBACION_JEFE'], [data-estado='RECHAZO_GERENTE']").length > 0)
        console.log("para Gerente:", paraGerente.length)

        // Gerente > RH
        const paraRH = selecteds.filter(el => $(el).find("[data-estado='APROBACION_GERENTE'], [data-estado='RECHAZO_RH'], [data-estado='RECHAZO_CONTABLE']").length > 0)
        console.log("para RH:", paraRH.length)

        // RH > Contable
        const paraContable = selecteds.filter(el => $(el).find("[data-estado='APROBACION_RH']").length > 0)
        console.log("para Contable:", paraContable.length)

        // Contable > Aprobación
        const paraAprobacion = selecteds.filter(el => $(el).find("[data-estado='APROBACION_CONTABLE']").length > 0)
        console.log("para Aprobacion:", paraAprobacion.length)

        const gestionar = [
            {
                main: "GERENTE",
                rows: paraGerente,
                listCheck: listGerente,
                listOption: listSelectGerente
            }, {
                main: "RH",
                rows: paraRH,
                listCheck: listRH,
                listOption: listSelectRH
            }, {
                main: "CONTABLE",
                rows: paraContable,
                listCheck: listContable,
                listOption: listSelectContable
            }, {
                main: "APROBADO",
                rows: paraAprobacion
            }
        ];

        for (const data in gestionar) {
            const main = gestionar[data].main
            const rows = gestionar[data].rows
            const listCheck = gestionar[data].listCheck
            const listOption = gestionar[data].listOption

            if (rows.length) {
                if (!listCheck && !listOption) {
                    aprobacion[`:${main}`] = {
                        aprobador: 1,
                        ids: []
                    };

                    rows.each(el => {
                        const $el = $(el)
                        const id = $el.find("[data-id]:eq(0)").data("id")
                        if (id) aprobacion[`:${main}`].ids.push(id)
                    })
                } else {
                    if (!listCheck.length) alerts.sweetalert2({
                        icon: "info",
                        title: `No hay aprobadores disponibles.`
                    }); else {
                        const id_aprobador = await solicitarAprobador({
                            title: `Seleccione un aprobador para "${main}"`,
                            inputOptions: listOption
                        })

                        if (!id_aprobador) alerts.sweetalert2({
                            icon: "info",
                            title: "Acción rechazada."
                        }); else {

                            aprobacion[`:${main}`] = {
                                aprobador: id_aprobador,
                                ids: []
                            };

                            rows.each(el => {
                                const $el = $(el)
                                const id = $el.find("[data-id]:eq(0)").data("id")
                                if (id) aprobacion[`:${main}`].ids.push(id)
                            })

                        }
                    }
                }
            }
        }

        if (Object.keys(aprobacion).length) $.ajax(`${URL_BACKEND}?action=aprobar`, {
            type: "POST",
            dataType: "JSON",
            data: aprobacion,
            success: (response) => {
                const message = response.message
                alerts.sweetalert2({ title: message })
            }
        })

    }, rechazar = async (e) => {
        const rechazo = {}
        const selecteds = rowsSelecteds()

        if (!selecteds.length) {
            alerts.sweetalert2({
                icon: "info",
                title: "No hay filas seleccionadas."
            })

            return
        }

        // Jefe > Empleado
        const paraEmpleado = selecteds.filter(el => $(el).find("[data-estado='APROBACION_JEFE'], [data-estado='RECHAZO_GERENTE']").length > 0)

        // Gerente > Jefe
        const paraJefe = selecteds.filter(el => $(el).find("[data-estado='APROBACION_GERENTE'], [data-estado='RECHAZO_RH'], [data-estado='RECHAZO_CONTABLE']").length > 0)

        // RH > Gerente
        const paraGerente = selecteds.filter(el => $(el).find("[data-estado='APROBACION_RH']").length > 0)

        // Contable > Rechazo
        const paraRechazo = selecteds.filter(el => $(el).find("[data-estado='APROBACION_CONTABLE']").length > 0)

        const gestionar = [
            {
                main: "EMPLEADO",
                rows: paraEmpleado
            }, {
                main: "JEFE",
                rows: paraJefe,
                listCheck: listJefe,
                listOption: listSelectJefe
            }, {
                main: "GERENTE",
                rows: paraGerente,
                listCheck: listGerente,
                listOption: listSelectGerente
            }, {
                main: "RECHAZO",
                rows: paraRechazo
            }
        ]

        for (const data in gestionar) {
            const main = gestionar[data].main
            const rows = gestionar[data].rows
            const listCheck = gestionar[data].listCheck
            const listOption = gestionar[data].listOption

            if (rows.length) {
                if (!listCheck && !listOption) {
                    rechazo[`:${main}`] = {
                        aprobador: 1,
                        ids: []
                    };

                    rows.each(el => {
                        const $el = $(el)
                        const id = $el.find("[data-id]:eq(0)").data("id")
                        if (id) rechazo[`:${main}`].ids.push(id)
                    })
                } else {
                    if (!listCheck.length) alerts.sweetalert2({
                        icon: "info",
                        title: `No hay aprobadores disponibles.`
                    }); else {
                        const id_aprobador = await solicitarAprobador({
                            title: `Seleccione un aprobador para "${main}"`,
                            inputOptions: listOption
                        })

                        if (!id_aprobador) alerts.sweetalert2({
                            icon: "info",
                            title: "Acción rechazada."
                        }); else {

                            rechazo[`:${main}`] = {
                                aprobador: id_aprobador,
                                ids: []
                            };

                            rows.each(el => {
                                const $el = $(el)
                                const id = $el.find("[data-id]:eq(0)").data("id")
                                if (id) rechazo[`:${main}`].ids.push(id)
                            })

                        }
                    }
                }
            }
        }

        if (Object.keys(rechazo).length) $.ajax(`${URL_BACKEND}?action=rechazar`, {
            type: "POST",
            dataType: "JSON",
            data: rechazo,
            success: (response) => {
                const message = response.message
                const status = response.status
                alerts.sweetalert2({
                    title: message, icon: status
                })
            }
        })
    }

    const solicitarAprobador = async ({ title = "Seleccione al Aaprobador", inputOptions }) => {
        return await swalFire({
            title: title,
            input: "select",
            inputOptions: inputOptions
        })
    }

    const seleccionar = (e) => rowsNoSelecteds().each(el => el.classList.add("selected"))
    const deseleccionar = (e) => rowsSelecteds().each(el => el.classList.remove("selected"))

    const rowsSelecteds = () => dataTable.rows(".selected").nodes()
    const rowsNoSelecteds = () => dataTable.rows(":not(.selected)").nodes()

    const alerts = new Alerts()
    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/app/Views/AdminMode/horasExtras/script/gestion/back.php`

    // table
    const $table = $(`table[data-action]`)
    const tableAction = $table.data("action")
    // btns action
    const $btnAprobar = $(`.controls button[data-action="aprobar_horas"]`);
    const $btnRechazar = $(`.controls button[data-action="rechazar_horas"]`);
    const $btnSeleccionar = $(`.controls button[data-action="seleccionar_horas"]`);
    const $btnDeseleccionar = $(`.controls button[data-action="deseleccionar_horas"]`);
    const $btnRecargar = $(`.controls button[data-action="refresh"]`);

    const dataTable = $table.DataTable($.extend(DATATABLE_ALL, {
        processing: true,
        serverSide: true,
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        select: {
            style: "multi"
        },
        ajax: `${URL_BACKEND}?action=${tableAction}`
    }))

    $('.dataTables_filter').remove();
    $(`.content .card-header input[type="search"]`).on(`input`, (e) => dataTable.search(e.target.value).draw())
    $btnAprobar.on("click", aprobar)
    $btnRechazar.on("click", rechazar)
    $btnSeleccionar.on("click", seleccionar)
    $btnDeseleccionar.on("click", deseleccionar)
    $btnRecargar.on(`click`, () => updateDatatable($table))

})