$(window).on("focus", function () {
    sessionStorage.setItem("windowIsFocus", true)
}).on("blur", function () {
    sessionStorage.setItem("windowIsFocus", false)
})

const lastDay = (
    year = new Date().toLocaleString(GETCONFIG(`LOCALE`), $.extend(GETCONFIG(`TIMEZONE`), { year: `numeric` })),
    month = new Date().toLocaleString(GETCONFIG(`LOCALE`), $.extend(GETCONFIG(`TIMEZONE`), { month: `2-digit` }))
) => {  /*---- obtiene el ultimo dia del mes ----*/
    return new Date(year, month, 0).getDate()
}, isHTML = (str) => { /*---- valida si un elemento es html ----*/
    return str instanceof Element || str instanceof Document
}, elementCreator = (tag, attrs = null) => { /*---- Crea elementos de html es algo complicado de usar asi que esta funcion no existe ----*/
    const newElement = (!isHTML(tag) ? document.createElement(tag) : tag)
    if (null !== attrs) for (data in attrs)
        if ([`text`].includes(data)) newElement.innerHTML = newElement.innerHTML + attrs[data]
        else if ([`html`].includes(data)) {
            if (typeof attrs.html == `object`) for (newCE in attrs.html)
                newElement.append(elementCreator(Object.keys(attrs.html[newCE])[0], Object.values(attrs.html[newCE])[0]))
        } else newElement.setAttribute(data, attrs[data])
    return newElement
}, updateDatatable = (el = "table.dataTable") => { /*---- Actualiza DataTable ----*/
    $(el).DataTable().ajax.reload(null, false)
}, alerts = (arrayAlert, typeAlert = "Sweetalert2") => {
    let windowIsFocus = sessionStorage.getItem("windowIsFocus")
    /**
     * La configuración la hice basándome en los valores de Sweetalert2 para nuevas alertas, sería adecuarlas para que funcione con estos parámetros
     */
    let config = $.extend({
        title: ``, // titulo
        text: ``, // texto
        html: ``, // html
        icon: ``, // icon
        duration: 3000,
        position: "top-end" // por si depronto quieren configurar una posicion diferente
    }, arrayAlert)

    const type = typeAlert.toLocaleLowerCase()

    if (!windowIsFocus && type !== "sweetalert2") alerts(config, "window")

    switch (type) { // por si quieren hacer configuracion diferentes de alertas yo de momento voy a utilizar esta con sweetalert
        case "sweetalert2":
            let Sweetalert2 = Swal.mixin({
                toast: true,
                position: config.position,
                showConfirmButton: false,
                timer: config.duration,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer)
                    toast.addEventListener("mouseleave", Swal.resumeTimer)
                }
            })

            Sweetalert2.fire({
                title: config.title,
                text: config.text,
                icon: config.icon.toLocaleLowerCase(),
                html: config.html
            })
            break
        case "window":
            if (!windowIsFocus && "Notification" in window) {
                notification = false
                if (Notification.permission === "granted") {
                    notification = new Notification(config.title, {
                        body: config.text,
                        icon: $(`link[rel="icon"]`).attr("href"),
                        dir: "ltr"
                    })
                } else if (Notification.permission !== "denied") {
                    Notification.requestPermission().then((permission) => {
                        if (permission === "granted") {
                            notification = new Notification(config.title, {
                                body: config.text,
                                icon: $(`link[rel="icon"]`).attr("href"),
                                dir: "ltr"
                            })
                        }
                    })
                }
                if (false !== notification) $(notification).on("click", function (e) {
                    e.preventDefault()
                    window.open(location.href, "_blank")
                })
            }
            break
        default:
            window.alert(`
                ${config.status}
                ${config.title}
                ${config.text}
                ${config.html}
            `)
            break
    }
}, swalFire = async (main = {}, remove = []) => {

    main = $.extend({
        title: `Cuerpo`,
        input: `textarea`,
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        focusConfirm: false
    }, main)

    if (remove.length) remove.forEach(r => {
        if (main[r] ?? false) delete main[r]
    })

    const { value: response } = await Swal.fire(main)

    return response
}, sendForm = async (url, el_form, params = {}) => {
    let response
    const error_msg = "Valor no valido";

    if (typeof url !== "string") throw Error(error_msg);
    if (typeof params !== "object") throw Error(error_msg);

    const $form = $(el_form)

    if ($form.length) $form.on("submit", (e) => {
        e.preventDefault()

        const form = e.target
        const request = $.ajax(url, $.extend({
            type: "POST",
            // dataType: "JSON",
            data: new FormData(form),
            async: false,
            cache: false,
            contentType: false,
            processData: false
        }, params));

        console.log(request);
    })


    return response;
}, loadData = async (url, id, el_form) => {
    const $form = $(el_form).eq(0)

    let request;

    console.log($form);

    if ($form.length) {
        request = $.ajax(url, {
            type: "POST",
            dataType: "JSON",
            data: { id: id },
            async: false
        });

        const response = request.responseJSON

        if (typeof response === "object") response.forEach((x) => {
            for (data in x) {
                $find = $(`[name="data[${data}]"]`)
                $find2 = $(`[name="data[${data}]"][value="${x[data]}"]`)

                const tagName = $find.prop(`tagName`)
                const type = $find.attr("type")

                if ($find.length) switch (tagName) {
                    case `INPUT`:
                    case `SELECT`:
                        if (type === "radio") $find.prop("checked", true).trigger("input").trigger("change")
                        else $find.val(x[data]).trigger("input").trigger("change")
                        break
                    default:
                        if ($find.length) $find.val(x[data]).html(x[data]).trigger("input").trigger("change")
                        break
                }
            }
        })
    }

    return false;
}