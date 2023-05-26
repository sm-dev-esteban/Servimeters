/*
* - CREATE 
* 25/05/2023
*/

/**
 * @author Esteban Serna Palacios 😉😜
 * @version 0.0.0
*/
(function ($) {

    const cdzUrlForRequest = `${location.origin}/${location.pathname.split("/")[1]}/assets/js/plugins/createDropzone`;
    const cdzUrlForFiles = `${location.origin}/${location.pathname.split("/")[1]}/files`;

    jQuery.fn.cdzTagName = function (token) {
        if (token) {
            return this.prop(`tagName`);
        }
    }

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

    jQuery.fn.cdzUpdateInput = function (array, table, separator) {
        let names = [], newValue = "";
        for (x in array) {
            // names.push(`${cdzUrlForFiles}/${table}/${array[x].name}`);
            names.push(`_${array[x].name}`);
        }
        newValue = names.join(separator);
        this.val(newValue);
        console.log(newValue);
    }

    $.fn.createDropzone = function (config) {
        let $this = $(this);
        let ident = Date.now();
        let tagnm = jQuery($this).cdzTagName(ident);
        let arrayAttributes = {}

        let defaultconfig = {
            preview: false,
            table: undefined,
            processOnEvent: {
                target: `#actions${ident} .start`,
                event: "click",
                preventDefault: false,
                stopPropagation: false
            },
            separator: "|/|"
        }

        let c = $.extend(defaultconfig, config);

        $this.get(0).attributes.forEach(element => {
            arrayAttributes[element.name] = element.value;
        });

        if (!c.table || tagnm !== `INPUT` || arrayAttributes.type !== `file` || (!c.processOnEvent.target || !c.processOnEvent.event)) {
            alerts({
                title: `createDropzone: ` + (
                    !c.table ? `Nombre de la tabla es obligatorio.` : (
                        tagnm !== `INPUT` ? `Etiqueta no valida: ${tagnm}` : (
                            arrayAttributes.type !== `file` ? `tipo de campo no valido: ${arrayAttributes.type}` : (
                                !c.processOnEvent.target || !c.processOnEvent.event ? `processOnEvent required -> {target: "${c.processOnEvent.target}", event: "${c.processOnEvent.event}"}` :
                                    `Error is undefined :/`
                            )
                        )
                    )
                ),
                icon: `Error`,
                duration: `10000`
            });
            return $this;
        }

        $this.replaceWith(
            cdzCreateElem("div", { // Este es el div que contiene la estructura
                "data-createDropzone": ident,
                "id": arrayAttributes.id ? arrayAttributes.id : ident
            }),
            cdzCreateElem("input", { // Input de tipo hidden con el nombre del campo original (va a contener las rutas de los archivos)
                "hidden-createDropzone": ident,
                "type": "hidden",
                "name": arrayAttributes.name ? arrayAttributes.name.replace("file[", "data[").replace("[]", "") : "data[dropzone]"
            })

        );

        let $inputH = $(`[hidden-createDropzone="${ident}"]`);
        $newThis = $(`[data-createDropzone="${ident}"]`);

        $newThis.append(`
        <div id="actions${ident}" class="row">
            <div class="col-lg-6">
                <div class="btn-group w-100">
                    <span class="btn btn-success col fileinput-button${ident}">
                        <i class="fas fa-plus"></i>
                        <span>Agregar Archivos</span>
                    </span>
                    
                    ${(c.processOnEvent.target == `#actions${ident} .start` ? `
                    <button type="submit" class="btn btn-primary col start">
                        <i class="fas fa-upload"></i>
                        <span>Subir archivos</span>
                    </button>
                    ` : ``)}

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

        Dropzone.autoDiscover = false;

        let previewNode = document.querySelector(`#template${ident}`);
        previewNode.id = "";

        let previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        console.log($newThis.get(0));

        let myDropzone = new Dropzone($newThis.get(0), $.extend({
            url: `${cdzUrlForRequest}/createDropzone.php?action=processData&table=${c.table}`,
            paramName: arrayAttributes.name ? arrayAttributes.name.replace("data[", "file[").replace("[]", "") : "file[Dropzone]",
            uploadMultiple: arrayAttributes.multiple ? true : false,
            acceptedFiles: arrayAttributes.accept ? arrayAttributes.accept : "image/*",
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false,
            previewsContainer: `#previews${ident}`,
            clickable: `.fileinput-button${ident}`
        }, config));

        // sube un archivo individual pero de momento no lo voy a usar
        // myDropzone.on("addedfile", function (file) {
        //     file.previewElement.querySelector(".start").onclick = function () { myDropzone.enqueueFile(file) }
        // });

        myDropzone.on("totaluploadprogress", function (progress) {
            jQuery($inputH).cdzUpdateInput(myDropzone.files, c.table, c.separator);
            document.querySelector(`#total-progress${ident} .progress-bar`).style.width = `${progress}%`
        });

        // myDropzone.on("sending", function (file) {
        //     jQuery($inputH).cdzUpdateInput(myDropzone.files, c.table, c.separator);
        //     document.querySelector(`#total-progress${ident}`).style.opacity = "1"
        //     file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
        // });

        myDropzone.on("queuecomplete", function (progress) {
            jQuery($inputH).cdzUpdateInput(myDropzone.files, c.table, c.separator);
            document.querySelector(`#total-progress${ident}`).style.opacity = "0"
        });

        $(c.processOnEvent.target).on(c.processOnEvent.event, function (e) { // Segun el evento con el que se configure enviara todos los archivos al back para procesar los archivos
            if (c.processOnEvent.preventDefault && c.processOnEvent.preventDefault === true) {
                e.preventDefault();
            }
            if (c.processOnEvent.stopPropagation && c.processOnEvent.stopPropagation === true) {
                e.stopPropagation();
            }
            jQuery($inputH).cdzUpdateInput(myDropzone.files, c.table, c.separator);
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
            myDropzone.processQueue();
        });

        document.querySelector(`#actions${ident} .cancel`).onclick = function () {
            jQuery($inputH).cdzUpdateInput(myDropzone.files, c.table, c.separator);
            myDropzone.removeAllFiles(true)
        }

        if (c.preview === true) {
            $.ajax(`${cdzUrlForRequest}/createDropzone.php?action=preview&table=${c.table}`, {
                dataType: "JSON",
                success: function (response) {
                    response.forEach(function (file) {
                        let mockFile = {
                            name: file.name,
                            size: file.size
                        };
                        myDropzone.displayExistingFile(mockFile, file.dirname);
                    });
                }
            });
        }

        return $this;
    }
}(jQuery));