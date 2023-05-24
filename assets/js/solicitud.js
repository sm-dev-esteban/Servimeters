$(document).ready(async function () {
    config = await loadConfig();


    $("#solicitud").on("submit", function (e) {
        e.preventDefault();

        let $date = new Date().toLocaleString(locale, { timeZone: timezone, year: 'numeric', month: '2-digit', day: '2-digit', weekday: "long", hour: '2-digit', hour12: false, minute: '2-digit', second: '2-digit' });

        $(`[name="data[fechaRegistro]"]`).val($date);
        $(`[name="data[timezone]"]`).val(timezone);

        $.ajax("../controller/submit.controller.php?action=permiso", {
            type: "POST",
            dataType: "JSON",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $(`#solicitud button:submit`).html(`
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                `).attr("disabled", true);
            },
            success: function (response) {
                if (response.error !== undefined) {
                    alerts({ title: `Error SQL: ${response.error}`, icon: "error", duration: 10000 });
                } else if (response.status == true) {
                    $(`[href*="permisos/listSolicitud.view"]`).click();
                    alerts({ title: "Solicitud realizada", icon: "success", duration: 5000 });
                } else {
                    alerts({ title: "Error al solitar el permiso, inténtalo más tarde", icon: "error" });
                }
            }
        })

    });

    $("#tipo_permiso").selectMaster({
        table: "tipo_permiso",
        option_value: "nombre",
        select2: true
    });
    
});