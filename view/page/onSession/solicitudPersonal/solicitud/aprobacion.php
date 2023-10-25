<?php

use Model\RouteModel;

$routeM = new RouteModel;
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Solicitudes</h1>
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
                <h3 class="card-title">Aprobar solicitudes</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table" data-action="ssp_aprobacion">
                    <thead>
                        <tr>
                            <th>N° Requisición</th>
                            <th>Proceso</th>
                            <th>Cargo</th>
                            <th>Estado</th>
                            <th>Ver solicitud</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
</section>