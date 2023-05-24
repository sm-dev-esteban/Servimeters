(function ($) {

    // cargo el modal de one para no hacer validaciones

    $("html").append(`
    <div class="modal fade" id="modal-selectMaster">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="overlay">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </div>
                <div class="modal-header">
                    <h4 class="modal-title">Select Master</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="ribbon-wrapper">
                    <div class="ribbon bg-info">
                        BETA
                    </div>
                </div>

                <div class="modal-body">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="update-content-tab" data-toggle="pill" href="#update-content" role="tab" aria-controls="update-content" aria-selected="true">Actualizar</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="add-content-tab" data-toggle="pill" href="#add-content" role="tab" aria-controls="add-content" aria-selected="false">Agregar</a>
                                </li>
                            </ul>
                            <div class="tab-content mt-3" id="custom-content-below-tabContent">
                                <div class="tab-pane fade show active" id="update-content" role="tabpanel" aria-labelledby="update-content-tab">
                                    <table class="table tableD">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Editar</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="add-content" role="tabpanel" aria-labelledby="add-content-tab">
                                    <form>
                                        <h1>Pendiente :/</h1>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `);


    const urlForRequest = `${location.origin}/${location.pathname.split("/")[1]}`;

    const smIsHTML = function (str) {
        return str instanceof Element || str instanceof HTMLDocument;
    }

    const smCreateElem = function (tag, attrs = null) {
        let newElement = (!smIsHTML(tag) ? document.createElement(tag) : tag); // valido si es un elemento html, si no es creo lo que envie y si no solo se asigna a la variable
        if (null !== attrs) {
            for (data in attrs) {
                if (["text"].includes(data)) {
                    newElement.textContent = attrs[data]; // html
                } else {
                    newElement.setAttribute(data, attrs[data]); // atributos
                }
            }
        }
        return newElement;
    }

    const smUpdateDatatable = function () {
        $('#modal-selectMaster table').DataTable().ajax.reload(null, false);
    };

    jQuery.fn.smTagName = function (token) {
        if (token) {
            return this.prop(`tagName`);
        }
    };

    jQuery.fn.smLoadContent = function (token, array, k, v) {
        if (token) {
            this.html(``);
            checkSelect2 = this.hasClass(`select2`);

            if (checkSelect2) {
                this.select2(`destroy`);
            }
            for (ident in array) {
                this.append(smCreateElem(`option`, {
                    value: array[ident][k],
                    text: array[ident][v]
                }));
            }
            if (checkSelect2) {
                this.select2();
            }
        }

    };

    jQuery.fn.smModeEdit = function (token, p, c, t) {
        if (token) {
            let $selectMaster = $(`[data-selectMaster="${token}"]`);
            // let p = window.atob(p);
            // let c = window.atob(c);
            // let t = window.atob(t);
            let v = this.val();
            let check = automaticForm("updateValueSql", [v, c, p, t]);
            if (check.error) {
                alerts({
                    text: `selectMaster->sql: ${check.error}`,
                    duration: 10000
                });
            } else {
                c = $selectMaster[0].selectMaster;
                checkSelect2 = $selectMaster.hasClass(`select2`);
                smUpdateDatatable();
                if (checkSelect2) {
                    $selectMaster.select2(`destroy`);
                }
                $(`[data-selectMaster="${token}"]`).find(`[value="${p}"]`).text(v);
                if (checkSelect2) {
                    $selectMaster.select2();
                }
            }
        }
    };

    jQuery.fn.smChangeMode = function (token, id) {
        if (token) {
            $(`[data-show="${id}"]`).toggleClass("d-none");
            $(`[data-edit="${id}"]`).toggleClass("d-none");
        }
    };


    $.fn.selectMaster = function (config, createTableIfNotExist = false) {

        let $this = $(this);
        let ident = Date.now();
        let tagnm = jQuery($this).smTagName(ident);

        if (true === createTableIfNotExist && config.table && config.option_value) {
            $checkTableExists = automaticForm("checkTableExists", [config.table]);
            if (!$checkTableExists) {
                $.ajax(`${urlForRequest}/assets/js/plugins/selectMaster.php?token=${ident}&accion=create&table=${config.table}&option_value=${config.option_value}`);
            }
        }

        if (this.length != 1 || tagnm != `SELECT` || (!config.table || !config.option_value)) {
            alerts({
                title: `selectMaster: ` + (
                    this.length != 1 ? `Objeto no encontrado.` : (
                        tagnm != `SELECT` ? `Objeto no valido: ${tagnm}.` : (
                            !config.table ? `Nombre de la tabla es obligatorio.` : (
                                !config.option_value ? `Campo a mostrar es obligatorio: option_value->${config.option_value}.` : `Error is undefined :/`
                            )
                        )
                    )
                ),
                icon: `Error`,
                duration: `10000`
            });
        } else {

            let defaultconfig = {
                table: undefined,
                option_id: `@primary`,
                option_value: undefined,
                popover: {
                    title: undefined,
                    content: undefined
                },
                select2: false
            };
            let c = $.extend(defaultconfig, config);

            if (!c.popover.title) {
                c.popover.title = `selectMaster`;
            }
            if (!c.popover.content) {
                c.popover.content = `Registros en total: @count`;
            }

            $this[0].selectMaster = c;
            $this.attr(`data-selectMaster`, ident);

            let data = automaticForm(`getDataSql`, [c.table, `1 = 1`, `${c.option_id}, ${c.option_value}`]);

            if (data.error) {
                alerts({
                    title: `selectMaster->Sql: ${data.error}`,
                    icon: `Error`,
                    duration: `10000`
                });
            } else {

                if (c.option_id == "@primary") {
                    c.option_id = automaticForm(`getNamePrimary`, [c.table])
                }

                jQuery($this).smLoadContent(ident, data, c.option_id, c.option_value);

                let pTitle = c.popover.title.replaceAll("@count", data.length);
                let pContent = c.popover.content.replaceAll("@count", data.length);

                if (c.select2 && $this.hasClass("select2")) {
                    $select2 = $this[0].nextElementSibling;
                    $poper = $($select2);
                } else {
                    $poper = $this;
                }

                $poper
                    .attr(`data-toggle`, `popover`)
                    .popover({
                        html: true,
                        container: 'body',
                        trigger: 'focus click',
                        title: function () {
                            return `
                            <div class="d-flex justify-content-between">
                                <div>${pTitle}</div>
                                <div>
                                    <a class="mt-1 p-1 rounded btn-info ${ident}">
                                        <i class="fa fa-cog"></i>
                                    </a>
                                </div>
                            </div>
                            `;
                        },
                        content: pContent
                    });

                $(document).on(`click`, `.${ident}`, function () {
                    $('.popover').popover('hide');

                    $modal = $('#modal-selectMaster');
                    $modal.css('overflow-y', 'auto');
                    $modal.find('.overlay').removeClass("d-none");
                    $modal.modal('show');

                    $table = $('#modal-selectMaster table');

                    $table.DataTable().destroy();

                    $table.DataTable(
                        $.extend(
                            datatableParams,
                            {
                                "processing": true,
                                "severSide": true,
                                "order": [[0, "desc"]],
                                "ajax": `${urlForRequest}/assets/js/plugins/selectMaster.php?token=${ident}&accion=ssp&table=${c.table}&option_value=${c.option_value}`,
                                "deferRender": true,
                                "initComplete": "",
                            }
                        )
                    );

                    setTimeout(() => {
                        $modal.find('.overlay').addClass("d-none");
                    }, 1000);

                });
            }

            return $this;

        }
    };

}(jQuery));