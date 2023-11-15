<?php

use Model\RouteModel;

$routeM = new RouteModel;

$CentrosCostos = $db->executeQuery(<<<SQL
SELECT * FROM CentrosCosto
SQL);

$Jefes = $db->executeQuery(<<<SQL
SELECT A.* from Aprobadores A inner join HorasExtras_Aprobador_Tipo B on A.id_tipo = B.id where B.nombre like '%Jefe%'
SQL);

$Gerentes = $db->executeQuery(<<<SQL
SELECT A.* from Aprobadores A inner join HorasExtras_Aprobador_Tipo B on A.id_tipo = B.id where B.nombre like '%Gerente%'
SQL);

$Estados = $db->executeQuery(<<<SQL
SELECT * from HorasExtras_Estados
SQL);

$EstadosError = $db::getError($Estados);
$EstadosEncode = json_encode(!$error ? $Estados : [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

$id = $_REQUEST["report"] ?? false;

?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Reportar horas extras</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><?= substr($routeM->getURI(), 1) ?></li>
                </ol>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Registro de horas extras</h3>
            </div>
            <div class="card-body">
                <form data-mode="<?= $id ? "UPDATE" : "INSERT" ?>">
                    <div class="row">
                        <div class="col-12 col-xl-3 mb-3">
                            <label for=""><b class="text-danger">*</b> Cedula</label>
                            <input type="number" name="data[CC]" class="form-control" required>
                        </div>
                        <div class="col-12 col-xl-3 mb-3">
                            <label for=""><b class="text-danger">*</b> Cargo</label>
                            <input type="text" name="data[cargo]" class="form-control" required>
                        </div>
                        <div class="col-12 col-xl-3 mb-3">
                            <label for="mes"><b class="text-danger">*</b> Mes reportado</label>
                            <input type="month" name="data[mes]" id="mes" class="form-control" required>
                        </div>
                        <div class="col-12 col-xl-3 mb-3">
                            <label for=""><b class="text-danger">*</b> Correo</label>
                            <input type="email" name="data[correoEmpleado]" class="form-control" value="<?= $_SESSION["email"] ?? "" ?>" required>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label for=""><b class="text-danger">*</b> Centro de Costo</label>
                            <select name="data[id_ceco]" class="form-control" required>
                                <option value="">Seleccione</option>
                                <?php if (!$db::getError($CentrosCostos)) foreach ($CentrosCostos as $data) :
                                    echo <<<HTML
                                        <option value="{$data['id']}">{$data['titulo']}</option>
                                    HTML;
                                endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label for="">Proyecto Asociado</label>
                            <input type="text" name="data[proyecto]" class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <hr>
                        </div>
                        <div class="col-12 col-xl-12 mb-3">
                            <label for="checkAdjuntos">Adjuntar archivos</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <input type="checkbox" id="checkAdjuntos" data-toggle="popover" data-trigger="hover" title="Adjuntar archivos" data-content="Marcar esta casilla si es necesaria la carga de archivos" checked>
                                    </span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" name="file[adjuntos][]" id="adjuntos" class="custom-file-input" multiple>
                                    <label class="custom-file-label" for="adjuntos">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tableDatail">
                                    <thead class="shadow">
                                        <tr>
                                            <th><b class="text-danger">*</b> Fecha</th>
                                            <th><b class="text-danger">*</b> Actividad</th>
                                            <th>Permisos Descuentos</th>
                                            <th>Extras Diurn Ordinaria</th>
                                            <th>Extras Noct Ordinaria</th>
                                            <th>Extras Diurn Fest Domin</th>
                                            <th>Extras Noct Fest Domin</th>
                                            <th>Recargo Nocturno</th>
                                            <th>Recargo Festivo Diurno</th>
                                            <th>Recargo Festivo Noctur</th>
                                            <th>Recargo Ord Fest Noct</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="date" name="HorasExtra[fecha][]" class="form-control" required></td>
                                            <td contenteditable name="HorasExtra[novedad][]" required></td>
                                            <td contenteditable name="HorasExtra[descuento][]" step="0.5" type="number" oninput="addHours()">0</td>
                                            <td contenteditable name="HorasExtra[Ext_Diu_Ord][]" step="0.5" type="number" oninput="addHours()">0</td>
                                            <td contenteditable name="HorasExtra[Ext_Noc_Ord][]" step="0.5" type="number" oninput="addHours()">0</td>
                                            <td contenteditable name="HorasExtra[Ext_Diu_Fes][]" step="0.5" type="number" oninput="addHours()">0</td>
                                            <td contenteditable name="HorasExtra[Ext_Noc_Fes][]" step="0.5" type="number" oninput="addHours()">0</td>
                                            <td contenteditable name="HorasExtra[Rec_Noc][]" step="0.5" type="number" oninput="addHours()">0</td>
                                            <td contenteditable name="HorasExtra[Rec_Fes_Diu][]" step="0.5" type="number" oninput="addHours()">0</td>
                                            <td contenteditable name="HorasExtra[Rec_Fes_Noc][]" step="0.5" type="number" oninput="addHours()">0</td>
                                            <td contenteditable name="HorasExtra[Rec_Ord_Fes_Noc][]" step="0.5" type="number" oninput="addHours()">0</td>
                                            <td>
                                                <input type="hidden" name="HorasExtra[id][]" value="0">
                                                <button type="button" class="btn btn-danger" disabled onclick="remove(this)"><i class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2">
                                                <button type="button" class="btn btn-primary" onclick="add()"><i class="fa fa-plus"></i></button>
                                            </td>
                                            <td contenteditable="false" name="data[Total_descuento]">0</td>
                                            <td contenteditable="false" name="data[Total_Ext_Diu_Ord]">0</td>
                                            <td contenteditable="false" name="data[Total_Ext_Noc_Ord]">0</td>
                                            <td contenteditable="false" name="data[Total_Ext_Diu_Fes]">0</td>
                                            <td contenteditable="false" name="data[Total_Ext_Noc_Fes]">0</td>
                                            <td contenteditable="false" name="data[Total_Rec_Noc]">0</td>
                                            <td contenteditable="false" name="data[Total_Rec_Fes_Diu]">0</td>
                                            <td contenteditable="false" name="data[Total_Rec_Fes_Noc]">0</td>
                                            <td contenteditable="false" name="data[Total_Rec_Ord_Fes_Noc]">0</td>
                                            <th>Totales</td>
                                        </tr>
                                        <tr>
                                            <td colspan="11" align="right">Total Permisos descuentos</td>
                                            <td contenteditable="false" name="data[Total_Descuentos]">0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="11" align="right">Total horas extras</td>
                                            <td contenteditable="false" name="data[Total_Extras]">0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="11" align="right">Total recargos</td>
                                            <td contenteditable="false" name="data[Total_Recargos]">0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="11" align="right">Total</td>
                                            <td contenteditable="false" name="data[Total_Horas]">0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 center">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="<?= date("YmdG") ?>" name="data[chechAprobador]" value="Edición" checked>
                                    <label for="<?= date("YmdG") ?>">
                                        Edición
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label for="">Jefe</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <input type="radio" name="data[chechAprobador]" value="Jefes">
                                    </span>
                                </div>
                                <select id="Jefes" class="form-control" disabled>
                                    <option value="">Seleccione</option>
                                    <?php if (!$db::getError($Jefes)) foreach ($Jefes as $data) :
                                        echo <<<HTML
                                            <option value="{$data['id']}">{$data['nombre']}</option>
                                        HTML;
                                    endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label for="">Gerente</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><input type="radio" name="data[chechAprobador]" value="Gerentes"></span>
                                </div>
                                <select id="Gerentes" class="form-control" disabled>
                                    <option value="">Seleccione</option>
                                    <?php if (!$db::getError($Gerentes)) foreach ($Gerentes as $data) :
                                        echo <<<HTML
                                            <option value="{$data['id']}">{$data['nombre']}</option>
                                        HTML;
                                    endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <input type="hidden" name="report" value="<?= $id ?>" id="report">
                            <input type="hidden" name="data[id_estado]" value="">
                            <input type="hidden" name="data[id_aprobador]">
                            <input type="hidden" name="data[fecha_inicio]">
                            <input type="hidden" name="data[fecha_fin]">
                            <input type="hidden" name="data[reportador_por]" value="<?= $_SESSION["usuario"] ?>">
                            <input type="hidden" name="data[id_aprobador_checked]" value="0">
                            <input type="hidden" name="data[codigos]">
                            <button class="col-12 btn btn-outline-primary"><i class="fa fa-check-circle"></i> Enviar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php /* queria evitar un poco las peticiones y probar asi */ ?>
<script>
    <?= <<<JS
    function getStatusHE(q = true, w = true, e = "==") {
        return {$EstadosEncode}.filter((x) => {
            return {
                "===": (x[q] ?? false) === w,
                "!==": (x[q] ?? false) !== w
            }[e] ?? (x[q] ?? false) == w
        })
    }
    JS ?>
</script>