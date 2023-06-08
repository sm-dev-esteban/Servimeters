$(document).ready(async function () {
    config = await loadConfig();

    $(`#listApro`).DataTable($.extend(datatableParams, {
        "processing": true,
        "serverSide": true,
        "order": [[0, `desc`]],
        "ajax": `../controller/ssp.controller.php?ssp=aprobadores`
    })).buttons().container().appendTo($('.col-sm-6:eq(0)'));

    $("#add").on("submit", function (e) {
        e.preventDefault();
        $.ajax(`../controller/submit.controller.php?action=aprobador`, {
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
                    alerts({
                        title: "Se añadio un nuevo aprobador",
                        icon: "success"
                    })
                    updateDatable();
                }
            }
        })
    });

    // pendiente

    $(`.bootstrap-switch span`).on("click", function () {
        $this = $(this.parentNode.parentNode);
        $check = $this.hasClass("bootstrap-switch-on");
    });

    $("#nombre, #correo").on("input", function () {
        $this = $(this);
        $.ajax(`../controller/search.ldap.php`, {
            dataType: "JSON",
            type: "POST",
            data: {
                search: $this.val()
            },
            success: function (response) {
                if (response.count == 1) {
                    $(`#nombre`).val(response[0].name[0]);
                    $(`#correo`).val(response[0].mail[0]);
                }
            }
        });
    });
    // pendiente

});

function ChangeMode(x) {
    $(`[data-show=${x}]`).toggleClass("d-none");
    $(`[data-edit=${x}]`).toggleClass("d-none");
}

function update(x) {
    let $this, v, c, u, t, $check;
    $this = $(x);
    v = $this.val();
    c = $this.data("column");
    u = $this.data("update");
    t = $this.data("table");
    $check = automaticForm("updateValueSql", [v, c, u, t]);
    if ($check.status == true) {
        updateDatable();
    }
}

function updateDatable() {
    $(`#listApro`).DataTable().ajax.reload(null, false);
}