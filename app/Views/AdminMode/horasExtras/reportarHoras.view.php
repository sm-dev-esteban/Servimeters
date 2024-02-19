<?php

$thead = [
    "<sup class=\"text-danger mr-1\">*</sup>Fecha",
    "<sup class=\"text-danger mr-1\">*</sup>Actividad",
    "Permisos Descuentos",
    "Extras Diurn Ordinaria",
    "Extras Noct Ordinaria",
    "Extras Diurn Fest Domin",
    "Extras Noct Fest Domin",
    "Recargo Nocturno",
    "Recargo Festivo Diurno",
    "Recargo Festivo Noctur",
    "Recargo Ord Fest Noct",
    ""
];

$showTH = fn (array $array): string => implode("\n", array_map(function ($str) use ($array) {
    $countThead = count($array);
    $width = 100 / $countThead;
    return "<th style=\"width: {$width}%\">{$str}</th>";
}, $array));

$id = base64_decode($_GET["report"] ?? false);

$rand = rand();

?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Reportar Horas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">horasExtras</li>
                    <li class="breadcrumb-item active">reportarHoras</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class=card-title>Formulario</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm">
                                <i class="fa fa-cog"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="row" data-mode="<?= $id ? "UPDATE" : "INSERT" ?>">
                            <div class="col-12 col-xl-3 mb-3">
                                <label><sup class="text-danger mr-1">*</sup>Cédula</label>
                                <input type="number" name="data[CC]" class="form-control" required="required">
                            </div>
                            <div class="col-12 col-xl-3 mb-3">
                                <label><sup class="text-danger mr-1">*</sup>Cargo</label>
                                <select name="data[cargo]" class="form-control" style="width: 100%" required></select>
                            </div>
                            <div class="col-12 col-xl-3 mb-3">
                                <label><sup class="text-danger mr-1">*</sup>Mes reportado</label>
                                <input type="month" name="data[mesReportado]" class="form-control" required>
                            </div>
                            <div class="col-12 col-xl-3 mb-3">
                                <label><sup class="text-danger mr-1">*</sup>Correo</label>
                                <input type="email" name="data[correoEmpleado]" class="form-control" value="<?= $_SESSION["email"] ?>" required>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label><sup class="text-danger mr-1">*</sup>Centro de costo</label>
                                <select name="data[id_ceco]" class="form-control" required style="width: 100%"></select>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label>Proyecto asociado</label>
                                <input type="text" name="data[proyecto]" class="form-control">
                            </div>
                            <div class="col-12 col-xl-12 mb-3">
                                <label for="checkAdjuntos">Adjuntar archivos<sup class="fa fa-info ml-1 text-info" data-toggle="popover" data-trigger="hover" title="Adjuntar archivos" data-content="Marcar esta casilla si es necesaria la carga de archivos"></sup></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <input name="data[enviaAdjuntos]" value="<?= true ?>" type="checkbox" id="checkAdjuntos">
                                        </span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="file[adjuntos][]" id="adjuntos" class="custom-file-input" multiple disabled>
                                        <label class="custom-file-label" for="adjuntos">Disabled</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-3 table-responsive">
                                <table class="table" id="tableDatail">
                                    <thead>
                                        <?= $showTH($thead) ?>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="date" name="HorasExtra[fecha][]" class="form-control" required></td>
                                            <td contenteditable="true" name="HorasExtra[novedad][]" type="text"></td>
                                            <td contenteditable="true" step=".5" name="HorasExtra[Descuento][]" type="number">0</td>
                                            <td contenteditable="true" step=".5" name="HorasExtra[Ext_Diu_Ord][]" type="number">0</td>
                                            <td contenteditable="true" step=".5" name="HorasExtra[Ext_Noc_Ord][]" type="number">0</td>
                                            <td contenteditable="true" step=".5" name="HorasExtra[Ext_Diu_Fes][]" type="number">0</td>
                                            <td contenteditable="true" step=".5" name="HorasExtra[Ext_Noc_Fes][]" type="number">0</td>
                                            <td contenteditable="true" step=".5" name="HorasExtra[Rec_Noc][]" type="number">0</td>
                                            <td contenteditable="true" step=".5" name="HorasExtra[Rec_Fes_Diu][]" type="number">0</td>
                                            <td contenteditable="true" step=".5" name="HorasExtra[Rec_Fes_Noc][]" type="number">0</td>
                                            <td contenteditable="true" step=".5" name="HorasExtra[Rec_Ord_Fes_Noc][]" type="number">0</td>
                                            <td contenteditable="false">
                                                <?php if ($id) :
                                                    print <<<HTML
                                                    <input type="hidden" name="HorasExtra[id][]" value="0">
                                                    HTML;
                                                endif ?>
                                                <button type="button" class="btn btn-danger" disabled data-action="borrar">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2">
                                                <button type="button" class="btn btn-primary" data-action="agregar">
                                                    <i class="fa fa-plus mr-1"></i>Agregar
                                                </button>
                                            </td>
                                            <td contenteditable="false" name="data[Total_Descuento]">0</td>
                                            <td contenteditable="false" name="data[Total_Ext_Diu_Ord]">0</td>
                                            <td contenteditable="false" name="data[Total_Ext_Noc_Ord]">0</td>
                                            <td contenteditable="false" name="data[Total_Ext_Diu_Fes]">0</td>
                                            <td contenteditable="false" name="data[Total_Ext_Noc_Fes]">0</td>
                                            <td contenteditable="false" name="data[Total_Rec_Noc]">0</td>
                                            <td contenteditable="false" name="data[Total_Rec_Fes_Diu]">0</td>
                                            <td contenteditable="false" name="data[Total_Rec_Fes_Noc]">0</td>
                                            <td contenteditable="false" name="data[Total_Rec_Ord_Fes_Noc]">0</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="11" align=right>Total Permisos descuentos</td>
                                            <td contenteditable="false" name="data[Suma_Total_Descuentos]">0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="11" align=right>Total horas extras</td>
                                            <td contenteditable="false" name="data[Suma_Total_Extras]">0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="11" align=right>Total recargos</td>
                                            <td contenteditable="false" name="data[Suma_Total_Recargos]">0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="11" align=right>Total</td>
                                            <td contenteditable="false" name="data[Suma_Total_Horas]">0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-12 mb-3 d-flex justify-content-center">
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="radio" id="check-<?= $rand ?>" name="data[checkAprobador]" value="1" checked>
                                        <label for="check-<?= $rand ?>">Edición<sup class="fa fa-info ml-1 text-info" data-toggle="popover" data-trigger="hover" title="Modo Edición" data-content="El modo de edición permite seguir editando este reporte hasta que considere necesario el envío a revisión por parte de jefes y gerentes."></sup></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="checkAprobadorJefes">Jefe</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <input type="radio" name="data[checkAprobador]" value="4" id="checkAprobadorJefes">
                                        </span>
                                    </div>
                                    <select id="Jefes" disabled="disabled" class="form-control"></select>
                                </div>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="checkAprobadorGerentes">Gerente</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <input type="radio" name="data[checkAprobador]" value="5" id="checkAprobadorGerentes">
                                        </span>
                                    </div>
                                    <select id="Gerentes" disabled="disabled" class="form-control"></select>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <input type="hidden" name="data[id_estado]">
                                <input type="hidden" name="data[id_aprobador]">
                                <input type="hidden" name="data[fecha_inicio]">
                                <input type="hidden" name="data[fecha_fin]">
                                <input type="hidden" name="data[reportador_por]" value="<?= $_SESSION["usuario"] ?? "test" ?>">
                                <input type="hidden" name="data[codigos]">
                                <button type="submit" class="btn btn-default btn-block">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>