(function ($) {
    $.fn.createDropzone = function (config = {}) {

        const urlForRequest = `${GETCONFIG("SERVER_SIDE")}/View/assets/createDropzone`

        const $mainThis = $(this)
        const mainThis = $(this).get(0)
        const ident = Date.now()

        const defaultConfig = {
            preview: false,
            action: "INSERT",
            table: "Dropzone",
            id: false
        }
        config = $.extend(defaultConfig, config)

        if (this) $.ajax(`${urlForRequest}/createDropzone.php?action=template`, {
            dataType: "HTML",
            type: "POST",
            data: {
                ident: ident
            },
            success: (template) => {
                const $inputsFile = $mainThis.find("input:file")
                if ($inputsFile.length) $inputsFile.each(function (i) {
                    const $input = $(this)
                    const attrs = {}

                    $input.get(0).attributes.forEach(e => {
                        attrs[e.name] = e.value
                    })

                    $input.replaceWith(
                        elementCreator("div", {
                            "data-createDropzone": ident,
                            id: attrs["id"] ?? ident
                        })
                    )

                    const $div = $(`[data-createDropzone="${ident}"]`)
                    $div.append(template.replaceAll(ident, `${i}-${ident}`))

                    const $previewNode = $(`#template-${i}-${ident}`)
                    $previewNode.removeAttr(`id`)

                    const previewTemplate = $previewNode.parent().html()
                    $previewNode.parent().get(0).removeChild($previewNode.get(0))

                    const mainConfig = $.extend({
                        url: `${urlForRequest}/createDropzone.php?action=${config.action}&table=${config.table}`,
                        autoProcessQueue: false,
                        uploadMultiple: attrs[`multiple`] ? true : false,
                        parallelUploads: 100,
                        maxFiles: 100,
                        paramName: attrs[`name`] ?? `file[Dropzone][]`,
                        previewTemplate: previewTemplate,
                        previewsContainer: `#previews-${i}-${ident}`,
                        clickable: `.fileinput-button-${i}-${ident}`,
                        init: function () {
                            var myDropzone = this

                            $(this.element).on("submit", (e) => {
                                e.preventDefault()

                                if (myDropzone.files.length == 0) myDropzone._uploadData( // (づ￣ 3￣)づ Por si acaso
                                    [
                                        {
                                            upload: {
                                                filename: ''
                                            }
                                        }
                                    ],
                                    [
                                        {
                                            filename: '',
                                            name: '',
                                            data: new Blob()
                                        }
                                    ]
                                )
                                myDropzone.processQueue()
                            })

                            $(this.element).find(`#actions-${i}-${ident} .cancel`).on(`click`, () => {
                                myDropzone.removeAllFiles(true)
                            })

                            this.on("totaluploadprogress", function (progress) {
                                document.querySelector(`#total-progress-${i}-${ident} .progress-bar`).style.width = `${progress}%`
                            })

                            this.on("queuecomplete", function (progress) {
                                document.querySelector(`#total-progress-${i}-${ident}`).style.opacity = "0"
                            })

                            this.on("sendingmultiple", function () { })
                            this.on("successmultiple", function (files, response) {
                                console.log(response);
                            })
                            this.on("errormultiple", function (files, response) {
                                console.log(response);
                            })
                        }
                    }, config)

                    const cdz = new Dropzone(mainThis, mainConfig)
                    return cdz
                })
            }
        })
        else throw Error(`Selector no valido ${this}`)
    }
}(jQuery))