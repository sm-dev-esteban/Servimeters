$(document).ready(async function () {
    config = await loadConfig();

    $(`#listCC`).DataTable($.extend(datatableParams, {
        "processing": true,
        "serverSide": true,
        "order": [[0, `desc`]],
        "ajax": `../controller/ssp.controller.php?ssp=ceco`
    })).buttons().container().appendTo($('.col-sm-6:eq(0)'));

    $("#add").on("submit", function (e) {
        e.preventDefault();
        $.ajax(`../controller/submit.controller.php?action=ceco`, {
            dataType: "JSON",
            type: "POST",
            data: new FormData(this),
            processData: false,
            cache: false,
            contentType: false,
            success: function (response) {
                if (response.error) {
                    alerts({
                        title: response.error,
                        icon: "error",
                        duration: 10000
                    })
                } else {
                    let usuario = localStorage.getItem(`usuario`);
                    let $valor = $("#titulo").val();
                    updateDatable();
                    server.send(
                        JSON.stringify({
                            type: "alerts",
                            data: {
                                arrayAlert: {
                                    title: `Se añadio un nuevo centro de costo`,
                                    html: `
                                    <p>
                                        <b>${usuario}:</b>
                                        <span class="text-success">
                                            ${$valor}
                                        </span>
                                    </p>`,
                                    icon: `info`,
                                    duration: 10000
                                }
                            }
                        })
                    )
                }
            }
        })
    })

    $("#id_clase").selectMaster({
        table: "Clase",
        option_value: "titulo",
        select2: true
    });

});

function ChangeMode(x) {
    $(`[data-show=${x}]`).toggleClass(`d-none`);
    $(`[data-edit=${x}]`).toggleClass(`d-none`);
}

function updateClass(x) {
    let $this, v, c, u, t, $check, $valor, usuario;
    $this = $(x);
    v = $this.val();
    c = $this.data(`column`);
    u = $this.data(`update`);
    t = $this.data(`table`);
    $valor = automaticForm(`getValueSql`, [u, `@primary`, c, t]);
    usuario = localStorage.getItem(`usuario`);
    $check = automaticForm(`updateValueSql`, [v, c, u, t]);
    if ($check.status == true) {
        updateDatable();
        server.send(
            JSON.stringify({
                type: "alerts",
                data: {
                    arrayAlert: {
                        title: `Modificación centro de costo`,
                        html: `
                        <p>
                            <b>${usuario}:</b>
                            <span class="text-warning">
                                ${$valor}
                            </span>
                            <span class="text-success">
                                <i class="fas fa-arrow-right text-sm"></i> ${v}
                            </span>
                        </p>`,
                        icon: `info`,
                        duration: 10000
                    }
                }
            })
        )
    } else {
        alerts({
            title: $check.error,
            icon: "error",
            duration: 10000
        });
    }
}

function updateDatable() {
    $(`#listCC`).DataTable().ajax.reload(null, false);
}