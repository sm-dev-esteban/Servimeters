<?php

# Includes your controller

?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Crear Solicitud</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">solicitudPersonal</li>
                    <li class="breadcrumb-item active">crearSolicitud</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form class="card" data-action="agregarSolicitud">
                    <div class="card-header">
                        <h3 class=card-title>Requisición de personal</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="id_proceso">Proceso</label>
                                <select name="data[id_proceso]" id="id_proceso" class="form-control" style="width: 100%;"></select>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="nombre_cargo">Nombre del cargo</label>
                                <input type="text" name="data[nombre_cargo]" id="nombre_cargo" class="form-control">
                            </div>
                            <div class="col-12 col-xl-12 mb-3">
                                <label for="ciudad">Ciudad</label>
                                <input type="text" name="data[ciudad]" id="ciudad" class="form-control">
                            </div>
                            <div class="col-12 col-xl-12 mb-3">
                                <label for="descripcionActividades">Descripción de actividades principales</label>
                                <textarea name="data[descripcionActividades]" id="descripcionActividades" class="form-control"></textarea>
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>
                            <div class="col-12 col-xl-1 mb-3">
                                <label for="codigo">Código</label>
                                <input type="text" name="data[codigo]" id="codigo" class="form-control">
                            </div>
                            <div class="col-12 col-xl-3 mb-3">
                                <label for="id_tipo_contrato">Tipo de contrato</label>
                                <select name="data[id_tipo_contrato]" id="id_tipo_contrato" class="form-control" style="width: 100%;"></select>
                            </div>
                            <div class="col-12 col-xl-2 mb-3">
                                <label for="id_horario">Horario</label>
                                <select name="data[id_horario]" id="id_horario" class="form-control" style="width: 100%;"></select>
                            </div>
                            <div class="col-12 col-xl-3 mb-3">
                                <label for="sueldo">Sueldo</label>
                                <input type="text" name="data[sueldo]" id="sueldo" class="form-control">
                            </div>
                            <div class="col-12 col-xl-3 mb-3">
                                <label for="auxilio_extralegal">Auxilio extralegal</label>
                                <input type="text" name="data[auxilio_extralegal]" id="auxilio_extralegal" class="form-control">
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="id_motivo_requisicion">Motivo de requisición</label>
                                <select name="data[id_motivo_requisicion]" id="id_motivo_requisicion" class="form-control" style="width: 100%;"></select>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="fecha_contratar">Fecha a contratar</label>
                                <input type="date" name="data[fecha_contratar]" id="fecha_contratar" class="form-control">
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="radicado_por">Radicado por</label>
                                <input type="text" name="data[radicado_por]" id="radicado_por" class="form-control" value="<?= $_SESSION["usuario"] ?>" readonly>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="id_aprobador">Jefe que aprobara</label>
                                <select name="data[id_aprobador]" id="id_aprobador" class="form-control"></select>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="solicitado_por">Solicitado por</label>
                                <input type="text" name="data[solicitado_por]" id="solicitado_por" class="form-control">
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="email_solicitado_por">E-mail solicitado por</label>
                                <input type="text" name="data[email_solicitado_por]" id="email_solicitado_por" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-default btn-block">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>