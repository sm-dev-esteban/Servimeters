/**
 * Esta clase es dependiente y teniendo en cuanta que el sistemano es node.js ni nada asi por el estilo no las puedo importar para evitar problemas
 */

class Alerts {
    constructor() {
        this.toastSweetalert = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        })

        this.toastAdminLTE = $(document)
        this.toastToastr = toastr
        this.toastWindow = null
    }

    sweetalert2(newSettings = {}) {
        const defaultSettings = {
            icon: "success",
            title: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora, quidem."
        }
        const settings = $.extend(defaultSettings, newSettings)
        this.toastSweetalert.fire(settings)
    }

    adminLTE(newSettings = {}) {
        const defaultSettings = {
            class: "bg-success",
            title: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora, quidem.",
            autohide: true,
            delay: 3000
        }
        const settings = $.extend(defaultSettings, newSettings)
        this.toastAdminLTE.Toasts('create', settings)
    }

    toastr(type = "success", message = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora, quidem.") {
        this[type](message)
    }

    window(title = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora, quidem.", newSettings = {}) {
        const defaultSettings = {
            icon: $(`link[rel="icon"]:eq(0)`).attr("href"),
            dir: "ltr"
        }
        const settings = $.extend(defaultSettings, newSettings)

        if (Notification.permission === "granted") this.toastWindow = new Notification(title, settings)
        else if (Notification.permission !== "denied") Notification.requestPermission().then((permission) => {
            if (permission === "granted") this.toastWindow = new Notification(title, settings)
        })

    }
}

// export default Alerts