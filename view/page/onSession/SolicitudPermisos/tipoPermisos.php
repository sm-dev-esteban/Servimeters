<?php

use Model\RouteModel;

$routeM = new RouteModel;

?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tipo de Permisos</h1>
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
            <div class="col-sm-12">
                <div class="card card-primary card-outline mt-1">
                    <div class="card-header">
                        <h3 class="card-title">Solicitudes</h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" placeholder="Buscar">
                            </div>
                        </div>
                    </div>

                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover table-striped" data-action="ssp_permiso">
                            <thead class="bg-blue">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Detalles</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Vacaciones</td>
                                    <td>Días libres solicitados</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Cumpleaños</td>
                                    <td>Días libres por cumpleaños</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-permiso">
                            <i class="fas fa-plus"></i> Agregar Permiso
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-permiso">
    <div class="modal-dialog">
        <form class="modal-content" data-action="I_Permiso">
            <div class="modal-header">
                <h4 class="modal-title">Agregar tipo de permiso</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nombre</label>
                    <input type="text" name="data[nombre]" class="form-control" placeholder="Nombre">
                </div>
                <div class="mb-3">
                    <label>Detalles</label>
                    <textarea name="data[detalle]" class="form-control" placeholder="Detalles"></textarea>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Agregar</button>
            </div>
        </form>
    </div>
</div>