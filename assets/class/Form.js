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

    getAjaxSettings() { return this.ajaxSettings }
    setAjaxSettings(newSettings) { this.ajaxSettings = { ...this.ajaxSettings, ...newSettings } }

    // preLoadData() { }

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
        $object.each((i, el) => {
            const $el = $(el);
            const name = $el.attr("name") || $el.data("name");
            const value = $el.val() || $el.text();
            this.ajaxSettings.data.append(name, value);
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
