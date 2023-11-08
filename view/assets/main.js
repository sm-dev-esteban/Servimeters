$(document).ready(() => {
    const $modalMain = $(`#modalMain`)
    $modalMain.on(`show.bs.modal`, function (e) {
        const $this = $(this) // modal
        const $btn = $(e.relatedTarget) // boton que presionaron
        const mode = $btn.data(`mode`) // el modo para 

        const $modalDialogOriginal = $this.find(".modal-dialog") // por si acaso

        const $modalDialog = $this.find(".modal-dialog").attr("class", "modal-dialog")

        const $modalContent = $modalDialog.find(".modal-content")

        const
            $modalMainBody = $modalContent.find("#modalMainContent").html(`<div class="modal-body"></div>`),
            $modalHeader = $modalContent.find(".modal-header"),
            $modalBody = $modalContent.find(".modal-body"),
            $modalFooter = $modalContent.find(".modal-footer")

        const $modalTitle = $modalHeader.find(".modal-title").text(`¯\_(ツ)_/¯`)

        switch (mode) {
            case "reportHE":
            case "reportSP":
                const id = $btn.data(`id`)
                $.ajax(`${GETCONFIG("SERVER_SIDE")}/View/page/onSession/backend.php?action=${mode}`, {
                    type: "POST",
                    dataType: "HTML",
                    data: {
                        id: id
                    },
                    success: (response) => {
                        $modalDialog.addClass("modal-xl")
                        $modalTitle.text(`${{
                            "reportHE": "Reporte #",
                            "reportSP": "N° Requisición: "
                        }[mode] + window.atob(id)}`)
                        $modalMainBody.html(response)
                    }
                })
                break
            case "commentHE":
                break
            default:
                console.log(`Failed modalMain: ${mode}`)
                break
        }
    })
})

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
}, updateDatatable = () => { /*---- Actualiza DataTable ----*/
    $(`table.dataTable`).DataTable().ajax.reload(null, false)
}, ldapAutoComplete = (array, config = {}) => {
    /**
     * Esta función es uno de los mayores pajasos mentales que he tenido con js ༼ つ ◕_◕ ༽つ 
     * El caso, pensando en como hacer que se viera bien un autocompletado, pensé en el buscador de google y tome la idea para hacer esta función
     * Ejemplo de uso...
    const autoComplete = [
        {
            element: `#mail`, // Indicador del elemento
            event: `input`, // Evento con el que va a reaccionar la función
            search: `mail` // Campo del directorio activo con el que va a completar
        }
    ]
    const config = { // No es obligatorio
        limit: 1, // Limite de resultados
        url: "backend.php" // De momento me parece útil que también pueda cambiar la url ya sea que quiera obtener los datos de otro lado
    }
    ldapAutoComplete(autoComplete, config)
    */
    const newConfig = $.extend({
        limit: 1,
        url: `${GETCONFIG("SERVER_SIDE")}/View/page/onSession/backend.php?action=ldapFind`,
        dataForLDAP: true
    }, typeof config === `object` ? config : {})

    if (typeof array === `object`) array.forEach(autoComplete => {

        const $find = $(autoComplete[`element`] ?? false)

        if ($find.length && !$find.data("ldapautocomplete")) {

            $find.attr("data-ldapautocomplete", true)

            const tagName = $find.prop("tagName")

            switch (tagName) {
                case "INPUT":
                    const $container = $(elementCreator("div", {
                        class: "position-relative"
                    }))

                    const $input1 = $find.clone().appendTo($container)
                    const $input2 = $find.clone().appendTo($container)

                    $input2.css({
                        "background-color": "transparent",
                        position: "absolute",
                        top: 0,
                        left: 0,
                        "z-index": 1,
                        "pointer-events": "none"
                    }).removeAttr("name").removeAttr("required")

                    $find.replaceWith($container)

                    $input1.on(`${autoComplete["event"] ?? "input"}`, () => {
                        const val1 = $input1.val()
                        const val2 = $input2.val()
                        // if (!val2.toUpperCase().includes(val1.toUpperCase())) $.ajax(newConfig.url, {
                        if (!val2.toUpperCase().startsWith(val1.toUpperCase())) $.ajax(newConfig.url, {
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                // limit: newConfig.limit,
                                limit: 1,
                                filter: [autoComplete["search"] ?? false],
                                search: `${val1}*`
                            },
                            success: (response) => {
                                if (response.length && !response.error && autoComplete.search && val1 != val2)
                                    if (response[0][autoComplete.search] ?? false) {
                                        const search = (
                                            newConfig.dataForLDAP == true
                                                ? (response[0][autoComplete.search][0] ?? false) // datos de ldap
                                                : response[0][autoComplete.search] // datos de arreglo
                                        )
                                        $input2.val(search ? val1 + search.substr(val1.length) : "")
                                    } else $input2.val("")
                                else $input2.val("")
                            }
                        })
                        else $input2.val(val2 ? val1 + val2.substr(val1.length) : "")
                    }).on("keypress keydown", (e) => {
                        const val1 = $input1.val()
                        const val2 = $input2.val()
                        if (e.which === 9 && val2 != "" && val1 != val2) { // Evento de la tecla "Tab ↹"
                            e.preventDefault()
                            $input1.val(val2)
                        }
                    }).on("blur", () => {
                        $input2.val("")
                    })
                    break
                default:
                    console.log(`dio mio, pero eto que eh: ${tagName}`)
                    break
            }
        }
    })
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
}, xls = (element, config = {}) => {
    let fecha, params
    fecha = new Date().toLocaleDateString(locale, { weekday: "long", year: "numeric", day: "numeric", month: "2-digit" })
    fecha = fecha.replace(",", "")

    let $this, $innerHTML, $tagName
    $this = $($(element).get(0))
    if (!$this || $this.length == 0) alerts({
        title: `Error al descargar el archivo XLS. Indicador no encontrado -> ${element}`,
        icon: `error`
    })
    else {
        $innerHTML = $this.html()
        $tagName = $this.prop(`tagName`)

        params = $.extend({
            title: `@now`,
            filename: `@now`,
            // checkTable: true
        }, config)

        Object.keys(params).forEach(x => {
            if (typeof params[x] === "string")
                params[x] = params[x].replace("@now", fecha)
        })

        noValidFn = {
            " ": "_",
            ",": "_"
        }

        for (data in noValidFn) {
            search = data
            replace = noValidFn[data]
            params.filename = params.filename.replaceAll(search, replace)
        }

        noValidCo = [
            /<input(.*?)>/g
        ]

        for (data in noValidCo)
            $innerHTML = $innerHTML.replace(noValidCo[data], "")

        if (!params.filename.includes(`.xls`))
            params.filename = `${params.filename}.xls`

        if (params.checkTable === true ? $tagName == `TABLE` : true) {
            let data, http
            data = JSON.stringify({ param: params, content: $innerHTML })
            http = new XMLHttpRequest()

            http.open(`POST`, `${GETCONFIG("SERVER_SIDE")}/View/page/onSession/backend.php?action=xls`)
            http.onload = () => {
                if (http.status === 200) {
                    let blob, url, a
                    blob = new Blob([http.response], { type: `application/vnd.ms-excel` })
                    url = URL.createObjectURL(blob)
                    a = createElem(`a`, {
                        href: url,
                        download: params.filename
                    })
                    document.body.appendChild(a)
                    a.click()
                    document.body.removeChild(a)
                    URL.revokeObjectURL(url)
                    alerts({
                        title: `Se descargó un archivo XLS`,
                        icon: `success`
                    })
                } else alerts({
                    title: `Error al descargar el archivo XLS`,
                    icon: `error`
                })
            }
            http.responseType = `arraybuffer`
            http.send(data)
        } else alerts({
            title: `Etiqueta no valida para excel`,
            icon: `info`
        })
    }
}