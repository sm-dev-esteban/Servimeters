<?php

use Model\RouteModel;

$routeM = new RouteModel;
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Clase</h1>
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
                <h3 class="card-title">Agregar Clase</h3>
            </div>
            <div class="card-body">
                <form data-action="I_Clase">
                    <div class="row">
                        <div class="col-12 col-xl-12 mb-3">
                            <label for="titulo">Nombre de la clase</label>
                            <input type="text" name="data[titulo]" id="titulo" class="form-control" required>
                        </div>
                        <div class="col-12 col-xl-12 mb-3">
                            <button class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table class="table" data-action="ssp_Clase">
                        <thead class="shadow">
                            <tr>
                                <th>Título</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody><!-- SERVERSIDE --></tbody>
                        <tbody>
                            <tr>
                                <th>Título</th>
                                <th>Editar</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>