$(document).ready(async function () {
    config = await loadConfig();

    $(`#listCL`).DataTable($.extend(datatableParams, {
        "processing": true,
        "severSide": true,
        "order": [[0, `desc`]],
        "ajax": `../controller/ssp.controller.php?ssp=clase`
    })).buttons().container().appendTo($('.col-sm-6:eq(0)'));

    $("#add").on("submit", function (e) {
        e.preventDefault();
        $.ajax(`../controller/submit.controller.php?action=clase`, {
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
                        title: "Se añadio una nueva clase",
                        icon: "success"
                    })
                    updateDatable();
                }
            }
        })
    })

});

function ChangeMode(x) {
    $(`[data-show=${x}]`).toggleClass("d-none");
    $(`[data-edit=${x}]`).toggleClass("d-none");
}

function updateClass(x) {
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
    $(`#listCL`).DataTable().ajax.reload(null, false);
}