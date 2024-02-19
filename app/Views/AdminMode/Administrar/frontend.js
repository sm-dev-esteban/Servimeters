$(document).ready(async () => {
    $(`select`).select2()
    $(`[data-toggle="popover"]`).popover({
        container: "body",
        html: true
    })
})

var alertMain = new Alerts()
var Config = CONFIG()
var URL_BACKEND = `${Config.BASE_SERVER}/app/Views/AdminMode/Administrar/backend.php`

var ChangeMode = (i) => {
    const $show = $(`[data-show=${i}]`)
    const $edit = $(`[data-edit=${i}]`)
    const $show_hide = $(`[data-show=${i}], [data-edit=${i}]`)

    $show_hide.toggleClass("show").toggleClass("hide")

    if ($edit.hasClass("hide")) $edit.hide("slow", () => { if ($show.hasClass("show")) $show.show("slow") })
    if ($show.hasClass("hide")) $show.hide("slow", () => { if ($edit.hasClass("show")) $edit.show("slow") })

}, ConfirmUpdate = (i, action) => {
    const $trAsForm = $(`[data-show=${i}]`).eq(0).closest("tr")
    const form = new Form(`${URL_BACKEND}?action=${action}&id=${i}`, $trAsForm)

    form.sendContentEditable = true
    form.setAjaxSettings({
        success: (response) => {
            const status = response.status.toLowerCase()
            const message = response.message

            if (status === "success" || status === "error") {
                ChangeMode(i)
                alertMain.sweetalert2({ title: message, icon: status })

                // contenteditable
                $trAsForm.find("[contenteditable][name]").each((i, el) => {
                    const $el = $(el)
                    const originalText = $el.data("original-text")
                    const newText = $el.text()

                    $el.attr("contenteditable", "false")
                    if (originalText !== newText) $el.data("original-text", newText)
                })

                // select
                $trAsForm.find(`[data-edit=${i}] select[data-id=${i}]`).each((i, el) => {
                    const $select = $(el)
                    console.log($select.find("[selected]").text());
                    $select.closest("td").find("[data-show]").text($select.find(":selected").text())
                })
            } else console.error(message);
        }
    })
    form.submit()
}

enableContentEditable = (i) => $(`[contenteditable][name][data-id=${i}]`).attr("contenteditable", "true")
disableContentEditable = (i) => $(`[contenteditable][name][data-id=${i}]`).each((i, el) => {
    const $el = $(el)
    const originalText = $el.data("original-text")
    const newText = $el.text()

    $el.attr("contenteditable", "false")
    if (originalText !== newText) $el.text(originalText)
})