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

    ldapAutoComplete([
        {
            element: `[name="data[ciudad]"]`,
            event: `input`,
            search: `ciudad`
        }, {
            element: `[name="data[codigo]"]`,
            event: `input`,
            search: `codigo`
        }
    ], {
        url: `${GETCONFIG("SERVER_SIDE")}/View/page/onSession/solicitudPersonal/solicitud/backend.php?action=autoComplete`,
        dataForLDAP: false
    })

    $(`#formAdjuntos`).createDropzone({
        table: "requisicion_hojas_de_vida"
    })

    const $form = $(`form[data-action]`)
    $form.on("submit", function (e) {
        e.preventDefault()
        const $this = $(this)
        $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/solicitudPersonal/solicitud/backend.php?action=${$this.data("action")}`, {
            type: "POST",
            dataType: "JSON",
            data: new FormData(this),
            cache: false,
            processData: false,
            contentType: false,
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
    })).buttons().container().appendTo($(".col-sm-6:eq(0)"))

    $(`[name="data[sueldo]"], [name="data[auxilioExtralegal]"]`).on("input", function () {
        const $this = $(this)
        const show = $this.data("show")
        const $show = $(typeof show === "object" ? show.join(", ") : show)

        if ($show.length) $show.html(new Intl.NumberFormat(GETCONFIG("LOCALE"), {
            style: "currency", currency: GETCONFIG("CURRENCY")
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

    $(`#btn-agregar-candidato`).on(`click`, function () {
        const $table = $(`#table-agregar-candidato`)
        const $requisicion = $(`#requisicion`)

        $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/solicitudPersonal/solicitud/backend.php?action=agregarCandidato`, {
            type: "POST",
            dataType: "JSON",
            data: {
                requisicion: $requisicion.val()
            },
            success: (response) => {
                if (response[0] ?? false) {
                    data = response[0]
                    const formatDate = new Date(data.fechaRegistro)
                    const date = new Intl.DateTimeFormat('es-CO', {
                        month: '2-digit',
                        day: '2-digit',
                        year: 'numeric',
                        hour: 'numeric',
                        minute: 'numeric'
                    }).format(formatDate)

                    $table.find(`tbody`).append(`
                        <tr data-id="${data.id}">
                            <td><input type="text" class="form-control" value="${date}" readonly></td>
                            <td><input type="text" class="form-control" value="${data.nombreCompleto}"></td>
                            <td>
                                <button class="rounded btn-success m-1" type="button" onclick="aprobarCandidato(${data.id})">
                                    <i class=" fa fa-check"></i>
                                </button>
                                <button class="rounded btn-danger m-1" type="button" onclick="rechazarCandidato(${data.id})">
                                    <i class=" fa fa-times"></i>
                                </button>
                                <div class="form-group m-1">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="option1">
                                        <label for="customCheckbox1" class="custom-control-label">¿El candidato fue citado?</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `)
                }
            }
        })
    })

    $(`tbody`).on(`click`, `[data-remove]`, function () {
        $(this).parent().parent().remove()
    })

    $(`#card-search form`).on(`submit`, async function (e) {
        e.preventDefault()
        const formData = new FormData(this)
        const $card_search = $(`#card-search`)
        const $card_candidates = $(`#card-candidates`)
        const $card_report = $(`#card-report`)
        const $overlay = $(`<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>`)
        const $table = $(`#table-agregar-candidato`)

        const id = formData.get(`requisicion`)

        request = await fetch(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/solicitudPersonal/solicitud/backend.php?action=validarRequisicion&requisicion=${id}`)
        response = await request.json()

        if (response.status === true) {
            $(`.overlay`).remove()
            // candidatos
            $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/solicitudPersonal/solicitud/backend.php?action=buscarCandidatos`, {
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: (response) => {
                    response.forEach((data) => {
                        const formatDate = new Date(data.fechaRegistro)
                        const date = new Intl.DateTimeFormat('es-CO', {
                            month: '2-digit',
                            day: '2-digit',
                            year: 'numeric',
                            hour: 'numeric',
                            minute: 'numeric'
                        }).format(formatDate).toLocaleUpperCase()
                        $table.find(`tbody`).append(`
                            <tr data-id="${data.id}">
                                <td><input type="text" class="form-control" value="${date}" readonly></td>
                                <td><input type="text" class="form-control" value="${data.nombreCompleto}"></td>
                                <td>
                                    <button class="rounded btn-success m-1" type="button" onclick="aprobarCandidato(${data.id})">
                                        <i class=" fa fa-check"></i>
                                    </button>
                                    <button class="rounded btn-danger m-1" type="button" onclick="rechazarCandidato(${data.id})">
                                        <i class=" fa fa-times"></i>
                                    </button>
                                    <div class="form-group m-1">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="customCheckbox1" value="option1">
                                            <label for="customCheckbox1" class="custom-control-label">¿El candidato fue citado?</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `)
                    });
                }
            })
            // reporte
            $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/solicitudPersonal/solicitud/backend.php?action=buscarReporte`, {
                type: "POST",
                dataType: "HTML",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: (response) => {
                    const $report = $(response)
                    $report.attr(`id`, `card-report`)
                    $report.prepend($card_report.find(`.card-header`))
                    $card_report.replaceWith($report)
                }
            })
        } else {
            $(`#card-candidates, #card-report`).append($overlay)
            alerts({
                title: "Registro no encontrado",
                icon: "info"
            })
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
}, aprobarCandidato = (i) => {
    console.log(i)
}
rechazarCandidato = (i) => {
    console.log(i)
}