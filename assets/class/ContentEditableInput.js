class ContentEditableInput {
    static eventsIsInvalid = {
        "number": (e) => "🤷",
        "email": (e) => "🤷"
    }

    static selector(el) { this.#addEventListeners(document.querySelector(el)) }
    static selectorAll(els) { document.querySelectorAll(els).forEach(el => this.#addEventListeners(el)) }

    static #addEventListeners(element) {
        if (element) element.addEventListener("keydown", e => {
            const type = element.getAttribute("type") || "text"
            type.toLowerCase()

            const isValid = this.#handleValidation(e, type)

            if (!isValid) {
                if (type === "number") {
                    e.preventDefault()
                    if (this.eventsIsInvalid[type] || false) this.eventsIsInvalid[type](e)
                }
            } else {
                if (type === "number") {
                    const step = parseFloat(element.getAttribute("step")) || 1
                    let currentValue = parseFloat(element.innerText + e.key) || 0

                    const max = parseFloat(element.getAttribute("max"))
                    const min = parseFloat(element.getAttribute("min"))

                    switch (true) {
                        case e.key === "ArrowUp":
                            currentValue += step
                            if (!isNaN(max) && currentValue > max) currentValue = max
                            break
                        case e.key === "ArrowDown":
                            currentValue -= step
                            if (!isNaN(min) && currentValue < min) currentValue = min
                            break
                        case !isNaN(max) && currentValue > max:
                            currentValue = max
                            break
                        case !isNaN(min) && currentValue < min:
                            currentValue = min
                            break
                        default: return
                    }

                    element.innerText = currentValue % step === 0 ? currentValue : step
                    const selection = window.getSelection()
                    const range = document.createRange()
                    range.selectNodeContents(element)
                    range.collapse(false)
                    selection.removeAllRanges()
                    selection.addRange(range)
                    element.dispatchEvent(this.#trigger("input"))

                    e.preventDefault()
                }
            }

        })
    }
    static #handleValidation(event, type) {
        const inputValue = event.target.innerText + event.key

        switch (type) {
            case "number":
                const isNumber = /^[0-9.]$/.test(event.key)
                if (!isNumber || ("." === event.key && event.target.innerText.includes("."))) return [
                    "Backspace",
                    "Delete",
                    "Tab",
                    "ArrowLeft",
                    "ArrowRight",
                    "ArrowUp",
                    "ArrowDown"
                ].includes(event.key)

                const step = parseFloat(event.target.getAttribute("step")) || 1
                const value = parseFloat(inputValue) || 0
                const remainder = value % step
                if (remainder !== 0) return false

                const min = parseFloat(event.target.getAttribute("min"))
                if (!isNaN(min) && value < min) return false

                const max = parseFloat(event.target.getAttribute("max"))
                if (!isNaN(max) && value > max) return false

                return true

            case "email":
                return /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(event.key)
            default:
                return true
        }
    }

    static #trigger(type, eventInitDict = {}) {
        const defaultEventInitDict = {
            bubbles: true,
            cancelable: true
        }

        return new Event(type, {
            ...defaultEventInitDict, ...eventInitDict
        })
    }

}

// export default ContentEditableInput