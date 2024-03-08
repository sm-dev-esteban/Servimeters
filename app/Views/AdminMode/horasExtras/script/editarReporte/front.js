$(document).ready(async () => {
    const handleSuccess = (response) => {
        if (response.status || false) {
            const status = response.status.toLowerCase()
            const message = response.message

            if (status === "success" || status === "error") alerts.sweetalert2({ title: message, icon: status })
            else console.error(message)

        }
    }, loadData = async (i) => {
        if (i) {
            await loadReportesHE(i)
            await loadHorasExtra(i)
        }
    }, loadReportesHE = async (i) => form.preLoadData(`${URL_BACKEND}?action=getReportesHE&id=${i}`),
        loadHorasExtra = async (i) => $.ajax(`${URL_BACKEND}?action=getHorasExtra&id=${i}`, {
            dataType: "JSON",
            success: (response) => {
                response.forEach((x, i) => {
                    if (i > 0) $btnAdd.click()
                    for (const data in x) $(`[name="HorasExtra[${data}][]"]:eq(${i})`)
                        .val(x[data])
                        .html(x[data])
                        .trigger("input")
                })
            }
        })

    const alerts = new Alerts()
    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/app/Views/AdminMode/horasExtras/script/editarReporte/back.php`

    const $form = $(`form`)
    const formMode = $form.data("mode")

    const $btnAdd = $(`[data-action="agregar"]`)

    const paramsString = location.search.replace("?", "")
    const searchParams = new URLSearchParams(paramsString)

    const report = searchParams.get("report")

    const form = new Form(`${URL_BACKEND}?action=${formMode}&id=${report}`, $form)

    form.sendContentEditable = true
    form.setAjaxSettings({ success: handleSuccess })

    await loadData(report)

    $form.on("submit", form.submit)
})
