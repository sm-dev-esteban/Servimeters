$(document).ready(async () => {
    const inArray = (needle, haystack) => haystack.includes(needle), showTimeline = (e) => {
        const $modal = $(e.target)
        const $btn = $(e.relatedTarget)
        const id = $btn.data("id")
        $.ajax(`${URL_BACKEND}?action=showTimeline`, {
            type: "POST",
            dataType: "HTML",
            data: { id: id },
            success: (response) => $modal.find(".modal-body").html(response)
        })
    }, showReport = (e) => {
        const $modal = $(e.target)
        const $btn = $(e.relatedTarget)
        const id = $btn.data("id")
        $.ajax(`${URL_BACKEND}?action=showReport`, {
            type: "POST",
            dataType: "HTML",
            data: { id: id },
            success: (response) => $modal.find(".modal-body").html(response)
        })
    }, printModal = (e) => {
        const content = $(e.target).closest(".modal-content").find(".modal-body").html();
        const windowPrint = window.open('', '_blank');
        windowPrint.document.write(`
        <html>
            <head>
                <link rel="stylesheet" href="http://localhost/Servimeters/vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css">
                <link rel="stylesheet" href="http://localhost/Servimeters/vendor/almasaeed2010/adminlte/plugins/fontawesome-free/css/all.min.css">
                <style>
                    body {
                        position: relative;
                    }
                    .watermark {
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        opacity: 0.1; /* Ajusta la opacidad según sea necesario */
                        z-index: -1;
                    }
                </style>
            </head>
            <body>
                <div class="watermark">
                    <img src="http://localhost/Servimeters/img/SM CIRCULAR.png" alt="Logo" style="width: 100%; max-width: 200px; height: auto;">
                </div>
                ${content}
                <script>
                document.addEventListener("DOMContentLoaded", event => window.print());
                document.addEventListener("click", event => window.print());
                </script>
            </body>
        </html>
        `);
        windowPrint.document.close();
    }


    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/app/Views/AdminMode/horasExtras/backend.php`

    const srcSplit = location.href.split("?")[0].split("/")

    if (inArray("reportarHoras", srcSplit) || inArray("editarReporte", srcSplit)) {

        const sumarColumnas = (i) => $(`tbody tr`).toArray().reduce((sum, $tr) => sum + Number($($tr).find(`td:nth-child(${i})`).text()), 0)
        const sumarValores = (array) => {
            let suma = 0
            array.forEach((data) => {
                const index = data.index
                const selector = data.selector
                suma += sumarColumnas(index) // Utiliza la función sumarColumnas definida previamente
            })
            return suma
        }, actualizarSumas = () => {
            array.forEach(data => {
                const index = data.index
                const selector = data.selector
                $(selector).text(sumarColumnas(index))
            })

            const sumaTotalDescuentos = sumarValores(arrayD)
            const sumaTotalExtras = sumarValores(arrayE)
            const sumaTotalRecargos = sumarValores(arrayR)

            $(`[name="data[Suma_Total_Descuentos]"]`).text(sumaTotalDescuentos)
            $(`[name="data[Suma_Total_Extras]"]`).text(sumaTotalExtras)
            $(`[name="data[Suma_Total_Recargos]"]`).text(sumaTotalRecargos)

            $(`[name="data[Suma_Total_Horas]"]`).text(sumaTotalDescuentos + sumaTotalExtras + sumaTotalRecargos)
        }, agregarColumna = (e) => {
            const $trClone = $(`#tableDatail tbody tr:eq(0)`).clone().appendTo("#tableDatail tbody")

            $trClone
                .find("[disabled]")
                .removeAttr("disabled")
            $trClone
                .find(`[contenteditable][type="number"]`)
                .text(0)

            actualizarSumas()
        }, borrarColumna = (e) => {
            $(e.target).closest("tr").hide("slow", function () {
                $(this).remove()
                actualizarSumas()
            })

        }, habilitarAdjuntos = (e) => {
            const $adjuntos = $(`#adjuntos`)
            const $labelAdjuntos = $(`[for="adjuntos"]`)

            if (e.target.checked) {
                $labelAdjuntos.text(`Choose file`)
                $adjuntos.attr(`required`, true).removeAttr(`disabled`)
            } else {
                $labelAdjuntos.text(`Disabled`)
                $adjuntos.attr(`disabled`, true).removeAttr(`required`).val(``)
            }
        }, establecerFechaLimite = (e) => {
            const mesReportado = e.target.value
            const $fecha = $(`[name="HorasExtra[fecha][]"]`)
            const $fecha_inicio = $(`[name="data[fecha_inicio]"]`)
            const $fecha_fin = $(`[name="data[fecha_fin]"]`)

            if (mesReportado) {
                const min = moment(`${mesReportado}-01`, "YYYY-MM-DD").subtract(1, "M").format().slice(0, 10)
                const max = new Date(...mesReportado.split("-"), 0).toISOString().slice(0, 10)

                $fecha.attr("min", min).attr("max", max)
                $fecha_inicio.val(min)
                $fecha_fin.val(max)
            } else {
                $fecha.removeAttr("min").removeAttr("max")
                $fecha_inicio.val(null)
                $fecha_fin.val(null)
            }
        }, habilitarAprobador = (e) => {
            const estado = e?.target.value || 1

            const $id_aprobador = $(`[name="data[id_aprobador]"]`)
            const $id_estado = $(`[name="data[id_estado]"]`)

            $id_estado.val(estado)

            if (estado == 4) {
                $jefes
                    .removeAttr("disabled")
                    .attr("required", true)
                $gerentes
                    .attr("disabled", true)
                    .removeAttr("required")
                    .val(null)
                    .trigger("change")
                $id_aprobador.val($jefes.val())
            } else if (estado == 5) {
                $gerentes
                    .removeAttr("disabled")
                    .attr("required", true)
                $jefes
                    .attr("disabled", true)
                    .removeAttr("required")
                    .val(null)
                    .trigger("change")
                $id_aprobador.val($gerentes.val())
            } else {
                $gerentes.attr("disabled", true).val(null).trigger("change")
                $jefes.attr("disabled", true).val(null).trigger("change")
                $id_aprobador.val(1)
            }
        }, seleccionarAprobador = (e) => {
            const id_aprobador = e.target.value
            const $id_aprobador = $(`[name="data[id_aprobador]"]`)

            $id_aprobador.val(id_aprobador)
        }

        ContentEditableInput.selectorAll("[contenteditable][type]")

        const $table = $(`#tableDatail`)
        const $btnAdd = $(`button[data-action="agregar"]`)
        const btnDelete = `button[data-action="borrar"]`
        const $cargo = $(`[name="data[cargo]"]`)
        const $id_ceco = $(`[name="data[id_ceco]"]`)
        const $mesReportado = $(`[name="data[mesReportado]"]`)
        const $checkAprobador = $(`[name="data[checkAprobador]"]`)
        const $jefes = $(`#Jefes`)
        const $gerentes = $(`#Gerentes`)
        const $aprobadores = $(`#Jefes, #Gerentes`)

        let n = 2;

        const array = [
            { index: ++n, selector: `[name="data[Total_Descuento]"]` },
            { index: ++n, selector: `[name="data[Total_Ext_Diu_Ord]"]` },
            { index: ++n, selector: `[name="data[Total_Ext_Noc_Ord]"]` },
            { index: ++n, selector: `[name="data[Total_Ext_Diu_Fes]"]` },
            { index: ++n, selector: `[name="data[Total_Ext_Noc_Fes]"]` },
            { index: ++n, selector: `[name="data[Total_Rec_Noc]"]` },
            { index: ++n, selector: `[name="data[Total_Rec_Fes_Diu]"]` },
            { index: ++n, selector: `[name="data[Total_Rec_Fes_Noc]"]` },
            { index: ++n, selector: `[name="data[Total_Rec_Ord_Fes_Noc]"]` }
        ]

        array.forEach(data => $table.on("input", `tbody tr td:nth-child(${data.index})`, () => {
            $(data.selector).text(sumarColumnas(data.index))
            actualizarSumas()
        }))

        const arrayD = array.filter((obj) => obj.selector.includes("_Descuento"))
        const arrayE = array.filter((obj) => obj.selector.includes("_Ext"))
        const arrayR = array.filter((obj) => obj.selector.includes("_Rec"))

        actualizarSumas()
        habilitarAprobador()

        $btnAdd.on("click", agregarColumna)
        $table.on("click", btnDelete, borrarColumna)
        $mesReportado.on("change", establecerFechaLimite)
        $checkAprobador.on("change", habilitarAprobador)
        $aprobadores.on("change", seleccionarAprobador);

        SELECT2_UTILS.ajax.url = `${URL_BACKEND}?action=sspCargo`
        $cargo.select2(SELECT2_UTILS)

        SELECT2_UTILS.ajax.url = `${URL_BACKEND}?action=sspCeco`
        $id_ceco.select2(SELECT2_UTILS)

        SELECT2_UTILS.ajax.url = `${URL_BACKEND}?action=sspJefes`
        SELECT2_UTILS.templateSelection = (data) => data.text
        $jefes.select2(SELECT2_UTILS)

        SELECT2_UTILS.ajax.url = `${URL_BACKEND}?action=sspGerentes`
        SELECT2_UTILS.templateSelection = (data) => data.text
        $gerentes.select2(SELECT2_UTILS)

        const $checkAdjuntos = $(`#checkAdjuntos`)
        $checkAdjuntos.on(`change`, habilitarAdjuntos)

    }

    // modal
    const $modalTimeline = $(`#modal-timeline`)
    const $modalReport = $(`#modal-report`)
    const $btnPrintModal = $(`[data-action="print-modal"]`)

    $modalTimeline.on("show.bs.modal", showTimeline)
    $modalReport.on("show.bs.modal", showReport)
    $btnPrintModal.on("click", printModal)

    $(`[data-toggle="popover"]`).popover({
        container: "body",
        html: true
    })
})