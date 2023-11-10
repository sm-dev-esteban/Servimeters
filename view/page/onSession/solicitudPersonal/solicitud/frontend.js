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
                        <tr data-id=${data.id}>
                            <td><input type="text" class="form-control" value="${date}" readonly></td>
                            <td><input name="data[nombreCompleto]" data-id=${data.id} type="text" class="form-control" placeholder="Nombre Completo" value="${data.nombreCompleto}"></td>
                            <td>
                                <!-- <button class="rounded btn-danger m-1" onclick="eliminarCandidato(${data.id})" data-remove=${data.id}><i class="fa fa-trash"></i></button> -->
                                <div class="form-group m-1">
                                    <div class="custom-control custom-checkbox">
                                        <input name="data[candidatoCitado]" data-id=${data.id} class="custom-control-input" type="checkbox" id="customCheckbox${data.id}" ${data.candidatoCitado == "true" ? "checked" : ""}>
                                        <label for="customCheckbox${data.id}" class="custom-control-label">¿El candidato fue citado?</label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr data-id=${data.id}>
                            <td colspan="3">
                                <textarea name="data[observacionCandidato]" data-id=${data.id} class="form-control" placeholder="Nota u observaciones sobre el candidato">${data.observacionCandidato ?? ""}</textarea>
                            </td>
                        </tr>
                    `)
                }
            }
        })
    })

    $(`#table-agregar-candidato tbody`).on(`change`, `[name]`, function () {
        const manages = localStorage.getItem("manages")

        if (manages === "RH") {
            const $this = $(this)
            const data = {}

            const $loadSpinner = $(`#load-spinner`)

            data["id"] = $this.data(`id`)
            data[$this.attr(`name`)] = () => {
                const type = $this.attr(`type`)

                if (type === "checkbox") return $this.get(0).checked
                else if (type === "textarea") return ""
                else return $this.val()
            }

            $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/solicitudPersonal/solicitud/backend.php?action=actualizarCandidatos`, {
                type: "POST",
                dataType: "JSON",
                data: data,
                beforeSend: () => {
                    $loadSpinner.show(`slow`)
                },
                success: (response) => {
                    console.log(response);
                },
                complete: () => {
                    setTimeout(() => {
                        $loadSpinner.hide(`slow`)
                    }, 1000);
                }
            })

        }
    })

    $(`tbody`).on(`click`, `td [data-remove]`, function () {
        // $(this).closest(`tr`).remove()

        const $this = $(this)
        const ident = $this.data("remove")
        const $find = $(`tr[data-id=${ident}]`)

        if ($find.length) $find.hide("slow", () => {
            $find.remove()
        })
    })

    const $formSearch = $(`#card-search form`);

    $formSearch.on(`submit`, async function (e) {
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
                    const manages = localStorage.getItem("manages")

                    const $tbody = $table.find(`tbody`)
                    $tbody.html(``)
                    response.forEach((data) => {
                        const formatDate = new Date(data.fechaRegistro)
                        const date = new Intl.DateTimeFormat('es-CO', {
                            month: '2-digit',
                            day: '2-digit',
                            year: 'numeric',
                            hour: 'numeric',
                            minute: 'numeric'
                        }).format(formatDate).toLocaleUpperCase()
                        $tbody.append(`
                            <tr data-id=${data.id}>
                                <td><input type="text" class="form-control" value="${date}" readonly></td>
                                <td><input ${manages !== "RH" ? "disabled" : ""} name="data[nombreCompleto]" data-id=${data.id} type="text" class="form-control" placeholder="Nombre Completo" value="${data.nombreCompleto}"></td>
                                <td>
                                    <!-- <button class="rounded btn-danger m-1" onclick="eliminarCandidato(${data.id})" data-remove=${data.id}><i class="fa fa-trash"></i></button> -->
                                    <div class="form-group m-1">
                                        <div class="custom-control custom-checkbox">
                                            <input ${manages !== "RH" ? "disabled" : ""} name="data[candidatoCitado]" data-id=${data.id} class="custom-control-input" type="checkbox" id="customCheckbox${data.id}" ${data.candidatoCitado == "true" ? "checked" : ""}>
                                            <label for="customCheckbox${data.id}" class="custom-control-label">¿El candidato fue citado?</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr data-id=${data.id}>
                                <td colspan="3">
                                    <textarea ${manages !== "RH" ? "disabled" : ""} name="data[observacionCandidato]" data-id=${data.id} class="form-control" placeholder="Nota u observaciones sobre el candidato">${data.observacionCandidato ?? ""}</textarea>
                                </td>
                            </tr>
                        `)
                    })
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

    SearchValue = $formSearch.find(`#requisicion`).val()

    if (SearchValue && SearchValue !== "")
        $formSearch.find(`button[type="submit"]`).click()
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
}, eliminarCandidato = (i) => {
    if (i) $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/solicitudPersonal/solicitud/backend.php?action=eliminarCandidato&id=${i}`)
}