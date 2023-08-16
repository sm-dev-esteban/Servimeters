$(document).ready(async function () {
    /*-- select2 --*/
    $(`select`).css({
        width: "100%"
    }).select2();

    /*-- parse number --*/
    $(`[name="data[sueldo]"], [name="data[auxilioExtralegal]"]`).on("input", function () {
        const $this = $(this)
        const show = $this.data("show")
        const $show = $(typeof show === "object" ? show.join(", ") : show)

        $show.html(new Intl.NumberFormat(locale, { style: 'currency', currency: 'COP' }).format($this.val()))
    })

    /*-- submit --*/
    $(`#envioSolicitud`).on(`submit`, function (e) {
        e.preventDefault();
        $.ajax(`../controller/submit.controller.php?t=${timezone}&action=solicitudPersonal`, {
            type: "POST",
            dataType: "JSON",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                console.log(response)
            }
        })
    })

    /*-- datatable serverSide --*/
    $("table[data-ssp]").DataTable($.extend(datatableParams, {
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        "ajax": `../controller/Datatable.controller.php?ssp=${$("table[data-ssp]").data("ssp")}`
    })).buttons().container().appendTo($('.col-sm-6:eq(0)'));

    /*-- Modal --*/
    $(`#modalMain${new Date().getFullYear()}`).on(`show.bs.modal`, function (e) {
        const $this = $(this)
        const $btn = $(e.relatedTarget)

        const $form = $this.find(`form`)

        const contentModal = {
            ".modal-header": $btn.data(`header`),
            ".modal-body": $btn.data(`body`),
            ".modal-footer": $btn.data(`footer`)
        };

        const defaultModal = {
            ".modal-header": [{ "h5": { "class": "modal-title", "text": contentModal[".modal-header"] ?? "¯\\_(ツ)_/\¯" } }, { "button": { "type": "button", "class": "close", "data-dismiss": "modal", "aria-label": "Close", "html": [{ "span": { "aria-hidden": true, "text": "❌" } }] } }],
            ".modal-body": [{ "p": { "text": "¯\\_(ツ)_/\¯" } }],
            ".modal-footer": [{ "button": { "type": "button", "class": "btn btn-secondary", "data-dismiss": "modal", "text": "Close" } }]
        };

        for (x in contentModal) {
            array = ((typeof contentModal[x]) === "object") ? contentModal[x] ?? defaultModal[x] : defaultModal[x]
            $find = $this.find(`${x}`).html(``);
            if ((typeof array) === "object") for (data in array) $find.append(createElem(Object.keys(array[data])[0], Object.values(array[data])[0]))
        }


        const formAttr = {
            "data-action": $btn.data(`action`),
            "data-id": $btn.data(`id`)
        }

        for (data in formAttr) if (formAttr[data]) $form.attr(`${data}`, formAttr[data]); else $form.removeAttr(`${data}`);
    })

    /*-- form --*/
    $(`#formMain${new Date().getFullYear()}`).on(`submit`, function (e) {
        e.preventDefault()

        const $this = $(this)
        const action = $this.data(`action`)
        const id = $this.data(`id`)

        if (action) $.ajax(`?action=${action}&mode=${id ? 1 : 2}&id=${id ?? 0}`, {
            cache: false,
            type: "POST",
            dataType: "JSON",
            contentType: false,
            processData: false,
            data: new FormData(this),
            success: function (response) {
                console.log(response)
            }
        })
    })

    $(`input:radio[name="typeC"]`).on(`change`, function () {
        $this = $(this);
        $(`[data-show][data-show="${$this.val()}"]`).removeClass("d-none");
        $(`[data-show][data-show!="${$this.val()}"]`).addClass("d-none");
    })
})
