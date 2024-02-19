$(document).ready(async () => {
    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/app/Views/ClientMode/script/index/back.php`

    const $form = $(`#signIn`)
    const form = new Form(URL_BACKEND, $form)

    form.setAjaxSettings({
        success: (response) => {
            if (response.status === true) location.href = Config.BASE_SERVER
        }
    })

    $form.on("submit", form.submit)
})