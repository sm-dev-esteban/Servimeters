<?php

use Model\RouteModel;

$routeM = new RouteModel;
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Gestionar horas extras</h1>
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
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-xl-6 mb-3">
                        <button class="btn btn-success btn-block" id="aprobar" data-action="aprobar_horas"><i class="fa fa-check"></i> Aprobar</button>
                    </div>
                    <div class="col-12 col-xl-6 mb-3">
                        <button class="btn btn-danger btn-block" id="rechazar" data-action="rechazar_horas"><i class="fa fa-times"></i> Rechazar</button>
                    </div>
                    <div class="col-12 col-xl-6 mb-3">
                        <button class="btn btn-primary btn-block" id="STodo" data-action="seleccionar_horas"><i class="fa fa-check-circle"></i> Seleccionar todo</button>
                    </div>
                    <div class="col-12 col-xl-6 mb-3">
                        <button class="btn btn-warning btn-block" id="DTodo" data-action="deseleccionar_horas"><i class="fa fa-times-circle"></i> Deseleccionar todo</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-hover" id="ssp_gestion">
                                <thead class="shadow">
                                    <tr>
                                        <th>Ver Detalle</th>
                                        <th>Documento</th>
                                        <th>Mes</th>
                                        <th>Colaborador</th>
                                        <th>Estado</th>
                                        <th>Clase</th>
                                        <th>Ceco</th>
                                        <th>Total descuento</th>
                                        <th>Total Extras Diu_Ord</th>
                                        <th>Total Extras Noc_Ord</th>
                                        <th>Total Extras Diu_Fes</th>
                                        <th>Total Extras Noc_Fes</th>
                                        <th>Total Recargo Noc</th>
                                        <th>Total Recargo Fes_Diu</th>
                                        <th>Total Recargo Fes_Noc</th>
                                        <th>Total Recargo Ord_Fes_Noc</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- <pre><?= json_encode($_SESSION, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?></pre> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>