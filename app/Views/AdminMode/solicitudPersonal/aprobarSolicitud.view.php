<?php

use Config\USEFUL;

$USEFUL = new USEFUL;

$thead = [
    "N° Requisición",
    "Proceso",
    "Cargo",
    "Fecha Y Hora Registro",
    "Estado",
    "",
];

?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">solicitudPersonal</li>
                    <li class="breadcrumb-item active">aprobarSolicitud</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-1">
                    <div class="card-header">
                        <h3 class="card-title">Pendiente por aprobación</h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm">
                                <input type="search" class="form-control" placeholder="Buscar">
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="controls">
                            <div class="btn-group mx-1">
                                <button type="button" class="btn btn-success btn-sm" data-action="aprobar">
                                    <i class="fas fa-check"></i>
                                    <b> Aprobar</b>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-action="rechazar">
                                    <i class="fas fa-times"></i>
                                    <b> Rechazar</b>
                                </button>
                                <button type="button" class="btn btn-warning btn-sm" data-action="cancelar">
                                    <i class="fas fa-exclamation"></i>
                                    <b> Cerrar solicitud</b>
                                </button>
                            </div>
                            <div class="btn-group mx-1">
                                <button type="button" class="btn btn-outline-success btn-sm" data-action="seleccionar">
                                    <i class="fas fa-check-circle"></i>
                                    <b>Seleccionar todo</b>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-action="deseleccionar">
                                    <i class="fas fa-times-circle"></i>
                                    <b>Deseleccionar todo</b>
                                </button>
                            </div>
                            <div class="btn-group mx-1">
                                <button type="button" class="btn btn-default btn-sm" data-action="refresh">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" data-action="ssp_aprobar_solicitud">
                                <thead>
                                    <tr>
                                        <?= ($USEFUL->thead)($thead) ?>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>