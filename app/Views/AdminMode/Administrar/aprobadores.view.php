<?php

use Config\USEFUL;
use Controller\Aprobador;

$useful = new USEFUL;
$aprovador = new Aprobador;

$thead = [
    "Nombre",
    "E-mail",
    "Tipo",
    "Gestiona",
    "Admin",
    "solicitud de personal",
    "solicitud de permiso",
    ""
];

$showTH = fn (array $array): string => implode("\n", array_map(function ($str) use ($array) {
    $countThead = count($array);
    $width = 100 / $countThead;
    return "<th style=\"width: {$width}%\">{$str}</th>";
}, $array));


// html
$saltoDeLinea = fn (string $str): string => htmlspecialchars(implode("<br>", explode("\n", $str)));

?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Aprovador</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Administrar</li>
                    <li class="breadcrumb-item active">Aprovador</li>
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
                        <h3 class="card-title">Aprovadores</h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm">
                                <input type="search" class="form-control" placeholder="Buscar">
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="controls">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#modal-aprovador">
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
                            <table class="table" data-action="ssp_Aprovador">
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

<div class="modal fade" id="modal-aprovador">
    <div class="modal-dialog">
        <form class="modal-content" data-action="I_Aprovador">
            <div class="modal-header">
                <h4 class="modal-title">Aprovador</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="data[nombre]" id="nombre" class="form-control" required placeholder="Nombre de la Aprovador">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="email">E-mail</label>
                        <input type="email" name="data[email]" id="email" class="form-control" required placeholder="Email de la Aprovador">
                    </div>
                    <div class="col-12 mb-3">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="data[admin]" id="admin" value="<?= true ?>" class="custom-control-input">
                                <label for="admin" class="custom-control-label">
                                    Admin<sup class="fa fa-info ml-1 text-info" data-toggle="popover" data-trigger="hover" title="Admin" data-content="<?= $saltoDeLinea(<<<HTML
                                        <b>Acceso al modulo de 'Administrar'</b>

                                        * Gestión Clases
                                        * Gestión Centros de costo
                                        * Gestión Cargos
                                        * Gestión Aprobadores
                                        HTML) ?>"></sup>
                                </label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="data[apruebaSolicitudPersonal]" id="apruebaSolicitudPersonal" value="<?= true ?>" class="custom-control-input">
                                <label for="apruebaSolicitudPersonal" class="custom-control-label">
                                    Aprueba Solicitud Personal<sup class="fa fa-info ml-1 text-info" data-toggle="popover" data-trigger="hover" title="Solicitud Personal" data-content="<?= $saltoDeLinea(<<<HTML
                                        Acceso al modulo de 'Administrar'

                                        * Gestión Clases
                                        * Gestión Centros de costo
                                        * Gestión Cargos
                                        * Gestión Aprobadores
                                        HTML) ?>"></sup>
                                </label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="data[apruebaSolicitudPermisos]" id="apruebaSolicitudPermisos" value="<?= true ?>" class="custom-control-input">
                                <label for="apruebaSolicitudPermisos" class="custom-control-label">
                                    Aprueba Solicitud Permisos<sup class="fa fa-info ml-1 text-info" data-toggle="popover" data-trigger="hover" title="Solicitud Permisos" data-content="<?= $saltoDeLinea(<<<HTML
                                        Acceso al modulo de Administrar

                                        * Gestión Clases
                                        * Gestión Centros de costo
                                        * Gestión Cargos
                                        * Gestión Aprobadores
                                        HTML) ?>"></sup>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="id_tipo">Tipo</label>
                        <select name="data[id_tipo]" id="id_tipo" class="form-control" required>
                            <?= ($useful->options)($aprovador->getApproverType(), "id", "nombre") ?>
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="id_gestiona">Gestiona</label>
                        <select name="data[id_gestiona]" id="id_gestiona" class="form-control" required>
                            <?= ($useful->options)($aprovador->getApproverManages(), "id", "nombre") ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</div>