/*-- 2023-09-22 09:16:22 --*/

$(document).ready(async () => {
    ldapAutoComplete([
        {
            element: `[name="data[solicitadoPor]"]`,
            event: `input`,
            search: `name`
        }, {
            element: `[name="data[emailsolicitadoPor]"]`,
            event: `input`,
            search: `mail`
        }, {
            element: `[name="data[reemplazaA]"]`,
            event: `input`,
            search: `name`
        }
    ])

    $(`#formAdjuntos`).createDropzone({
        table: "requisicion_hojas_de_vida"
    })

    const $form = $(`form[data-action]`)
    $form.on("submit", function (e) {
        e.preventDefault()
        const $this = $(this)
        $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/solicitudPersonal/solicitud/backend.php?action=${$this.data("action")}`, {
            cache: false,
            processData: false,
            contentType: false,
            dataType: "JSON",
            type: "POST",
            data: new FormData(this),
            success: (response) => {
                console.log(response)
            }
        })
    })

    const $table = $(`table[data-action]`)
    $table.DataTable($.extend(GETCONFIG("DATATABLE"), {
        "processing": true,
        "serverSide": true,
        "order": [[0, `desc`]],
        "ajax": `${GETCONFIG("SERVER_SIDE")}/View/page/onSession/solicitudPersonal/solicitud/backend.php?action=${$table.data("action")}`
    })).buttons().container().appendTo($('.col-sm-6:eq(0)'))

    $(`[name="data[sueldo]"], [name="data[auxilioExtralegal]"]`).on("input", function () {
        const $this = $(this)
        const show = $this.data("show")
        const $show = $(typeof show === "object" ? show.join(", ") : show)

        if ($show.length) $show.html(new Intl.NumberFormat(GETCONFIG("LOCALE"), {
            style: 'currency', currency: GETCONFIG("CURRENCY")
        }).format($this.val()))
    })

    $(`[name="data[contrato]"]`).on(`change`, function () {
        const $this = $(this)
        const $divMeses = $($(`[name="data[meses]"]`).get(0).parentNode)
        const $meses = $divMeses.find(`[name="data[meses]"]`)

        if ($this.val() === "3") { // fijo
            $meses.attr("required", true)
            $divMeses.show("slow")
        } else {
            $meses.removeAttr("required").val("")
            $divMeses.hide("slow")
        }
    })

    $(`[name="data[motivoRequisicion]"]`).on(`change`, function () {
        const $this = $(this)

        const $divReemplazaA = $($(`[name="data[reemplazaA]"]`).get(0).parentNode.parentNode)
        const $reemplazaA = $divReemplazaA.find(`[name="data[reemplazaA]"]`)

        const $divRecursos = $($(`[name="data[recursos][]"]:eq(0)`).get(0).parentNode.parentNode.parentNode)
        const $recursos = $divRecursos.find(`[name="data[recursos][]"]:eq(0)`)
        console.log($divRecursos)

        const $divOtroMotivoRequisicion = $($(`[name="data[otroMotivoRequisicion]"]`).get(0).parentNode)
        const $otroMotivoRequisicion = $divOtroMotivoRequisicion.find(`[name="data[otroMotivoRequisicion]"]`)

        if ($this.val() === "1" || $this.val() === "2") {
            $divReemplazaA.show("slow")
            $reemplazaA.attr("required", true)
            /*------------------------------------------------*/
            $divRecursos.hide("slow")
            $recursos.each(function () {
                this.checked = false
            })
            /*------------------------------------------------*/
            $divOtroMotivoRequisicion.hide("slow")
            $otroMotivoRequisicion.removeAttr("required").val("")
        } else if ($this.val() === "3" || $this.val() === "4") {
            $divRecursos.show("slow")
            /*------------------------------------------------*/
            $divReemplazaA.hide("slow")
            $reemplazaA.removeAttr("required").val("")
            /*------------------------------------------------*/
            $divOtroMotivoRequisicion.hide("slow")
            $otroMotivoRequisicion.removeAttr("required").val("")
        } else if ($this.val() === "-1") {
            $divOtroMotivoRequisicion.show("slow")
            $otroMotivoRequisicion.attr("required", true)
            /*------------------------------------------------*/
            $divReemplazaA.hide("slow")
            $reemplazaA.removeAttr("required").val("")
            /*------------------------------------------------*/
            $divRecursos.hide("slow")
            $recursos.each(function () {
                this.checked = false
            })
        } else {
            $divReemplazaA.hide("slow")
            $reemplazaA.removeAttr("required").val("")
            /*------------------------------------------------*/
            $divRecursos.hide("slow")
            $recursos.each(function () {
                this.checked = false
            })
            /*------------------------------------------------*/
            $divOtroMotivoRequisicion.hide("slow")
            $otroMotivoRequisicion.removeAttr("required").val("")
        }
    })

})


const aprobar_rechazar = async (i, type) => {
    const comentario = type == "rechazar" ? await swalFire({
        title: "Motivo de rechazo",
        inputAttributes: {
            required: true,
        },
        inputValidator: (val) => {
            if (!val) return "Motivo es obligatorio"
        }
    }) : true
    if (comentario) $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/solicitudPersonal/solicitud/backend.php?action=${type}`, {
        type: "POST",
        dataType: "JSON",
        data: {
            id: i,
            comentario: comentario
        },
        success: (response) => {
            const msg = type == "rechazar" ? "Rechazo confirmado" : "Aprobación confirmada"
            if (response.status === true) {
                alerts({ "title": msg, "icon": "success" })
                updateDatatable()
            } else if (response.error) {
                alerts({ "title": response.error, "icon": "error" })
            }
        }
    })
}