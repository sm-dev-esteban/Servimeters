$(document).ready(async () => {
    // Funciones
    const aprobar = () => {
        const ids = []
        const selecteds = rowsSelecteds()

        if (!selecteds.length) {
            alerts.sweetalert2({
                icon: "info",
                title: "No hay filas seleccionadas."
            })

            return
        }

        selecteds.each(tr => ids.push($(tr).find("[data-id]:eq(0)").data("id")))

        if (ids.length) $.ajax(`${URL_BACKEND}?action=aprobar`, {
            type: "POST",
            dataType: "JSON",
            data: { ids: ids },
            success: (response) => {
                const { message: message, status: status } = response;
                alerts.sweetalert2({
                    title: message,
                    icon: status
                })

                if (status === "success") updateDatatable($table)
            }
        })
    }, rechazar = () => {
        const ids = []
        const selecteds = rowsSelecteds()

        if (!selecteds.length) {
            alerts.sweetalert2({
                icon: "info",
                title: "No hay filas seleccionadas."
            })

            return
        }

        selecteds.each(tr => ids.push($(tr).find("[data-id]:eq(0)").data("id")))

        if (ids.length) $.ajax(`${URL_BACKEND}?action=rechazar`, {
            type: "POST",
            dataType: "JSON",
            data: { ids: ids },
            success: (response) => {
                const { message: message, status: status } = response;
                alerts.sweetalert2({
                    title: message,
                    icon: status
                })

                if (status === "success") updateDatatable($table)
            }
        })
    }, cancelar = () => {
        const ids = []
        const selecteds = rowsSelecteds()

        if (!selecteds.length) {
            alerts.sweetalert2({
                icon: "info",
                title: "No hay filas seleccionadas."
            })

            return
        }

        selecteds.each(tr => ids.push($(tr).find("[data-id]:eq(0)").data("id")))

        if (ids.length) $.ajax(`${URL_BACKEND}?action=cancelar`, {
            type: "POST",
            dataType: "JSON",
            data: { ids: ids },
            success: (response) => {
                const { message: message, status: status } = response;
                alerts.sweetalert2({
                    title: message,
                    icon: status
                })

                if (status === "success") updateDatatable($table)
            }
        })
    };

    const seleccionar = () => rowsNoSelecteds().each(el => el.classList.add("selected"))
    const deseleccionar = () => rowsSelecteds().each(el => el.classList.remove("selected"))

    const rowsSelecteds = () => dataTable.rows(".selected").nodes()
    const rowsNoSelecteds = () => dataTable.rows(":not(.selected)").nodes()

    // Variables
    const alerts = new Alerts()
    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/app/Views/AdminMode/solicitudPersonal/script/aprobarSolicitud/back.php`

    const $table = $(`table[data-action]`)
    const tableAction = $table.data("action")

    const $btnAprobar = $(`.controls button[data-action="aprobar"]`);
    const $btnRechazar = $(`.controls button[data-action="rechazar"]`);
    const $btnCancelar = $(`.controls button[data-action="cancelar"]`);
    const $btnSeleccionar = $(`.controls button[data-action="seleccionar"]`);
    const $btnDeseleccionar = $(`.controls button[data-action="deseleccionar"]`);
    const $btnRecargar = $(`.controls button[data-action="refresh"]`);

    const dataTable = $table.DataTable($.extend(DATATABLE_ALL, {
        processing: true,
        serverSide: true,
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        select: { style: "multi" },
        ajax: `${URL_BACKEND}?action=${tableAction}`
    }))

    // Ejecucion
    $('.dataTables_filter').remove();
    $(`.content .card-header input[type="search"]`).on(`input`, (e) => dataTable.search(e.target.value).draw())
    $btnAprobar.on("click", aprobar)
    $btnRechazar.on("click", rechazar)
    $btnCancelar.on("click", cancelar)
    $btnSeleccionar.on("click", seleccionar)
    $btnDeseleccionar.on("click", deseleccionar)
    $btnRecargar.on(`click`, () => updateDatatable($table))
})