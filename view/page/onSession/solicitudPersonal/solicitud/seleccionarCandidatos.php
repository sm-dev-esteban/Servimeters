<?php

use Model\RouteModel;

$routeM = new RouteModel;

$user = $_SESSION;

$report = $_GET["report"] ?? false;
if ($report) $report = base64_decode($report);
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
                            <form data-mode="seleccionarCandidatos">
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
                <div class="card card-primary" id="card-candidates">
                    <div class="card-header">
                        <h3 class="card-title">Candidatos</h3>
                    </div>
                    <div class="card-body"></div>
                </div>
            </div>
        </div>
    </div>
</section>