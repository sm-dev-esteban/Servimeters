<?php

use Model\RouteModel;

$routeM = new RouteModel;

$type = base64_decode($_GET["type"] ?? 0);
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>(╹ڡ╹ )</h1>
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
                <form id="formExcel" data-type="<?= $type ?>">
                    <div class="row">
                        <div class="col-12 col-xl-6 mb-3">
                            <label for="fechaInicio">fecha Inicio</label>
                            <input type="month" name="fechaInicio" id="fechaInicio" class="form-control" required>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label for="fechaFin">fecha Fin</label>
                            <input type="month" name="fechaFin" id="fechaFin" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                            <button type="button" class="btn btn-success" data-action="excel"><i class="fa fa-file-excel"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table" id="tableExcel">
                                <thead></thead>
                                <tbody></tbody>
                                <tfoot></tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>