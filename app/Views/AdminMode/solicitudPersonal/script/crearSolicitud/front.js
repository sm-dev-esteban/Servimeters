$(document).ready(async _ => {
    const handleSuccess = (response) => {
        if (response.status || false) {
            const status = response.status.toLowerCase()
            const message = response.message

            if (status === "success" || status === "error") alerts.sweetalert2({ title: message, icon: status })
            else console.error(message);

            if (status === "success") updateDatatable()
        }
    }, handleContract = _ => {
        const tipo_contrato = $tipo_contrato.find(":selected").text()
        const $meses = $("#meses")

        switch (tipo_contrato) {
            case "FIJO":
                $meses.parent().show("slow", _ =>
                    $meses.val("").attr("required", true))
                break;
            default:
                $meses.parent().hide("slow", _ =>
                    $meses.val("").removeAttr("required"))
                break;
        }
    }, handleRequisition = _ => {
        const motivo_requisicion = $motivo_requisicion.find(":selected").text()
        const $reemplazaA = $("#reemplazaA")
        const $resources = $("#resources")
        const $otroMotivoRequisicion = $("#otroMotivoRequisicion")

        const addRequired = (list = []) => list.forEach(
            ($el) => $el.closest(".col-12").show("slow",
                _ => $el.val("").attr("required", true)
            )
        ), removeRequired = (list = []) => list.forEach(
            ($el) => $el.closest(".col-12").hide("slow",
                _ => $el.val("").removeAttr("required")
            )
        );

        switch (motivo_requisicion) {
            case "RETIRO / RENUNCIA EMPLEADO":
            case "REEMPLAZO POR MATERNIDAD / INCAPACIDAD":
                addRequired([$reemplazaA])
                removeRequired([$resources, $otroMotivoRequisicion])
                break;
            case "NUEVO CARGO":
            case "NUEVO CUPO NÓMINA":
                addRequired([$resources])
                removeRequired([$reemplazaA, $otroMotivoRequisicion])
                break;
            case "OTRO":
                addRequired([$otroMotivoRequisicion])
                removeRequired([$reemplazaA, $resources])
                break;
            default:
                removeRequired([$resources, $reemplazaA, $otroMotivoRequisicion])
                $listResources.html("")
                break;
        }
    }, handleAddResources = () => {
        const $resources = $("#resources")
        const value = $resources.val()

        if (value) value.split(";").forEach(
            val => $listResources.append(
                $(`<li contenteditable="false" name="data[recursos][]" style="display: none">${val}</li>`)
            ).find("li").show("slow")
        )
    }, handleRemoveResources = (e) => $(e.target).hide("slow", function () {
        $(this).remove()
    })

    const alerts = new Alerts()

    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/app/Views/AdminMode/solicitudPersonal/script/crearSolicitud/back.php`

    const $proceso = $(`[name="data[id_proceso]"]`)
    const $tipo_contrato = $(`[name="data[id_tipo_contrato]"]`)
    const $horario = $(`[name="data[id_horario]"]`)
    const $motivo_requisicion = $(`[name="data[id_motivo_requisicion]"]`)
    const $aprobador = $(`[name="data[id_aprobador]"]`)

    const $btnAddResources = $(`#btn-add-resources`)
    const $listResources = $("#list-resources")

    const autoCompleteLDAP = ["solicitado_por", "email_solicitado_por", "reemplazaA"];

    SELECT2_UTILS.ajax.url = `${URL_BACKEND}?action=sspProceso`
    $proceso.select2(SELECT2_UTILS)

    SELECT2_UTILS.ajax.url = `${URL_BACKEND}?action=sspContrato`
    $tipo_contrato.select2(SELECT2_UTILS)

    SELECT2_UTILS.ajax.url = `${URL_BACKEND}?action=sspHorario`
    $horario.select2(SELECT2_UTILS)

    SELECT2_UTILS.ajax.url = `${URL_BACKEND}?action=sspMotivoRequisicion`
    $motivo_requisicion.select2(SELECT2_UTILS)

    SELECT2_UTILS.ajax.url = `${URL_BACKEND}?action=sspAprobador`
    $aprobador.select2(SELECT2_UTILS)

    const $form = $(`form`)
    const formAction = $form.data("action")

    const form = new Form(`${URL_BACKEND}?action=${formAction}`, $form)

    form.sendContentEditable = true
    form.setAjaxSettings({ success: handleSuccess })

    $form.on("submit", form.submit)
    $tipo_contrato.on("change", handleContract)
    $motivo_requisicion.on("change", handleRequisition)
    $btnAddResources.on("click", handleAddResources)
    $listResources.on("click", "li", handleRemoveResources)

    $(`form[data-action="agregarSolicitud"] input:text[name]`).each((_, input) => {
        const name = $(input).attr("name").replace(/data\[(.*?)\]/, "$1")
        if (!autoCompleteLDAP.includes(name)) $(input).autoComplete({
            column: name,
            mode: "SQL",
            url: `${URL_BACKEND}?action=autoComplete`
        })
    })

    $(`#sueldo, #auxilio_extralegal`).on("input", (e) => {
        const $input = $(e.target)
        const value = $input.val()
        const describedby = $input.attr("aria-describedby")
        const $help = $(`#${describedby}`)

        if ($help.length) {
            const amount = new Intl.NumberFormat(Config.LOCALE, {
                style: "currency", currency: Config.CURRENCY
            })

            $help.html(amount.format(value))
        }
    })

    ldapAutoComplete(
        [
            {
                element: `[name="data[solicitado_por]"]`, event: `input`, column: `name`
            }, {
                element: `[name="data[email_solicitado_por]"]`, event: `input`, column: `mail`
            }, {
                element: `[name="data[reemplazaA]"]`, event: `input`, column: `name`
            }
        ]
    )
})
