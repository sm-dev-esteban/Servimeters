<?php

use Model\RouteModel;

$routeM = new RouteModel;
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Mis horas extras</h1>
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
                <h3 class="card-title">Horas reportadas</h3>

            </div>
            <div class="card-body table-responsive p-0">
                <table class="table" data-action="ssp_horas">
                    <thead class="shadow">
                        <tr>
                            <th>#</th>
                            <th>Documentos</th>
                            <th>Centro costo</th>
                            <th>Clase</th>
                            <th>Año</th>
                            <th>Mes</th>
                            <th>Aprobador</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tfoot class="shadow">
                        <tr>
                            <th>#</th>
                            <th>Documentos</th>
                            <th>Centro costo</th>
                            <th>Clase</th>
                            <th>Año</th>
                            <th>Mes</th>
                            <th>Aprobador</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>