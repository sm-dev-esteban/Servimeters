<?php

use Config\USEFUL;
use Controller\CentroCosto;

$centroCosto = new CentroCosto;
$useful = new USEFUL;

$thead = [
    "Centro de costo",
    "Clase",
    "",
];

$showTH = fn(array $array): string => implode("\n", array_map(function ($str) use ($array) {
    $countThead = count($array);
    $width = 100 / $countThead;
    return "<th style=\"width: {$width}%\">{$str}</th>";
}, $array));

?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Centro de costo</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Administrar</li>
                    <li class="breadcrumb-item active">centroCosto</li>
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
                        <h3 class="card-title">Centro de costo</h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm">
                                <input type="search" class="form-control" placeholder="Buscar">
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="controls">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal"
                                data-target="#modal-ceco">
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
                            <table class="table" data-action="ssp_Ceco">
                                <thead>
                                    <tr>
                                        <?= $showTH($thead) ?>
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

<div class="modal fade" id="modal-ceco">
    <div class="modal-dialog">
        <form class="modal-content" data-action="I_Ceco">
            <div class="modal-header">
                <h4 class="modal-title">Centro de costo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="data[nombre]" id="nombre" class="form-control" required
                        placeholder="Nombre de la clase">
                </div>
                <div class="mb-3">
                    <label for="id_clase">Clase</label>
                    <select name="data[id_clase]" id="id_clase" class="form-control" required>
                        <option value="">Seleccione</option>
                        <?= ($useful->options)($centroCosto->getClass(), "id", "nombre") ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</div>