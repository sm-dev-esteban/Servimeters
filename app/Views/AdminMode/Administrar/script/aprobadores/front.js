$(document).ready(async () => {
    const alert = new Alerts()
    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/app/Views/AdminMode/Administrar/script/aprobadores/back.php`

    const handleSuccess = (response) => {
        if (response.status || false) {
            const status = response.status.toLowerCase()
            const message = response.message

            if (status === "success" || status === "error") alert.sweetalert2({ title: message, icon: status })
            else console.error(message);

            if (status === "success") updateDatatable()
        }
    }

    const $table = $(`table[data-action]`)
    const tableAction = $table.data("action")

    const $form = $(`form[data-action]`)
    const formAction = $form.data("action")

    const form = new Form(`${URL_BACKEND}?action=${formAction}`, $form)

    form.setAjaxSettings({ success: handleSuccess })

    $form.on("submit", form.submit)

    const dataTable = $table.DataTable($.extend(DATATABLE_ALL, {
        processing: true,
        serverSide: true,
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        ajax: `${URL_BACKEND}?action=${tableAction}`
    }))

    $('.dataTables_filter').remove();

    $(`.content .card-header input[type="search"]`).on(`input`, (e) => dataTable.search(e.target.value).draw())
    $(`button[data-action="refresh"]`).on(`click`, () => updateDatatable($table))

    ldapAutoComplete([
        {
            element: `#nombre`,
            event: `input`,
            column: `name`
        }, {
            element: `#email`,
            event: `input`,
            column: `mail`
        }
    ])
})
