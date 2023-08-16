<?php include_once("../../controller/automaticForm.php") ?>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Llamar candidatos a entrevista o/ Solicitar mas candidatos</h3>
            </div>
            <div class="card-body">
                <form id="envioSolicitud">
                    <div class="row">
                        <div class="col-12 col-xl-6">
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="r1" name="typeC" value="sc">
                                    <label for="r1" class="custom-control-label">Seleccionar Candidatos</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-6">
                            <div class="form-group">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="r2" name="typeC" value="nsc">
                                    <label for="r2" class="custom-control-label">No Seleccionar Candidatos</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-12">
                            <label>* Numero Requisicion</label>
                            <input type="number" name="" class="form-control">
                        </div>
                        <div class="col-12 col-xl-12" data-show="sc">
                            <label>* Candidatos Seleccionados</label>
                            <input type="number" name="" class="form-control">
                        </div>
                        <div class="col-12 col-xl-12">
                            <label>* Observaciónes</label>
                            <input type="number" name="" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<JSON-loadScript style="display: none;">
    <?= json_encode([
        "../assets/js/sPersonal.js"
    ], JSON_UNESCAPED_UNICODE) ?>
</JSON-loadScript>