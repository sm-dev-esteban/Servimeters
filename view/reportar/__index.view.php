<?php
// fracase en el intento esto es muy complicado para cambiar las clases

// session_start();
if (!isset($_SESSION["estadoAutentica"])) {
    require_once "../../config/LoadConfig.config.php";
    $config = LoadConfig::getConfig();
    header('Location:' . $config['URL_SITE'] . 'index.php');
}
?>
<!-- Form Reportar -->
<section id="four" class="content">
    <div class="container-fluid">
        <div class="card" id="one">
            <div class="card-header">
                <h3 class="card-title">Reporte Horas Extra</h3>
            </div>
            <div class="card-body">
                <form action="" id="formReporte">
                    <div class="row" style="font-size: 0.8em !important;">
                        <section class="col-2 col-md-3 col-sm-12">
                            <h4 title="Sin puntuación">Cedula <b title="Sin puntuación" style="color: black;">❗</b><span class="text-danger">*</span></h4>
                            <input title="Sin puntuación" type="text" name="cc" id="cc" class="mainValue form-control" value="" placeholder="**********" data-empleado="<?= $_SESSION['usuario'] ?>" data-correoEmpleado="<?= $_SESSION['email'] ?>" pattern="[0-9]{1,10}" required /><!-- title="Solo numeros. No debe exceder los 10 digitos." -->
                        </section>
                        <section class="col-2 col-md-3 col-sm-12">
                            <h4>Cargo<span class="text-danger">*</span></h4>
                            <input type="text" name="cargo" id="cargo" class="mainValue form-control" required /><!-- title="Solo numeros. No debe exceder los 10 digitos." -->
                        </section>

                        <section class="col-2 col-md-3 col-sm-12">
                            <h4>Mes Reportado<span class="text-danger">*</span></h4>
                            <input type="month" name="mes" id="mes" class="mainValue form-control" value="" required />
                        </section>
                        <section class="col-3 col-md-3 col-sm-10">
                            <h4>Correo <span class="text-danger">*</span></h4>
                            <input type="email" name="correoEmpleado" id="correoEmpleado" class="mainValue form-control" value="<?= $_SESSION['email'] ?>" required />
                        </section>

                        <section class="col-3 col-md-6 col-sm-12">
                            <h4>Centro de Costo</h4>
                            <select name="ceco" id="ceco" class="form-control">
                                <!--Llenar datos con BD-->
                            </select>
                        </section>

                        <section class="col-3 col-md-6 col-sm-12">
                            <h4>Proyecto Asociado</h4>
                            <input type="text" name="proyecto" id="proyecto" class="form-control" required />
                        </section>

                        <section class="col-12 my-5">
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="#two" class="goTo"><span class="fas fa-chevron-down fit"></span></a>
                            </div>
                        </section>

                        <section class="col-12" id="heReportadas">
                            <header>
                                <h3>Horas Extra (HE) Reportadas</h3>
                            </header>
                            <table id="tableEdit" class="table">
                                <thead>
                                    <tr id="headTableEdit">
                                        <th>Fecha</th>
                                        <th>Actividad</th>
                                        <th>Permisos Descuentos</th>
                                    </tr>
                                    <!--Llenar encabezado con script-->
                                </thead>
                                <tbody id="bodyTableEdit">

                                </tbody>
                            </table>
                            <br>
                            <hr />
                            <button type="submit" id="allowAddRows" class="btn btn-primary fas fa-toggle-off fi">Agregar Horas Extra</button>
                        </section>

                        <!--<section class="col-12">
                            <header>
                                <h3>Información de Horas Extra (HE) <span class="fas fa-exclamation-triangle help" style="color: #e44c65 !important; padding: 3px;"></span></h3>
                            </header>
                        </section>-->

                        <section id="tableHE" class="col-12 sectionDisabled">
                            <div class="table-responsive" id="two">
                                <table id="table" class="table">
                                    <thead>
                                        <tr id="encTableHE">
                                            <th>Fecha <span class="text-danger">*</span></th>
                                            <th style="width: 180px;">Actividad <span class="text-danger">*</span></th>
                                            <th>Permisos Descuentos</th>
                                        </tr>
                                        <!--Llenar datos con DB-->
                                    </thead>
                                    <tbody id="bodyTableHE">
                                        <!--Llenar datos con DB-->
                                        <tr id="rowTableHE">
                                            <td style="width: 150px;"><input type="date" class="form-control fechasActividades" name="fechaActividad" id="fechaActividad" value="" required /></td>
                                            <td style="width: 150px;"><input type="text" class="form-control novedades" name="novedad" id="novedad" placeholder="Ingrese la novedad" style="font-size: 12px;" required></td>
                                            <td style="width: 70px;"><input type="text" class="form-control values descuentos" name="descuentos" value="0" required pattern="^[0-9]{1,2}?(.[5]{0,1})?$" title="Solo numeros, para decimales debe terminar en .5" /></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td style="text-align: right;" id="botonAgregar"><span class="fas fa-plus-square fi" title="Agregar fila" data-rows="0" name="agregarhe" id="agregarhe" style="font-size: 30px; color: #5480f1;"></span></td>
                                            <!-- <span style="color: tomato;" data-id="${id}" class="deleteRow fas fa-window-close fi" onclick="deleteRow(event, this, false)"></span> -->
                                        <tr>
                                        <tr>
                                            <td style="height: 50px;"></td>
                                        </tr>
                                        <tr id="summaries">
                                            <td colspan="2">Totales:</td>
                                            <td id="calcDescuentos" class="summariesFields">0</td>
                                        </tr>
                                        <tr>
                                            <td class="tituloTotal" align="right">Total Horas Extra</td>
                                            <td><span style="font-weight: bold; color: greenyellow;" id="calcHE">0</span></td>
                                        <tr>
                                            <td class="tituloTotal" align="right">Total Recargos</td>
                                            <td><span style="font-weight: bold;" id="calcRec">0</span></td>
                                        </tr>
                                        <!-- <tr>
                                            <td class="tituloTotal" align="right">Total Descuentos</td>
                                            <td><span style="font-weight: bold;" id="calcDescuentos">0</span></td>
                                        </tr> -->
                                        <tr>
                                            <td class="tituloTotal" align="right">Total</td>
                                            <td><span style="font-weight: bold;" id="total">0.0</span></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </section>
                    </div>
                    <a href="#three" class="goTo"><span class="fas fa-chevron-down fit"></span></a>
            </div>
        </div>
    </div>
