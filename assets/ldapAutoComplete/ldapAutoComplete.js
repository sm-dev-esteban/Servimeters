(($) => {
    $.fn.autoComplete = function (config) {
        const $el = $(this)

        if (this.length && $.isPlainObject(config)) {
            const array = [{
                element: this,
                event: config.event || "input",
                column: config.column || $el.attr("name").replace(/data\[(.*?)\]/, "$1")
            }]

            if (config.mode) config.dataForLDAP = config.mode.toUpperCase().trim() === "SQL" ? false : true

            ldapAutoComplete(array, config)
        }

        return this
    }
})(jQuery)

/**
 * Función para proporcionar autocompletado con datos del Directorio Activo o desde SQL.
 * 
 * Para obtener datos desde SQL, es necesario configurar la URL desde donde se obtendrán los datos.
 * Además, cambia "dataForLDAP" a false. El nombre y otros detalles hacen referencia a LDAP
 * porque inicialmente estructuré la función para obtener datos de LDAP.
 * Posteriormente, se modificó la función para que también pueda obtener datos de SQL.
 * 
 * @param {Array} array - Datos del elemento que tendrá autocompletado.
 *   - array[?]["element"]: Selector válido para jQuery.
 *   - array[?]["event"]: Evento para ejecutar las peticiones.
 *   - array[?]["column"]: Campo con el que se completará.
 * @param {Object} config - Configuración adicional.
 *   - config["limit"]: Límite de resultados a filtrar establecido en el backend.
 *   - config["url"]: URL a la que se realizarán peticiones. Solo aplica si "dataForLDAP" es false, indicando que no se usará el Directorio Activo.
 *   - config["dataForLDAP"]: Booleano para saber cómo se están obteniendo los datos y poder acceder a ellos.
 */
const ldapAutoComplete = (array, config = {}) => {
    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/assets/ldapAutoComplete/ldapAutoComplete.php`

    const defaultConfig = {
        limit: 1,
        url: URL_BACKEND,
        dataForLDAP: true
    }

    const newConfig = $.extend(defaultConfig, $.isPlainObject(config) ? config : {})

    if (Array.isArray(array)) {
        array.forEach(autoComplete => {
            const $this = $(autoComplete.element || null)

            if ($this.length) {
                $this.each(function (i) {
                    const $el = $(this)

                    if (!$el.data("ldapautocomplete")) {
                        $el.attr("data-ldapautocomplete", true)

                        const $container = $(`<div class="position-relative" />`)

                        const $input = $el.clone().appendTo($container)
                        const $inputMask = $el.clone().appendTo($container)

                        $inputMask.css({
                            "background-color": "transparent",
                            "z-index": 1,
                            "pointer-events": "none",
                            position: "absolute",
                            top: 0,
                            left: 0,
                            outline: "none"
                        }).removeAttr("id")

                        const validAttr = ["class", "type", "style", "data-ldapautocomplete"];

                        $inputMask.each((i, el) => el.attributes.forEach(attr => {
                            const { name: name } = attr

                            if (!validAttr.includes(name)) $(el).removeAttr(name)
                        }))

                        $el.replaceWith($container)

                        const handleEventUser = (e) => {
                            const val1 = $input.val()
                            const val2 = $inputMask.val()

                            if (!val2.toUpperCase().startsWith(val1.toUpperCase())) $.ajax(newConfig.url, {
                                type: "POST",
                                dataType: "JSON",
                                data: {
                                    limit: 1,
                                    column: autoComplete.column || false,
                                    search: val1
                                },
                                success: (response) => {
                                    if (response.length && !response.error && autoComplete.column && val1 != val2)
                                        if (response[0][autoComplete.column] || false) {
                                            const search = String(
                                                newConfig.dataForLDAP == true
                                                    ? (response[0][autoComplete.column][0] || "") // datos de ldap
                                                    : response[0][autoComplete.column] // datos de sql
                                            )
                                            $inputMask.val(search ? val1 + search.substring(val1.length) : "")
                                        } else $inputMask.val("")
                                    else $inputMask.val("")
                                }
                            })
                            else $inputMask.val(val2 ? val1 + val2.substr(val1.length) : "")

                        }, handleEventKeydown = (e) => {
                            const val1 = $input.val()
                            const val2 = $inputMask.val()

                            // Evento de la tecla "Tab ↹" o "➡"
                            if (e.which === 9 || e.which === 39) if (val2 != '' && val1 != val2) {
                                e.preventDefault()
                                $input.val(val2)
                                $inputMask.val("")
                            }
                        }

                        $input.on(`${autoComplete.event || "input"}`, handleEventUser)
                        $input.on('keydown', handleEventKeydown)
                        $input.on("blur select", () => $inputMask.val(""))
                        $inputMask.on("focus", (e) => e.target.blur())
                    }
                })
            }
        })
    }
}
