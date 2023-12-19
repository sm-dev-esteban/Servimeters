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

          <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="bg-blue">
                    <tr>
                        <th>Nombre</th>
                        <th>Detalles</th>
                        <th>Editar</th>
                        <th>Eliminar</th>

                    </tr>
                </thead>
                <tbody >
                    <tr>
                        <td>Vacaciones</td>
                        <td>Días libres solicitados</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-edit"></i></button>
                        </td>
                        <td>
                          <button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>

                    <tr>
                        <td>Cumpleaños</td>
                        <td>Días libres por cumpleaños</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-edit"></i></button>
                        </td>
                        <td>
                          <button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


        </div>
      </div>
    </div>
  </div>
</section>
