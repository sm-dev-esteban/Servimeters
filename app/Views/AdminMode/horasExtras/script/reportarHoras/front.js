$(document).ready(async () => {
    const handleBeforeSend = () => {
        const codes = []
        const $codigos = $(`[name="data[codigos]"]`)
        $("[data-code]").each((_, td) => {
            const $td = $(td)
            const num = Number($td.text())
            const code = $td.data("code")
            if (!isNaN(num) && num > 0 && code !== "") codes.push(code)
        })

        if (codes.length) $codigos.val(JSON.stringify(codes))
        else $codigos.val("")
    }, handleSuccess = (response) => {
        if (response.status || false) {
            const status = response.status.toLowerCase()
            const message = response.message

            if (status === "success" || status === "error") alerts.sweetalert2({ title: message, icon: status })
            else console.error(message)

        }
    }

    const alerts = new Alerts()
    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/app/Views/AdminMode/horasExtras/script/reportarHoras/back.php`

    const $form = $(`form`)
    const formMode = $form.data("mode")

    const form = new Form(`${URL_BACKEND}?action=${formMode}`, $form)

    form.sendContentEditable = true
    form.setAjaxSettings({
        beforeSend: handleBeforeSend,
        success: handleSuccess
    })

    // autoComplete
    const $correo = $(`[name="data[correoEmpleado]"]`)
    const $CC = $(`[name="data[CC]"]`)
    const $proyecto = $(`[name="data[proyecto]"]`)

    $form.on("submit", form.submit)

    // autoComplete
    $correo.autoComplete({
        event: `input`,
        column: `mail`
    })
    $CC.autoComplete({
        event: "input",
        column: "CC",
        mode: "SQL",
        url: `${URL_BACKEND}?action=autoComplete`
    })
    $proyecto.autoComplete({
        event: "input",
        column: "proyecto",
        mode: "SQL",
        url: `${URL_BACKEND}?action=autoComplete`
    })
})
