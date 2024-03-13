Dropzone.autoDiscover = false

$(async _ => {
    const handlePreviewFiles = () => $.ajax(`${URL_BACKEND}?action=preview&report=${report}&indexCollapse=${getIndexCollapseShow()}`, {
        dataType: "JSON",
        beforeSend: () => $(accordion).find(".collapse.show").parent().append($overlay),
        success: (response) => {
            const { "innerHtml": innerHtml, "message": message, "status": status } = response

            if (status === "success") $(accordion).html(innerHtml)
            else console.error(message)
        },
        complete: () => setTimeout(_ => $overlay.remove(), 2000)
    })

    const handleClickDelete = async (e) => {
        const loBorroOQue = await swalFire({
            title: "¿Estás seguro?",
            text: "¡No podrás revertir esto!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "¡Sí, bórralo!"
        }, ["input"])

        if (loBorroOQue) $.ajax(`${URL_BACKEND}?action=deleteHV&report=${report}&indexCollapse=${getIndexCollapseShow()}`, {
            type: "POST",
            dataType: "JSON",
            data: {
                delete: $(e.target).closest(".btn-group").find("a[href]:eq(0)").attr("href")
            },
            beforeSend: () => $(accordion).find(".collapse.show").parent().append($overlay),
            success: (response) => {
                const { "innerHtml": innerHtml, "message": message, "status": status } = response

                if (status === "success") $(accordion).html(innerHtml)
                else console.error(message)
            },
            complete: () => setTimeout(_ => $overlay.remove(), 2000)
        })
    }

    const getIndexCollapseShow = () => $(".collapse.show").parent().index()

    // const alerts = new Alerts()
    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/app/Views/AdminMode/solicitudPersonal/script/cargarHojasDeVida/back.php`
    const urlSearchParams = new URLSearchParams(location.search)
    const report = window.atob(urlSearchParams.get("report"))

    const accordion = document.getElementById("accordion")
    const $overlay = $(`<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>`)

    const btnDelete = "[data-delete-hv]"

    $(".dz-hidden-input").remove()

    var previewNode = document.getElementById("template")
    previewNode.id = ""
    var previewTemplate = previewNode.parentNode.innerHTML
    previewNode.parentNode.removeChild(previewNode)

    var myDropzone = new Dropzone($("#actions").parent().get(0), {
        url: `${URL_BACKEND}?action=upload`,
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        previewTemplate: previewTemplate,
        paramName: "file[adjunto][]",
        autoQueue: false,
        previewsContainer: "#previews",
        clickable: ".fileinput-button"
    })

    myDropzone.on("addedfile", (file) => file.previewElement.querySelector(".start").onclick = _ => myDropzone.enqueueFile(file))
    myDropzone.on("totaluploadprogress", (progress) => document.querySelector("#total-progress .progress-bar").style.width = `${progress}%`)
    myDropzone.on("sending", (file, xhr, formData) => {

        document.querySelector("#total-progress").style.opacity = "1"

        file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
        formData.append("data[id_report]", report)
    })

    myDropzone.on("queuecomplete", (progress) => {
        document.querySelector("#total-progress").style.opacity = "0"
        handlePreviewFiles()
    })

    document.querySelector("#actions .start").onclick = _ => myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
    document.querySelector("#actions .cancel").onclick = _ => myDropzone.removeAllFiles(true)
    $(accordion).on("click", btnDelete, handleClickDelete)
})