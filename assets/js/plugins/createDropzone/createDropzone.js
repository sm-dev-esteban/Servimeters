(function ($) {
    const cdzUrlForRequest = `${location.origin}/${location.pathname.split("/")[1]}/assets/js/plugins/createDropzone`;

    // const formatIdDropzone = function (idformat) {
    //     let format, ident;
    //     format = idformat;
    //     ident = format.search(`-`) + 1;
    //     format = format.replace(`-${format[ident]}`, format[ident].toUpperCase());
    //     return format.search(`-`) == -1 ? format : formatIdDropzone(format);
    // }

    const cdzIsHTML = function (str) {
        return str instanceof Element || str instanceof HTMLDocument;
    }

    const cdzCreateElem = function (tag, attrs = null) {
        let newElement = (!cdzIsHTML(tag) ? document.createElement(tag) : tag);
        if (null !== attrs) {
            for (data in attrs) {
                if ([`text`].includes(data)) {
                    newElement.textContent = attrs[data]; // html
                } else {
                    newElement.setAttribute(data, attrs[data]); // atributos
                }
            }
        }
        return newElement;
    }

    jQuery.fn.cdzTagName = function (token) {
        if (token) {
            return this.prop(`tagName`);
        }
    }

    $.fn.createDropzone = function (config) {
        let $form = $(this);
        let ident = config.ident ? config.ident : Date.now();
        // let $idDr = formatIdDropzone($form.attr(`id`) ? $form.attr(`id`) : `form${ident}`);
        let $idDr = $form.attr(`id`) ? $form.attr(`id`) : `form${ident}`;
        let tagnm = jQuery($form).cdzTagName(ident);
        let arrayAttributes = {}

        let defaultconfig = {
            preview: false,
            table: undefined,
            separator: "|/|",
            action: "INSERT",
            ident: ident
        }

        let c = $.extend(defaultconfig, config);

        if (this.length != 1 || tagnm != `FORM`) {
            alerts({
                title: `createDropzone: Selector no valido: ${tagnm}`,
                icon: `error`
            });
            return $form;
        } else {
            $inputFile = $form.find("input:file").get(0);
            $inputFile.attributes.forEach(element => {
                arrayAttributes[element.name] = element.value;
            });
            $inputFile.replaceWith(
                cdzCreateElem("div", { // Este es el div que contiene la estructura
                    "data-createDropzone": ident,
                    "id": arrayAttributes.id ? arrayAttributes.id : ident
                })
            );

            let $newDropzone = $(`[data-createDropzone="${ident}"]`);

            $newDropzone.append(`
            <div id="actions${ident}" class="row">
                <div class="col-lg-6">
                    <div class="btn-group w-100">
                    
                        <span class="btn btn-success col fileinput-button${ident}">
                            <i class="fas fa-plus"></i>
                            <span>Agregar Archivos</span>
                        </span>

                        <button type="reset" class="btn btn-warning col cancel">
                            <i class="fas fa-times-circle"></i>
                            <span>Borrar archivos</span>
                        </button>

                    </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center">
                    <div class="fileupload-process w-100">
                        <div id="total-progress${ident}" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table table-striped files" id="previews${ident}">
                <div id="template${ident}" class="row mt-2">
                    <div class="col-auto">
                        <span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
                    </div>
                    <div class="col d-flex align-items-center">
                        <p class="mb-0">
                            <span class="lead" data-dz-name></span>
                            (<span data-dz-size></span>)
                        </p>
                        <strong class="error text-danger" data-dz-errormessage></strong>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="progress progress-striped active w-100" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                        </div>
                    </div>
                    <div class="col-auto d-flex align-items-center">
                        <div class="btn-group">

                            <!--
                            <button type="button" class="btn btn-primary start">
                                <i class="fas fa-upload"></i>
                                <span>Start</span>
                            </button>
                            -->

                            <button type="button" data-dz-remove class="btn btn-danger delete">
                                <i class="fas fa-trash"></i>
                                <span>Delete</span>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
            `);


            let previewNode = document.querySelector(`#template${ident}`);
            previewNode.id = "";

            let previewTemplate = previewNode.parentNode.innerHTML;
            previewNode.parentNode.removeChild(previewNode);

            // return Dropzone.options[$idDr] = $.extend({ // The camelized version of the ID of the form element
            cdz = new Dropzone(`#${$idDr}`, $.extend({ // The camelized version of the ID of the form element

                // The configuration we've talked about above
                url: `${cdzUrlForRequest}/createDropzone.php?action=${c.action}&table=${c.table}`,
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 100,
                maxFiles: 100,
                paramName: arrayAttributes.name ? arrayAttributes.name : "file[Dropzone][]",
                previewTemplate: previewTemplate,
                previewsContainer: `#previews${ident}`,
                clickable: `.fileinput-button${ident}`,
                init: function () {
                    var myDropzone = this;

                    $(this.element).on("submit", function (e) {
                        e.preventDefault();

                        if (myDropzone.files.length == 0) {
                            myDropzone._uploadData(
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
                            );
                        }

                        myDropzone.processQueue();
                    });

                    this.element.querySelector(`#actions${ident} .cancel`).onclick = function () {
                        myDropzone.removeAllFiles(true);
                    }

                    this.on("totaluploadprogress", function (progress) {
                        document.querySelector(`#total-progress${ident} .progress-bar`).style.width = `${progress}%`
                    });

                    this.on("queuecomplete", function (progress) {
                        document.querySelector(`#total-progress${ident}`).style.opacity = "0"
                    });

                    this.on("sendingmultiple", function () { });
                    this.on("successmultiple", function (files, response) { });
                    this.on("errormultiple", function (files, response) { });
                }

            }, c));

            if (c.preview !== false) {

                $.ajax(`${cdzUrlForRequest}/createDropzone.php?action=preview`, {
                    dataType: "JSON",
                    type: "POST",
                    data: {
                        table: c.table,
                        ident: c.ident,
                        preview: c.preview,
                        separator: c.separator
                    },
                    success: function (response) {
                        response.forEach(function (file) {
                            let mockFile = {
                                name: file.name,
                                size: file.size
                            };
                            cdz.displayExistingFile(mockFile, file.dirname);
                        });
                    }
                });
            }

            return cdz;
        }
    }
}(jQuery));