<?php

use Model\RouteModel;

$routeM = new RouteModel;

$Tipo = $db->executeQuery("SELECT * FROM HorasExtras_Aprobador_Tipo");
$Gestiona = $db->executeQuery("SELECT * FROM HorasExtras_Aprobador_Gestiona");
$Administra = $db->executeQuery("SELECT * FROM HorasExtras_Aprobador_Administra");
$SolicitudPersonal = $db->executeQuery("SELECT * FROM HorasExtras_Aprobador_SolicitudPersonal");
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Aprobadores</h1>
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
                <h3 class="card-title">Agregar centro de costo</h3>
            </div>
            <div class="card-body">
                <form data-action="I_Aprobador">
                    <div class="row">
                        <div class="col-12 col-xl-4 mb-3">
                            <label for="nombre">Nombre del aprobador</label>
                            <input type="text" name="data[nombre]" id="nombre" class="form-control" required>
                        </div>
                        <div class="col-12 col-xl-4 mb-3">
                            <label for="mail">E-mail</label>
                            <input type="email" name="data[mail]" id="mail" class="form-control" required>
                        </div>
                        <div class="col-12 col-xl-4 mb-3">
                            <label for="tipo">Tipo</label>
                            <select name="data[id_tipo]" id="tipo" class="form-control" required>
                                <?php if (!$db::getError($Tipo)) foreach ($Tipo as $data) :
                                    echo <<<HTML
                                        <option value="{$data['id']}">{$data['nombre']}</option>
                                    HTML;
                                endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 col-xl-4 mb-3">
                            <label for="gestiona">Gestiona</label>
                            <select name="data[id_gestiona]" id="gestiona" class="form-control" required>
                                <?php if (!$db::getError($Gestiona)) foreach ($Gestiona as $data) :
                                    echo <<<HTML
                                        <option value="{$data['id']}">{$data['nombre']}</option>
                                    HTML;
                                endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 col-xl-4 mb-3">
                            <label for="Esadmin">Es administrador</label>
                            <select name="data[id_Esadmin]" id="Esadmin" class="form-control" required>
                                <?php if (!$db::getError($Administra)) foreach ($Administra as $data) :
                                    echo <<<HTML
                                        <option value="{$data['id']}">{$data['nombre']}</option>
                                    HTML;
                                endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 col-xl-4 mb-3">
                            <label for="solicitudPersonal">Aprueba solitud de personal</label>
                            <select name="data[id_solicitudPersonal]" id="solicitudPersonal" class="form-control" required>
                                <?php if (!$db::getError($SolicitudPersonal)) foreach ($SolicitudPersonal as $data) :
                                    echo <<<HTML
                                        <option value="{$data['id']}">{$data['nombre']}</option>
                                    HTML;
                                endforeach ?>
                            </select>
                        </div>
                        <div class="col-12 col-xl-12 mb-3">
                            <button class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table class="table" data-action="ssp_Aprobador">
                        <thead class="shadow">
                            <tr>
                                <th>Nombre</th>
                                <th>E-mail</th>
                                <th>Tipo</th>
                                <th>Gestiona</th>
                                <th>Admin</th>
                                <th>solicitud de personal</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tbody><!-- SERVERSIDE --></tbody>
                        <tbody>
                            <tr>
                                <th>Nombre</th>
                                <th>E-mail</th>
                                <th>Tipo</th>
                                <th>Gestiona</th>
                                <th>Admin</th>
                                <th>solicitud de personal</th>
                                <th>Editar</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>