<?php include_once("../../controller/automaticForm.php") ?>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">REQUISICIÓN DE PERSONAL</h3>
            </div>
            <div class="card-body">
                <form id="envioSolicitud">
                    <div class="row">
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Proceso</label>
                            <select name="data[id_proceso]" class="form-control" required>
                                <?php $proceso = AutomaticForm::getDataSql("requisicion_proceso") ?>
                                <option value="">Seleccione</option>
                                <?php foreach ($proceso as $data) : ?>
                                    <option value="<?= $data["id"] ?>">
                                        <?= $data["nombre"] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Nombre del cargo</label>
                            <input type="text" name="data[nombreCargo]" class="form-control" required>
                        </div>
                        <div class="col-12 col-xl-12 mb-3">
                            <label>* Descripción de actividades, principales:</label>
                            <textarea name="data[descripciónActividades]" class="form-control" required></textarea>
                        </div>
                        <div class="col-12 col-xl-12 mb-3">
                            <hr>
                        </div>
                        <div class="col-12 col-xl-12 mb-3">
                            <label>
                                <?php $arrayContent = json_encode([
                                    [
                                        "div" => [
                                            "class" => "col-12",
                                            "html" => [
                                                [
                                                    "label" => [
                                                        "type" => "text",
                                                        "text" => "Código"
                                                    ]
                                                ], [
                                                    "input" => [
                                                        "type" => "text",
                                                        "required" => true,
                                                        "class" => "form-control"
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ], JSON_UNESCAPED_UNICODE) ?>
                                * Código:
                                <!-- <span class="btn text-success" data-toggle="modal" data-target="#modalMain<?= date("Y") ?>" data-body=<?= $arrayContent ?>><i class="fa fa-plus"></i></span> -->
                            </label>

                            <!-- <select name="data[codigo]" class="form-control" required>
                                <?php $codigo = AutomaticForm::getDataSql("requisicion_codigo") ?>
                                <option value="">Seleccione</option>
                                <?php foreach ($codigo as $data) : ?>
                                    <option value="<?= $data["id"] ?>">
                                        <?= $data["nombre"] ?>
                                    </option>
                                <?php endforeach ?>
                            </select> -->
                            <input type="text" name="data[codigo]" class="form-control" required>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Tipo de contrato:</label>
                            <select name="data[contrato]" class="form-control" required>
                                <?php $contrato = AutomaticForm::getDataSql("requisicion_contrato") ?>
                                <option value="">Seleccione</option>
                                <?php foreach ($contrato as $data) : ?>
                                    <option value="<?= $data["id"] ?>">
                                        <?= $data["nombre"] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Horario:</label>
                            <select name="data[horario]" class="form-control" required>
                                <?php $horario = AutomaticForm::getDataSql("requisicion_horario") ?>
                                <option value="">Seleccione</option>
                                <?php foreach ($horario as $data) : ?>
                                    <option value="<?= $data["id"] ?>">
                                        <?= $data["nombre"] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Sueldo:</label>
                            <input type="number" name="data[sueldo]" class="form-control" data-show=["#desc1"] required>
                            <span class="small" id="desc1">0</span>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Auxilio Extralegal:</label>
                            <input type="number" name="data[auxilioExtralegal]" class="form-control" data-show="#desc2" required>
                            <span class="small" id="desc2">0</span>
                        </div>
                        <div class="col-12 col-xl-12 mb-3">
                            <hr>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Motivo de Requisición</label>
                            <select name="data[motivoRequisición]" class="form-control" required>
                                <?php $motivo = AutomaticForm::getDataSql("requisicion_motivo") ?>
                                <option value="">Seleccione</option>
                                <?php foreach ($motivo as $data) : ?>
                                    <option value="<?= $data["id"] ?>">
                                        <?= $data["nombre"] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* Fecha a contratar</label>
                            <input type="date" name="data[fechaContratacion]" class="form-control" required min="<?= date("Y-m-d") ?>">
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
                            <input type="text" name="data[solicitadoPor]" class="form-control" required>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label>* E-mail solitado por</label>
                            <input type="email" name="data[emailsolicitadoPor]" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <input type="hidden" name="data[estado]" value="<?= AutomaticForm::getValueSql("Pendiente", "nombre", "id", "requisicion_estado") ?>">
                            <button type="submit" class="btn btn-success">Enviar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalMain<?= date("Y") ?>" tabindex="-1" role="dialog" aria-labelledby="modalMain<?= date("Y") ?>Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formMain<?= date("Y") ?>">
                <div class="modal-header">¯\_(ツ)_/¯</div>
                <div class="modal-body">¯\_(ツ)_/¯</div>
                <div class="modal-footer">¯\_(ツ)_/¯</div>
            </form>
        </div>
    </div>
</div>

<JSON-loadScript style="display: none;">
    <?= json_encode([
        "../assets/js/sPersonal.js"
    ], JSON_UNESCAPED_UNICODE) ?>
</JSON-loadScript>