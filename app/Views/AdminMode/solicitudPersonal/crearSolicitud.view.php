<?php

# Includes your controller

$saltoDeLinea = fn (string $str): string => htmlspecialchars(implode("<br>", explode("\n", $str)));

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
                                <label for="id_proceso"><sup class="text-danger mr-1">*</sup>Proceso</label>
                                <select name="data[id_proceso]" id="id_proceso" class="form-control" style="width: 100%;" required></select>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="nombre_cargo"><sup class="text-danger mr-1">*</sup>Nombre del cargo</label>
                                <input type="text" name="data[nombre_cargo]" id="nombre_cargo" class="form-control" required>
                            </div>
                            <div class="col-12 col-xl-12 mb-3">
                                <label for="ciudad"><sup class="text-danger mr-1">*</sup>Ciudad</label>
                                <input type="text" name="data[ciudad]" id="ciudad" class="form-control" required>
                            </div>
                            <div class="col-12 col-xl-12 mb-3">
                                <label for="descripcionActividades"><sup class="text-danger mr-1">*</sup>Descripción de actividades principales</label>
                                <textarea name="data[descripcionActividades]" id="descripcionActividades" class="form-control" required></textarea>
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>
                            <div class="col-12 col-xl-1 mb-3">
                                <label for="codigo"><sup class="text-danger mr-1">*</sup>Código</label>
                                <input type="text" name="data[codigo]" id="codigo" class="form-control" required>
                            </div>
                            <div class="col-12 col-xl-3 mb-3">
                                <label for="id_tipo_contrato"><sup class="text-danger mr-1">*</sup>Tipo de contrato</label>
                                <select name="data[id_tipo_contrato]" id="id_tipo_contrato" class="form-control" style="width: 100%;" required></select>
                            </div>
                            <div class="col-12 col-xl-2 mb-3">
                                <label for="id_horario"><sup class="text-danger mr-1">*</sup>Horario</label>
                                <select name="data[id_horario]" id="id_horario" class="form-control" style="width: 100%;" required></select>
                            </div>
                            <div class="col-12 col-xl-3 mb-3">
                                <label for="sueldo" class="form-label"><sup class="text-danger mr-1">*</sup>Sueldo</label>
                                <input type="number" class="form-control" name="data[sueldo]" id="sueldo" aria-describedby="salaryHelp" min="0" required>
                                <span id="salaryHelp" class="form-text small"></span>
                            </div>
                            <div class="col-12 col-xl-3 mb-3">
                                <label for="auxilio_extralegal"><sup class="text-danger mr-1">*</sup>Auxilio extralegal</label>
                                <input type="number" class="form-control" name="data[auxilio_extralegal]" id="auxilio_extralegal" aria-describedby="salaryExtraHelp" min="0" required>
                                <span id="salaryExtraHelp" class="form-text small"></span>
                            </div>
                            <div class="col-12 mb-3" style="display: none">
                                <label for="meses"><sup class="text-danger mr-1">*</sup>Meses</label>
                                <select name="data[meses]" id="meses" class="form-control" required>
                                    <option value="">Seleccione</option>
                                    <option value="1">1 Mes</option>
                                    <option value="2">2 Meses</option>
                                    <option value="3">3 Meses</option>
                                    <option value="4">6 Meses</option>
                                </select>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="id_motivo_requisicion"><sup class="text-danger mr-1">*</sup>Motivo de requisición</label>
                                <select name="data[id_motivo_requisicion]" id="id_motivo_requisicion" class="form-control" style="width: 100%;" required></select>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="fecha_contratar"><sup class="text-danger mr-1">*</sup>Fecha a contratar</label>
                                <input type="date" name="data[fecha_contratar]" id="fecha_contratar" class="form-control" required>
                            </div>
                            <div class="col-12 mb-3" style="display: none">
                                <label for="otroMotivoRequisicion">Cual</label>
                                <input type="text" name="data[otroMotivoRequisicion]" id="otroMotivoRequisicion" class="form-control" required="required">
                            </div>
                            <div class="col-12 mb-3" style="display: none">
                                <label for="reemplazaA"><sup class="text-danger mr-1">*</sup>Reemplaza a</label>
                                <input type="text" name="data[reemplazaA]" id="reemplazaA" class="form-control" required>
                            </div>
                            <div class="col-12 mb-3" style="display: none">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="resources" placeholder="Recursos necesarios para el cargo" data-toggle="popover" data-trigger="hover" data-content="<?= $saltoDeLinea(<<<HTML
                                    <b>Nota</b>
                                    * Hacer clic sobre el icono de "+" para agregar los recursos que requieran.
                                    * Para borrarlo, haga clic sobre el ítem que quiera borrar.
                                    * Para agregar datos simultáneamente, separar por ";" ejemplo: "item1; item2"
                                    <hr>
                                    <b>Subnota</b>
                                    Si necesitaban el autocompletado, regañen al desarrollador por no ponerlo.

                                    <b>Atte:</b> El desarrollador.
                                    HTML) ?>">
                                    <div class="input-group-append">
                                        <button type="button" class="input-group-text" id="btn-add-resources"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <ul id="list-resources"></ul>
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="radicado_por">Radicado por</label>
                                <input type="text" name="data[radicado_por]" id="radicado_por" class="form-control" value="<?= $_SESSION["usuario"] ?>" readonly>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="id_aprobador"><sup class="text-danger mr-1">*</sup>Jefe que aprobara</label>
                                <select name="data[id_aprobador]" id="id_aprobador" class="form-control" required style="width: 100%;"></select>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="solicitado_por"><sup class="text-danger mr-1">*</sup>Solicitado por</label>
                                <input type="text" name="data[solicitado_por]" id="solicitado_por" class="form-control" required>
                            </div>
                            <div class="col-12 col-xl-6 mb-3">
                                <label for="email_solicitado_por"><sup class="text-danger mr-1">*</sup>E-mail solicitado por</label>
                                <input type="text" name="data[email_solicitado_por]" id="email_solicitado_por" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <input type="hidden" name="data[estado]" value="1">
                        <button class="btn btn-default btn-block">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>