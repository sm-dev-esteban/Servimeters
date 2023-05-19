$(document).ready(async function () {
    config = await loadConfig();

    $datatable = $("#listAprov").DataTable($.extend(datatableParams,
        {
            "processing": true,
            "severSide": true,
            "order": [[0, "desc"
            ]],
            "ajax": "../controller/ssp.controller.php?ssp=listAprobar",
            "deferRender": true
        })).on("draw", function () {
            $("tr").each(function () {
                let status = $(this).find("span").data("status");
                let tr = $(this);
                if (status == 2) {
                    tr.addClass("bg-info");
                }
            });
        }).buttons().container().appendTo($('.col-sm-6:eq(0)'));


    $("#listAprov tbody").on("mousemove", "tr", function () {
        $("#listAprov tbody tr").removeClass("bg-primary");
        $(this).addClass("bg-primary");
    }).on("click", "tr", function () {
        let ident = $(this).find("span").data("ident");
        $(this).toggleClass("bg-info");
        if ($(this).hasClass("bg-info")) {
            automaticForm("updateValueSql", [
                2,
                "checkStatus",
                ident,
                "ReportesHE"
            ]);
        } else {
            automaticForm("updateValueSql", [
                1,
                "checkStatus",
                ident,
                "ReportesHE"
            ]);
        }
    });

    $("#rechazar, #aprobar").on("click", function () {
        let change = $(this).attr("id") == "rechazar" ? 2 : 1;
        let email = localStorage.getItem("email");
        let rol = localStorage.getItem("rol");

        if (rol.toLocaleLowerCase() == "gerente" || rol.toLocaleLowerCase() == "jefe") {
            let id_aprobador = automaticForm("getValueSql", [
                email,
                "correo",
                "id",
                "Aprobadores"
            ]);
            let check = automaticForm("updateValueSql", [
                change,
                "id_estado",
                {
                    "checkStatus": "2",
                    "id_aprobador": id_aprobador,
                    "id_estado": rol.toLocaleLowerCase() == "jefe"
                        ? config.APROBACION_JEFE
                        : (
                            rol.toLocaleLowerCase() == "gerente"
                                ? config.APROBACION_GERENTE
                                : false
                        )
                },
                "ReportesHE"
            ]);
            alerts({
                "title": check.status == true ? `${change == 1 ? "Aprobación" : "Rechazo"} confirmado` : check.error,
                "icon": check.status == true ? "Success" : "Error"
            });
            if (check.status == false) {
                alerts({ "title": check.error, "icon": "Error" });
                console.log(`query: ${check.query}`, `rol: ${rol}`, `config.jefe: ${config.APROBACION_JEFE}`, `config.gerente: ${config.APROBACION_GERENTE}`);
            }
            console.log(`query: ${check.query}`);
        } else {
            alerts({ "title": `Tu rol no permite realizar cambios: ${rol}`, "icon": "info" });
        }

        updateDatable();
    });

    $("#STodo, #DTodo").on("click", function () {
        let change = $(this).attr("id") == "STodo" ? 2 : 1;
        let email = localStorage.getItem("email");
        let rol = localStorage.getItem("rol");
        let id_aprobador = automaticForm("getValueSql", [
            email,
            "correo",
            "id",
            "Aprobadores"
        ]);

        let check = automaticForm("updateValueSql", [
            change,
            "checkStatus",
            {
                "id_aprobador": id_aprobador,
                "id_estado": rol.toLocaleLowerCase() == "jefe"
                    ? config.APROBACION_JEFE
                    : (
                        rol.toLocaleLowerCase() == "gerente"
                            ? config.APROBACION_GERENTE
                            : false
                    )
            },
            "ReportesHE"
        ]);

        if (check.status == false) {
            alerts({ "title": check.error, "icon": "Error" });
            console.log(`query: ${check.query}`, `rol: ${rol}`, `config.jefe: ${config.APROBACION_JEFE}`, `config.gerente: ${config.APROBACION_GERENTE}`);
        }
        console.log(`query: ${check.query}`);

        updateDatable();

    });

});

function updateDatable() {
    $("#listAprov").DataTable().ajax.reload(null, false);
}