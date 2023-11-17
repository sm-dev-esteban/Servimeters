<?php

use Model\RouteModel;

$routeM = new RouteModel;

$user = $_SESSION;

$report = $_GET["report"] ?? false;
if ($report) $report = base64_decode($report);

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
                <h1>Agregar Candidatos</h1>
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
            <?php if ($report === false) : ?>
                <div class="col-12 mb-3">
                    <div class="card" id="card-search">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="far fa-list-alt"></i>
                                N° Requisición
                            </h3>
                        </div>
                        <div class="card-body pt-0">
                            <form data-mode="agregarCandidatos">
                                <div class="mb-3">
                                    <input type="number" name="requisicion" id="requisicion" class="form-control" value="<?= $report ?>">
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success">Buscar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif ?>
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
                                <?php if ($user["manages"] === "RH") : ?>
                                    <tr>
                                        <td colspan="3">
                                            <button class="btn btn-success" id="btn-agregar-candidato"><i class="fa fa-plus"></i></button>
                                        </td>
                                    </tr>
                                <?php endif ?>
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

<div class="spinner-border" role="status" id="load-spinner" style="
    position: fixed;
    bottom: 5px;
    right: 5px;
    display: none;
    ">
    <span class="sr-only">Loading...</span>
</div>