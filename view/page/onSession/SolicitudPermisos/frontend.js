/*-- 2023-11-22 10:29:13 --*/

$(document).ready(async () => {
    $('#reservation').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'MM/DD/YYYY'
        }
    })

    const $form = $(`form[data-action]`)
    if ($form.length) $form.on("submit", function (e) {
        e.preventDefault()
        const $this = $(this)
        $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/SolicitudPermisos/backend.php?action=${$this.data("action")}`, {
            cache: false,
            processData: false,
            contentType: false,
            dataType: "JSON",
            type: "POST",
            data: new FormData(this),
            success: (response) => {
                if (response.status) {
                    $(`.modal`).modal("hide")
                    updateDatatable()

                    alerts({ title: "Registrado con exito", icon: "success" })
                } else alerts({ title: "Ha ocurrido un error al hacer el registro", icon: "error" })
            }
        })
    })

    const $table = $(`table[data-action]`);

    if ($table.length) {
        const defaultParams = GETCONFIG("DATATABLE");
        const newParams = {
            processing: true,
            serverSide: true,
            order: [[0, `desc`]],
            language: $.extend(defaultParams.language, {
                paginate: {
                    previous: "<i class=\"fas fa-chevron-left\"></i>",
                    next: "<i class=\"fas fa-chevron-right\"></i>",
                }
            }),
            ajax: {
                url: `${GETCONFIG("SERVER_SIDE")}/View/page/onSession/SolicitudPermisos/backend.php?action=${$table.data("action")}`,
                type: 'POST',
                data: function (data) {
                    $('tfoot input').each(function () {
                        const $input = $(this)

                        const value = $input.val()
                        const position = $input.parent().index();

                        data.columns[position].search.value = value;
                        data.columns[position].search.position = position;
                    });
                }
            },
            footerCallback: function (tfoot, data, start, end, display) {
                const $tfoot = $(tfoot);

                if (!$tfoot.data("filter")) {
                    $tfoot.data("filter", true);

                    const api = this.api();

                    const handleEventInput = (e) => api.column($(e.target).parent().index()).search(e.target.value).draw();

                    $tfoot.find('input').on("input", handleEventInput);
                }
            }
        };

        const params = $.extend(defaultParams, newParams);

        const dataTable = $table.DataTable(params);

        $('.dataTables_filter').remove();

        $(`.content .card .card-header input`).on('input', function () {
            dataTable.search(this.value).draw();
        });

        $(document).on("click", "[data-edit]", () => alerts({ title: "Edita en HD", icon: "success" }))
        $(document).on("click", "[data-delete]", () => alerts({ title: "Borra en HD", icon: "success" }))

        $(`[data-action="refresh"]`).on("click", function () {
            dataTable.ajax.reload(null, false);
        });

        dataTable.buttons().container().prependTo($('.content .col-sm-12:eq(0)'));
    }

})