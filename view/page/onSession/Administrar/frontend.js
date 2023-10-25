/*-- 2023-08-27 22:29:34 --*/

$(document).ready(async () => {
    ldapAutoComplete([
        {
            element: `#nombre`,
            event: `input`,
            search: `name`
        }, {
            element: `#mail`,
            event: `input`,
            search: `mail`
        }
    ])

    const $form = $(`form[data-action]`)
    $form.on("submit", function (e) {
        e.preventDefault()
        const $this = $(this)
        $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/Administrar/backend.php?action=${$this.data("action")}`, {
            cache: false,
            processData: false,
            contentType: false,
            dataType: "JSON",
            type: "POST",
            data: new FormData(this),
            success: (response) => {
                if (response.status && response.status === true) updateDatatable()
            }
        })
    })

    const $table = $(`table[data-action]`)
    $table.DataTable($.extend(GETCONFIG("DATATABLE"), {
        "processing": true,
        "serverSide": true,
        "order": [[0, `desc`]],
        "ajax": `${GETCONFIG("SERVER_SIDE")}/View/page/onSession/Administrar/backend.php?action=${$table.data("action")}`
    })).buttons().container().appendTo($('.col-sm-6:eq(0)'))

    const $select = $(`select`)
    // $select.select2()
})

const ChangeMode = (i) => {
    const $show = $(`[data-show="${window.btoa(i)}"]`)
    const $edit = $(`[data-edit="${window.btoa(i)}"]`)
    const $show_hide = $(`[data-show="${window.btoa(i)}"], [data-edit="${window.btoa(i)}"]`)

    $show_hide.toggleClass("show").toggleClass("hide")

    if ($show.hasClass("show")) $show.show("slow")
    if ($show.hasClass("hide")) $show.hide("slow")

    if ($edit.hasClass("show")) $edit.show("slow")
    if ($edit.hasClass("hide")) $edit.hide("slow")

}, ConfirmUpdate = (i, action) => {
    const $show = $(`[data-show="${window.btoa(i)}"]`)
    const $edit = $(`[data-edit="${window.btoa(i)}"]`)

    var formData = new FormData()

    formData.append(`id`, i)

    $edit.each(function (i) {
        $this = $(this)
        $inputs = $this.find("[name]")
        if ($inputs.length) $inputs.each(function (q) {
            $this = $(this)
            formData.append(`data[${$this.attr("name")}]`, $this.val())
        })
    })

    $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/Administrar/backend.php?action=${action}`, {
        cache: false,
        processData: false,
        contentType: false,
        dataType: "JSON",
        type: "POST",
        data: formData,
        success: (response) => {
            if (response.status && response.status === true) {
                ChangeMode(i)
                $edit.each(function (i) {
                    $this = $(this)
                    $inputs = $this.find("[name]")
                    if ($inputs.length) $inputs.each(function (q) {
                        $this = $(this)
                        $showIn = $($show.get(i))
                        switch ($this.prop(`tagName`)) {
                            case "INPUT":
                                $showIn.text($this.val())
                                break
                            case "SELECT":
                                $showIn.text($this.find(`option:selected`).text())
                                break
                            default:
                                updateDatatable()
                                break
                        }
                    })
                })
            }
        }
    })
}