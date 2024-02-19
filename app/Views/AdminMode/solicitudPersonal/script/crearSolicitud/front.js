$(document).ready(async () => {
    const handleSuccess = (response) => {
        if (response.status || false) {
            const status = response.status.toLowerCase()
            const message = response.message

            if (status === "success" || status === "error") alerts.sweetalert2({ title: message, icon: status })
            else console.error(message);

            if (status === "success") updateDatatable()
        }
    }


    const alerts = new Alerts()

    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/app/Views/AdminMode/solicitudPersonal/script/crearSolicitud/back.php`

    const $proceso = $(`[name="data[id_proceso]"]`)
    const $tipo_contrato = $(`[name="data[id_tipo_contrato]"]`)
    const $horario = $(`[name="data[id_horario]"]`)
    const $motivo_requisicion = $(`[name="data[id_motivo_requisicion]"]`)
    const $aprobador = $(`[name="data[id_aprobador]"]`)

    ldapAutoComplete([{ element: `[name="data[solicitado_por]"]`, event: `input`, column: `name` }, { element: `[name="data[email_solicitado_por]"]`, event: `input`, column: `mail` }, { element: `[name="data[reemplazaA]"]`, event: `input`, column: `name` }])
    ldapAutoComplete([{ element: `[name="data[ciudad]"]`, event: `input`, column: `ciudad` }, { element: `[name="data[codigo]"]`, event: `input`, column: `codigo` }], { url: `${URL_BACKEND}?action=autoComplete`, dataForLDAP: false })

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

    form.setAjaxSettings({ success: handleSuccess })

    $form.on("submit", form.submit)
})
