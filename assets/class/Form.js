class Form {
    constructor(urlBack, form) {
        this.uid = Date.now();

        this.urlBack = urlBack;
        this._form = form instanceof $ ? form.first() : $(form);
        this._isForm = this._form.prop("tagName") === "FORM";

        this.sendContentEditable = false;

        this.ajaxSettings = {
            type: "POST",
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false
        };

        if (!this._form.length) throw new Error("Failed to find form element");
    }

    getUrlBack() { return this.urlBack }
    setUrlBack(urlBack) { this.urlBack = urlBack; return this }

    getAjaxSettings() { return this.ajaxSettings }
    setAjaxSettings(newSettings) { this.ajaxSettings = { ...this.ajaxSettings, ...newSettings }; return this }

    preLoadData(url = this.urlBack) {
        const oldSettings = this.getAjaxSettings()
        this.setAjaxSettings({
            dataType: "JSON",
            success: (response) => {
                response.forEach(info => {
                    for (const data in info) {
                        const $find = $(`[name="data[${data}]"]`)
                        const tagName = $find.prop(`tagName`)
                        const type = $find.attr("type") || ""

                        const value = info[data]

                        if ($find.length) {
                            if ((tagName === "INPUT" || tagName === "SELECT")) {
                                if (["RADIO", "CHECKBOX"].includes(type.toUpperCase())) {
                                    $find.removeAttr("checked")
                                    $(`[name="data[${data}]"][value=${value}]`)
                                        .prop("checked", true)
                                        .trigger("input")
                                        .trigger("change")
                                } else {
                                    $find
                                        .val(value)
                                        .trigger("input")
                                        .trigger("change")
                                }
                            } else if (this.sendContentEditable === true) {
                                $find
                                    .html(value)
                                    .trigger("input")
                                    .trigger("change")
                            }
                        }
                    }
                })
            }
        })

        this.requestAjax(url)
        this.setAjaxSettings(oldSettings)

        return this
    }

    requestAjax(url = this.urlBack, settings = this.ajaxSettings) { $.ajax(url, settings) }

    async #findContentEditable() {
        const $elements = this._form.find(`[contenteditable][name], [contenteditable][data-name]`);
        if ($elements.length) this.#appendToFormData($elements);
    }

    async #findElements() {
        const $elements = this._form.find("[name]:not([contenteditable]), [data-name]:not([contenteditable])");
        if ($elements.length) this.#appendToFormData($elements);
    }

    async #appendToFormData($object) {
        $object.each((_, el) => {
            const $el = $(el);
            const name = $el.attr("name") || $el.data("name");
            const checked = el?.checked

            const value = checked === false ? false : $el.val() || $el.text();

            this.ajaxSettings.data.append(name, value ? value.trim() : value);
        });
    }

    submit = async (event) => {
        if (event) event.preventDefault();

        this.ajaxSettings.data = new FormData(this._isForm ? this._form.get(0) : undefined);

        if (!this._isForm) await this.#findElements();
        if (this.sendContentEditable === true) await this.#findContentEditable();

        this.requestAjax();
    }
}

// export default Form
