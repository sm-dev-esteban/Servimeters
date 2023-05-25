<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Permiso</h3>
            </div>
            <div class="card-body">
                <form id="solicitud">
                    <div class="row">
                        <div class="col-12 col-xl-6 mb-3">
                            <label for>funcionario</label>
                            <select name="data[id_funcionario]" id="id_funcionario" class="form-control select2">
                                <option value="">###</option>
                                <option value="">###</option>
                                <option value="">###</option>
                            </select>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label for="tipo_permiso">Tipo de permiso</label>
                            <select name="data[tipo_permiso]" id="tipo_permiso" class="form-control select2"></select>
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label for="fecha_inicio">Fecha inicio</label>
                            <input type="date" name="data[fecha_inicio]" id="fecha_inicio" class="form-control">
                        </div>
                        <div class="col-12 col-xl-6 mb-3">
                            <label for="fecha_fin">Fecha Fin</label>
                            <input type="date" name="data[fecha_fin]" id="fecha_fin" class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="observaciones">Observaciones</label>
                            <textarea name="data[observaciones]" id="observaciones" class="form-control"></textarea>
                        </div>
                        <div class="col-12 col-xl-2 mb-3">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" name="data[check_reposicion]" id="check_reposicion" value="<?= true ?>">
                                    <label for="check_reposicion">
                                        Reposición
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-10 mb-3">
                            <!-- <label for="fecha_reposicion">Fecha reposición</label> -->
                            <input type="date" name="data[fecha_reposicion]" id="fecha_reposicion" class="form-control">
                        </div>
                        <div class="col-12 col-xl-12 mb-3">
                            <label for="jefe_aprueba">Jefe que aprueba</label>
                            <select name="data[jefe_aprueba]" id="jefe_aprueba" class="form-control select2">
                                <option value="">###</option>
                                <option value="">###</option>
                                <option value="">###</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <input type="hidden" name="data[fechaRegistro]">
                            <input type="hidden" name="data[timezone]">
                            <button class="btn btn-default">Guardar Solicitud</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>