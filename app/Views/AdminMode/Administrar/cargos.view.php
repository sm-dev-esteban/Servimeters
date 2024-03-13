<?php

use Config\USEFUL;

$USEFUL = new USEFUL;

$thead = [
    "Cargo",
    ""
];

?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Cargos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Administrar</li>
                    <li class="breadcrumb-item active">cargos</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class=card-title>Listado de cargos</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="controls">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal-cargos">
                                <i class="fas fa-plus"></i>
                                Agregar
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm" data-action="refresh">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" data-action="ssp_Cargos">
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

<div class="modal fade" id="modal-cargos">
    <div class="modal-dialog">
        <form class="modal-content" data-action="I_Cargos">
            <div class="modal-header">
                <h4 class="modal-title">Cargos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="data[nombre]" id="nombre" class="form-control" required placeholder="Nombre del cargo">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</div>