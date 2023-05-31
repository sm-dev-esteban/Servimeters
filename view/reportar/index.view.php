<style>
    .center {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #btn-fixed {
        position: fixed;
        right: 0;
        bottom: 0;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Registro de horas extras</h3>
            </div>
            <div class="card-body">
                <form id="formReporte" data-mode="<?= isset($_GET["edit"]) ? "UPDATE" : "INSERT" ?>">
                    <div class="row">
                        <div class="col-12 col-xl-3">
                            <label for="cc">Cedula <b class="text-danger">*</b></label>
                            <input type="number" name="data[cc]" id="cc" class="form-control" maxlength="10" required>
                        </div>
                        <div class="col-12 col-xl-3">
                            <label for="cargo">Cargo <b class="text-danger">*</b></label>
                            <input type="text" name="data[cargo]" id="cargo" class="form-control" required>
                        </div>
                        <div class="col-12 col-xl-3">
                            <label for="mes">Mes Reportado <b class="text-danger">*</b></label>
                            <input type="month" name="data[mes]" id="mes" class="form-control" required oninput="fechas()">
                        </div>
                        <div class="col-12 col-xl-3">
                            <label for="correoEmpleado">Correo <b class="text-danger">*</b></label>
                            <input type="email" name="data[correoEmpleado]" id="correoEmpleado" class="form-control" required value="<?= $_SESSION['email'] ?>">
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="ceco">Centro de Costo </label>
                            <select name="data[id_ceco]" id="ceco" class="form-control select2" style="width: 100%"></select>
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="proyecto">Proyecto Asociado </label>
                            <input type="text" name="data[proyecto]" id="proyecto" class="form-control">
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                        <div class="col-12">
                            <label for="adjuntos" class="form-label">Adjuntar archivos</label>
                            <input type="file" name="file[adjuntos]" id="adjuntos" class="form-control" multiple>
                        </div>
                        <div class="col-12 my-3">
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="#one" style="font-size: 25px"><i class="fas fa-chevron-down"></i></a>
                            </div>
                        </div>
                        <div class="col-12" id="heReportadas">
                            <div class="table-responsive">
                                <table class="table" id="tableEdit">
                                    <thead>
                                        <tr class="shadow" id="headTableEdit">
                                            <th>Fecha <b class="text-danger">*</b></th>
                                            <th>Actividad <b class="text-danger">*</b></th>
                                            <th>Permisos Descuentos</th>
                                            <th>Extras Diurn Ordinaria</th> <?php #11001 
                                                                            ?>
                                            <th>Extras Noct Ordinaria</th> <?php #11002 
                                                                            ?>
                                            <th>Extras Diurn Fest Domin</th> <?php #11003 
                                                                                ?>
                                            <th>Extras Noct Fest Domin</th> <?php #11004 
                                                                            ?>
                                            <th>Recargo Nocturno</th> <?php #11501 
                                                                        ?>
                                            <th>Recargo Festivo Diurno</th> <?php #11502 
                                                                            ?>
                                            <th>Recargo Festivo Noctur</th> <?php #11503 
                                                                            ?>
                                            <th>Recargo Ord Fest Noct</th> <?php #11504 
                                                                            ?>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyTableEdit">
                                        <tr>
                                            <td><input type="date" name="HorasExtra[fecha][]" class="form-control" required></td>
                                            <td><input type="text" name="HorasExtra[novedad][]" class="form-control" required></td>
                                            <td><input type="number" name="HorasExtra[descuento][]" class="form-control" data-he="descuento" oninput="total()" step="0.5" min="0"></td>
                                            <td><input type="number" name="HorasExtra[E_Diurna_Ord][]" class="form-control" data-he="EDO" oninput="total()" step="0.5" min="0"></td>
                                            <td><input type="number" name="HorasExtra[E_Nocturno_Ord][]" class="form-control" data-he="ENO" oninput="total()" step="0.5" min="0"></td>
                                            <td><input type="number" name="HorasExtra[E_Diurna_Fest][]" class="form-control" data-he="EDF" oninput="total()" step="0.5" min="0"></td>
                                            <td><input type="number" name="HorasExtra[E_Nocturno_Fest][]" class="form-control" data-he="ENF" oninput="total()" step="0.5" min="0"></td>
                                            <td><input type="number" name="HorasExtra[R_Nocturno][]" class="form-control" data-he="RN" oninput="total()" step="0.5" min="0"></td>
                                            <td><input type="number" name="HorasExtra[R_Fest_Diurno][]" class="form-control" data-he="RFD" oninput="total()" step="0.5" min="0"></td>
                                            <td><input type="number" name="HorasExtra[R_Fest_Nocturno][]" class="form-control" data-he="RFN" oninput="total()" step="0.5" min="0"></td>
                                            <td><input type="number" name="HorasExtra[R_Ord_Fest_Noct][]" class="form-control" data-he="ROF" oninput="total()" step="0.5" min="0"></td>
                                            <td><input type="hidden" name="HorasExtra[id][]"><button class="btn btn-danger" type="button" disabled onclick="deleteT(this)"><i class="fa fa-times"></i></button></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="shadow">
                                            <td colspan="2" align="left"><button id="addHE" type="button" class="btn btn-primary"><i class="fas fa-plus fi"></i></button></td>
                                            <td data-info-he="descuento">0</td>
                                            <td data-info-he="EDO">0</td><input type="hidden" data-codigo="EDO" name="data[codigo][]">
                                            <td data-info-he="ENO">0</td><input type="hidden" data-codigo="ENO" name="data[codigo][]">
                                            <td data-info-he="EDF">0</td><input type="hidden" data-codigo="EDF" name="data[codigo][]">
                                            <td data-info-he="ENF">0</td><input type="hidden" data-codigo="ENF" name="data[codigo][]">
                                            <td data-info-he="RN">0</td><input type="hidden" data-codigo="RN" name="data[codigo][]">
                                            <td data-info-he="RFD">0</td><input type="hidden" data-codigo="RFD" name="data[codigo][]">
                                            <td data-info-he="RFN">0</td><input type="hidden" data-codigo="RFN" name="data[codigo][]">
                                            <td data-info-he="ROF">0</td><input type="hidden" data-codigo="ROF" name="data[codigo][]">
                                            <td align="right">
                                                <input type="hidden" name="totales">
                                                <b>Totales</b>
                                            </td>
                                            <td id="totales"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="11" align="right">
                                                <input type="hidden" name="data[totalPermisos]">
                                                Total Permisos descuentos
                                            </td>
                                            <td id="totalPermisos">0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="11" align="right">
                                                <input type="hidden" name="data[totalHorasExtras]">
                                                Total horas extras
                                            </td>
                                            <td id="totalHorasExtras">0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="11" align="right">
                                                <input type="hidden" name="data[totalRecargos]">
                                                Total recargos
                                            </td>
                                            <td id="totalRecargos">0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="11" align="right">
                                                <input type="hidden" name="data[total]">
                                                Total
                                            </td>
                                            <td id="total">0.0</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 my-3">
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="#one" style="font-size: 25px"><i class="fas fa-chevron-down"></i></a>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                        <div class="col-6">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="jefe" value="Jefe" name="aprobador">
                                    <label for="jefe">Jefe</label>
                                </div>
                                <select name="listJefe" id="listJefe" class="form-control mt-1" disabled>
                                    <option value="">###</option>
                                    <option value="">###</option>
                                    <option value="">###</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="radio" id="gerente" value="Gerente" name="aprobador">
                                    <label for="gerente">Gerente</label>
                                </div>
                                <select name="listGerente" id="listGerente" class="form-control mt-1" disabled>
                                    <option value="">###</option>
                                    <option value="">###</option>
                                    <option value="">###</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="data[id_estado]">
                        <input type="hidden" name="data[id_aprobador]">
                        <input type="hidden" name="data[empleado]" value="<?= $_SESSION['usuario'] ?>">
                        <input type="hidden" name="data[fechaInicio]">
                        <input type="hidden" name="data[fechaFin]">
                        <input type="hidden" name="action" value="<?= isset($_GET["edit"]) ? "UPDATE" : "INSERT" ?>">
                        <input type="hidden" name="edit" value="<?= isset($_GET["edit"]) ? $_GET["edit"] : "" ?>">
                        <input type="hidden" name="data[fechaRegistro]">
                        <input type="hidden" name="data[timezone]">
                        <div class="col-12">
                            <div class="center">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fa fa-check-circle"></i>
                                    Enviar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- comentarios -->
                <div id="btn-fixed" class="m-1">
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-comentarios">
                        Comentarios
                    </button>
                </div>
                <div class="modal fade" id="modal-comentarios" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Comentarios</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="timeline"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- comentarios -->
            </div>
        </div>
    </div>
</section>
<?php if (isset($_GET["edit"])) : ?>
    <script>
        sessionStorage.setItem("edit", <?= $_GET["edit"] ?>)
    </script>
<?php endif ?>