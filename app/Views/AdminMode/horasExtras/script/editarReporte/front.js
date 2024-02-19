$(document).ready(async () => {
    const handleSuccess = (response) => {
        if (response.status || false) {
            const status = response.status.toLowerCase()
            const message = response.message

            if (status === "success" || status === "error") alerts.sweetalert2({ title: message, icon: status })
            else console.error(message)

        }
    }

    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/app/Views/AdminMode/horasExtras/script/editarReporte/back.php`

    const $form = $(`form`)
    const formMode = $form.data("mode")

    const form = new Form(`${URL_BACKEND}?action=${formMode}`, $form)

    form.sendContentEditable = true
    form.setAjaxSettings({ success: handleSuccess })

    $form.on("submit", form.submit)
})
