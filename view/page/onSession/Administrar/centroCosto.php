<?php

use Model\RouteModel;

$routeM = new RouteModel;

$Clases = $db->executeQuery("SELECT * FROM Clase");
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Centros de costo</h1>
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
                <h3 class="card-title">Agregar centro de costo</h3>
            </div>
            <div class="card-body">
                <form data-action="I_CECO">
                    <div class="row">
                        <div class="col-12 col-xl-12 mb-3">
                            <label for="titulo">Nombre del centro de costo</label>
                            <input type="text" name="data[titulo]" id="titulo" class="form-control" required>
                        </div>
                        <div class="col-12 col-xl-12 mb-3">
                            <label for="clase">Selecione una Clase</label>
                            <select name="data[id_clase]" id="clase" class="form-control" style="width: 100%;" required>
                                <?php if (!$db::getError($Clases)) foreach ($Clases as $data) :
                                    echo <<<HTML
                                        <option value="{$data['id']}">{$data['titulo']}</option>
                                    HTML;
                                endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 col-xl-12 mb-3">
                            <button class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table class="table" data-action="ssp_CECO">
                        <thead class="shadow">
                            <tr>
                                <th>Título</th>
                                <th>Clase</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody><!-- SERVERSIDE --></tbody>
                        <tbody>
                            <tr>
                                <th>Título</th>
                                <th>Clase</th>
                                <th>Editar</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>