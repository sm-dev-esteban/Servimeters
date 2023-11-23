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

    const $formAdjuntos = $(`#formAdjuntos`)
    if ($formAdjuntos.length) $formAdjuntos.createDropzone({
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
            success: (response) => $table.find(`tbody`).append(templateCandidato(response[0], 1))
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
                complete: () => {
                    setTimeout(() => {
                        $loadSpinner.hide(`slow`)
                    }, 1000)
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

    const $formSearchAdd = $(`#card-search form[data-mode="agregarCandidatos"]`)

    if ($formSearchAdd.length) $formSearchAdd.on(`submit`, async function (e) {
        e.preventDefault()
        const formData = new FormData(this)
        await buscarCandidato(formData)
    })
    else {
        const get = gets()
        const report = get.report ?? false
        if (report) {
            const formData = new FormData()
            formData.append("requisicion", window.atob(report))
            await buscarCandidato(formData)
        }
    }

    const $formSearchSelect = $(`#card-search form[data-mode="seleccionarCandidatos"]`)

    if ($formSearchSelect.length) $formSearchSelect.on(`submit`, async function (e) {
        e.preventDefault()
        const formData = new FormData(this)
        await buscarCandidatoCitados(formData)
        console.log(`Selecionar candidatos`)
    })
    else {
        const get = gets()
        const report = get.report ?? false
        if (report) {
            const formData = new FormData()
            formData.append("requisicion", window.atob(report))
            await buscarCandidatoCitados(formData)
            console.log(`Selecionar candidatos`)
        }
    }

    formSearch = $.extend($formSearchAdd, $formSearchSelect)
    SearchValue = formSearch.find(`#requisicion`).val()
    if (SearchValue && SearchValue !== "") formSearch.find(`button[type="submit"]`).click()
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
}, buscarCandidato = async (formData) => {
    if (formData instanceof FormData) {
        const $card_report = $(`#card-report`)
        const $overlay = $(`<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>`)
        const $table = $(`#table-agregar-candidato`)

        const id = formData.get(`requisicion`)

        request = await fetch(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/solicitudPersonal/solicitud/backend.php?action=validarRequisicion&requisicion=${id}`)
        response = await request.json()

        const $tbody = $table.find(`tbody`)
        $tbody.html(``)

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
                success: (response) => response.forEach((data) => $tbody.append(templateCandidato(data, 1)))
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
    }
}, buscarCandidatoCitados = async (formData) => {
    if (formData instanceof FormData) {
        const $card_candidatos = $(`#card-candidates`)
        const $card_body = $card_candidatos.find(`.card-body`).html(``)

        const id = formData.get(`requisicion`)

        request = await fetch(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/solicitudPersonal/solicitud/backend.php?action=validarRequisicion&requisicion=${id}`)
        response = await request.json()

        if (response.status === true) {
            $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/solicitudPersonal/solicitud/backend.php?action=buscarCandidatosCitados`, {
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: (response) => response.forEach((data) => $card_body.append(templateCandidato(data, 2)))
            })
        } else {
            alerts({
                title: "Registro no encontrado",
                icon: "info"
            })
        }
    }
}, gets = () => {
    const url = new URL(location.href)
    const array_gets = {}

    url.searchParams.forEach((v, k) => {
        array_gets[k] = v
    })

    return array_gets
}, templateCandidato = (data, type = 1) => {
    if (typeof data === "object" && typeof type === "number") {
        const manages = localStorage.getItem("manages")
        const COMPANY = GETCONFIG("COMPANY")

        const fechaRegistro = new Intl.DateTimeFormat('es-CO', {
            month: '2-digit',
            day: '2-digit',
            year: 'numeric',
            hour: 'numeric',
            minute: 'numeric'
        }).format(new Date(data.fechaRegistro))

        const fechaCitacion = new Intl.DateTimeFormat('es-CO', {
            month: '2-digit',
            day: '2-digit',
            year: 'numeric',
            hour: 'numeric',
            minute: 'numeric'
        }).format(new Date(data.fechaCitacion))

        const disabled = manages !== "RH" ? "disabled" : ""

        return type === 1 ? `
        <tr data-id=${data.id}>
            <td><input type="text" class="form-control" value="${fechaRegistro}" readonly></td>
            <td><input ${disabled} name="data[nombreCompleto]" data-id=${data.id} type="text" class="form-control" placeholder="Nombre Completo" value="${data.nombreCompleto}"></td>
            <td>
                <!-- <button class="rounded btn-danger m-1" onclick="eliminarCandidato(${data.id})" data-remove=${data.id}><i class="fa fa-trash"></i></button> -->
                <div class="form-group m-1">
                    <div class="custom-control custom-checkbox">
                        <input ${disabled} name="data[candidatoCitado]" data-id=${data.id} class="custom-control-input" type="checkbox" id="customCheckbox${data.id}" ${data.candidatoCitado == "true" ? "checked" : ""}>
                        <label for="customCheckbox${data.id}" class="custom-control-label">¿El candidato fue citado?</label>
                    </div>
                </div>
            </td>
        </tr>
        <tr data-id=${data.id}>
            <td colspan="3">
                <textarea ${disabled} name="data[observacionCandidato]" data-id=${data.id} class="form-control" placeholder="Nota u observaciones sobre el candidato">${data.observacionCandidato ?? ""}</textarea>
            </td>
        </tr>
        ` : `
        <div class="post">
            <div class="user-block">
                ${data.contratado ? `<img class="img-circle img-bordered-sm" src="${COMPANY["LOGO"]}" alt="User Image">` : ""}
                <span style="${!data.contratado ? "margin-left: 0" : ""}" class="username">
                    <a href="#" style="pointer-events: none">${data.nombreCompleto}</a>
                    <!-- <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a> -->
                </span>
                <span style="${!data.contratado ? "margin-left: 0" : ""}" class="description">Registrado El: ${fechaRegistro} - Citado El: ${fechaCitacion}</span>
            </div>
            <p>${data.observacionCandidato}</p>
            <p class="${data.contratado ? "d-none" : ""}"><a onclick="contratarEmpleado(${data.id})" class="link-black text-sm mr-2" style="cursor: pointer"><i class="fas fa-file-contract"></i> Contratar</a></p>
        </div>
        `
    }

}, contratarEmpleado = (i) => {
    if (i) $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/solicitudPersonal/solicitud/backend.php?action=contratarEmpleado`, {
        type: "POST",
        dataType: "JSON",
        data: {
            id: i
        },
        success: (response) => {
            if (response && response.status) alerts({ title: "Candidato aprobado", icon: "success" })
            else alerts({ title: "Ah ocurrido un error inesperado", icon: "info" })
        }
    })
}