</section>
<!-- Accion Reportar -->
<section id="reportar" class="wrapper style2 special fade">
    <div class="container" id="three">
        <header>
            <h2 style="color: white;">Reportar</h2>
            <p>Seleccione un tipo de aprobador.</p>
        </header>

        <div class="row">
            <section class="col-6 col-md-8 col-sm-12">
                <input type="radio" id="jefe" name="aprobador" required>
                <label for="jefe">Jefe</label>

                <div class="col-12">
                    <select name="listJefe" id="listJefe" disabled>
                        <!--Llenar datos con DB-->
                    </select>
                </div>
            </section>
            <section class="col-6 col-md-8 col-sm-12">
                <input type="radio" id="gerente" name="aprobador" required>
                <label for="gerente">Gerente</label>

                <div class="col-12">
                    <select name="listGerente" id="listGerente" disabled>
                        <!--Llenar datos con DB-->
                    </select>
                </div>

            </section>
            <section class="col-12 col-md-8 col-sm-12">
                <div class="col-12" id="errorRadio" style="display: none;">
                    <p>✗ Por favor seleccione un aprobador.</p>
                </div>
            </section>
            <section class="col-12 col-md-8 col-sm-12" id="butonSend">
                <footer class="major">
                    <ul class="actions special">
                        <li><button type="submit" id="sendData" data-type="create" class="btn btn-primary fas fa-check-circle fi">Enviar</button></li>
                    </ul>
                    <span>❗ Si no selecciona un aprobador, el registro quedara en modo de edición y podrá modificarlo desde el módulo "Mis Horas Extra".</span>
                </footer>
                <br>
                <a href="#one" class="goTo"><span class="fas fa-chevron-up fit"></span></a>
            </section>
            <section class="col-12 col-md-8 col-sm-12" id="loadSpinner">
                <div class="load-wrapp">
                    <div class="load-3">
                        <h4 style="color: white;">Enviando Datos...</h4>
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                    </div>
                </div>
            </section>
            <div id="resultTest">

            </div>
        </div>
        </form>
    </div>
</section>