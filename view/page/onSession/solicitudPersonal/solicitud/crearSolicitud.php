<?php

use Model\RouteModel;

$routeM = new RouteModel;

$proceso = $db->executeQuery(<<<SQL
SELECT * FROM requisicion_proceso
SQL);

$contrato = $db->executeQuery(<<<SQL
SELECT * FROM requisicion_contrato
SQL);

$mesContrato = $db->executeQuery(<<<SQL
SELECT * FROM requisicion_meses
SQL);

$horario = $db->executeQuery(<<<SQL
SELECT * FROM requisicion_horario
SQL);

$motivo = $db->executeQuery(<<<SQL
SELECT * FROM requisicion_motivo
SQL);

$recursos = $db->executeQuery(<<<SQL
SELECT * FROM requisicion_recursos
SQL);

$estadoPendiente = $db->executeQuery(<<<SQL
SELECT * FROM requisicion_estado where nombre = 'Pendiente'
SQL);

$aprobadores = $db->executeQuery(<<<SQL
SELECT 
A.id,
A.nombre,
B.nombre aprueba
from Aprobadores A
inner join HorasExtras_Aprobador_SolicitudPersonal B on A.id_solicitudPersonal = B.id
where B.nombre = 'SI'
SQL);
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Crear Solicitud</h1>
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
                <h3 class="card-title">REQUISICIÓN DE PERSONAL</h3>
            </div>
            <div class="card-body">
                <form data-action="I_requisicion">
                    <div class="row">
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Proceso</label>
                            <select name="data[id_proceso]" class="form-control" required style="width: 100%;">
                                <option value="">Seleccione</option>
                                <?php if (!$db::getError($proceso)) foreach ($proceso as $data) :
                                    echo <<<HTML
                                        <option value="{$data['id']}">{$data['nombre']}</option>
                                    HTML;
                                endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Nombre del cargo</label>
                            <input type="text" name="data[nombreCargo]" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label>* Ciudad</label>
                            <input type="text" name="data[ciudad]" class="form-control" required list="list-ciudad">
                            <datalist id="list-ciudad">
                                <option value="guacamole">
                            </datalist>
                        </div>
                        <div class="col-12 col-xl-12 mb-3">
                            <label>* Descripción de actividades, principales:</label>
                            <textarea name="data[descripcionActividades]" class="form-control" required></textarea>
                        </div>
                        <div class="col-12 col-xl-12 mb-3">
                            <hr>
                        </div>
                        <div class="col-12 col-xl-12 mb-3">
                            <label>* Código:</label>
                            <input type="text" name="data[codigo]" class="form-control" list="list-codigo" required>
                            <datalist id="list-codigo">
                                <option value="Chocolate">
                                <option value="Coconut">
                                <option value="Mint">
                                <option value="Strawberry">
                                <option value="Vanilla">
                            </datalist>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Tipo de contrato:</label>
                            <select name="data[contrato]" class="form-control" required>
                                <option value="">Seleccione</option>
                                <?php if (!$db::getError($contrato)) foreach ($contrato as $data) :
                                    echo <<<HTML
                                        <option value="{$data['id']}">{$data['nombre']}</option>
                                    HTML;
                                endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Horario:</label>
                            <select name="data[horario]" class="form-control" required>
                                <option value="">Seleccione</option>
                                <?php if (!$db::getError($horario)) foreach ($horario as $data) :
                                    echo <<<HTML
                                        <option value="{$data['id']}">{$data['nombre']}</option>
                                    HTML;
                                endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 mb-3" style="display: none;">
                            <label for="">Meses</label>
                            <select name="data[meses]" class="form-control">
                                <option value="">Seleccione</option>
                                <?php if (!$db::getError($mesContrato)) foreach ($mesContrato as $data) :
                                    echo <<<HTML
                                        <option value="{$data['id']}">{$data['nombre']}</option>
                                    HTML;
                                endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Sueldo:</label>
                            <input type="number" name="data[sueldo]" value="0" class="form-control" data-show=["#desc1"] required>
                            <span class="small" id="desc1">0</span>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Auxilio Extralegal:</label>
                            <input type="number" name="data[auxilioExtralegal]" value="0" class="form-control" data-show="#desc2" required>
                            <span class="small" id="desc2">0</span>
                        </div>
                        <div class="col-12 col-xl-12 mb-3">
                            <hr>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Motivo de Requisición</label>
                            <select name="data[motivoRequisicion]" class="form-control" required>
                                <option value="">Seleccione</option>
                                <?php if (!$db::getError($motivo)) foreach ($motivo as $data) :
                                    echo <<<HTML
                                        <option value="{$data['id']}">{$data['nombre']}</option>
                                    HTML;
                                endforeach ?>
                                <option value="-1">Otro</option>
                            </select>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Fecha a contratar</label>
                            <input type="date" name="data[fechaContratacion]" class="form-control" required min="<?= date("Y-m-d") ?>">
                        </div>
                        <div class="col-12 mb-3" style="display: none;">
                            <label for="">* Reemplaza a</label>
                            <input type="text" name="data[reemplazaA]" class="form-control">
                        </div>
                        <div class="col-12 mb-3" style="display: none;">
                            <label for="">* Recursos necesarios para el cargo</label>
                            <div class="form-group">
                                <?php if (!$db::getError($recursos)) foreach ($recursos as $data) :
                                    echo <<<HTML
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{$data['id']}" name="data[recursos][]">
                                            <label class="form-check-label">{$data['nombre']}</label>
                                        </div>
                                    HTML;
                                endforeach ?>
                            </div>
                        </div>
                        <div class="col-12 mb-3" style="display: none;">
                            <label for="">Cual</label>
                            <input type="text" name="data[otroMotivoRequisicion]" class="form-control" list="list-otroMotivoRequisicion">
                            <datalist id="list-otroMotivoRequisicion">
                                <option value="tengo hambre">
                            </datalist>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Radicado por</label>
                            <input type="text" name="data[radicadoPor]" class="form-control" readonly value="<?= $_SESSION["usuario"] ?>" required>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Solicitado por</label>
                            <input type="text" name="data[solicitadoPor]" class="form-control" required>
                            <small>Jefe de Área</small>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Jefe que Aprobara</label>
                            <select name="data[id_aprobador]" class="form-control">
                                <option value="">Seleccione</option>
                                <?php if (!$db::getError($aprobadores)) foreach ($aprobadores as $data) :
                                    echo <<<HTML
                                        <option value="{$data['id']}">{$data['nombre']}</option>
                                    HTML;
                                endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* E-mail solitado por</label>
                            <input type="email" name="data[emailsolicitadoPor]" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <input type="hidden" name="data[estado]" value="<?= (!$db::getError($estadoPendiente)) ? $estadoPendiente[0]['id'] : "null" ?>">
                            <button type="submit" class="btn btn-success">Enviar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>