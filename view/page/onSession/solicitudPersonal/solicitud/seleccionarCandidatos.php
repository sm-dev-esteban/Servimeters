<?php

use Model\RouteModel;

$routeM = new RouteModel;

$overlay = <<<HTML
<div class="overlay">
    <i class="fas fa-2x fa-sync fa-spin"></i>
</div>
HTML;
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Seleccionar Candidatos</h1>
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
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card" id="card-search">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="far fa-list-alt"></i>
                            N° Requisición
                        </h3>
                    </div>
                    <div class="card-body pt-0">
                        <form>
                            <div class="mb-3">
                                <input type="text" name="requisicion" id="requisicion" class="form-control">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-success">Buscar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card" id="card-candidates">
                    <?= $overlay ?>
                    <div class="card-body pt-0 table-responsive">
                        <table class="table" id="table-agregar-candidato">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Nombre Completo</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <button class="btn btn-success" id="btn-agregar-candidato"><i class="fa fa-plus"></i></button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="card" id="card-report">
                    <div class="card-header border-0">
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <?= $overlay ?>
                    <div class="card-body pt-0"></div>
                </div>
            </div>
        </div>
    </div>
</section>