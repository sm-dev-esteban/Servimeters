<?php

use Model\RouteModel;

$routeM = new RouteModel;
?>

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Mostrar solicitud</h1>
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
            <!-- Agrega DataTables a la tabla existente -->
            <table data-action="ssp_Solicitud" class="table table-hover table-striped">
              <thead class="bg-blue">
                <tr>
                  <th>id</th>
                  <th>funcionario</th>
                  <th>fecha registro</th>
                  <th>tipo de permiso</th>
                  <th>fecha Inicio - Fin</th>
                  <th>Horas</th>
                  <th>observacion</th>
                  <th>reposicion</th>
                  <th>fecha reposicion</th>
                  <th>aprovador</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th><input type="text" class="form-control" placeholder="id"></th>
                  <th><input type="text" class="form-control" placeholder="funcionario"></th>
                  <th><input type="text" class="form-control" placeholder="fechaRegistro"></th>
                  <th><input type="text" class="form-control" placeholder="tipoPermiso"></th>
                  <th><input type="text" class="form-control" placeholder="fechaInicioFin"></th>
                  <th><input type="text" class="form-control" placeholder="Nhoras"></th>
                  <th><input type="text" class="form-control" placeholder="observacion"></th>
                  <th><input type="text" class="form-control" placeholder="reposicion"></th>
                  <th><input type="text" class="form-control" placeholder="fechaReposicion"></th>
                  <th><input type="text" class="form-control" placeholder="aprovador"></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
