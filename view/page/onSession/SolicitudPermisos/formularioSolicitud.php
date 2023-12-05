<?php

use Model\RouteModel;

$routeM = new RouteModel;
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Solicitar Permisos</h1>
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
                <h3 class="card-title">Formulario</h3>
            </div>
            <div class="card-body">
                <!--Formulario de solicitud de permisos-->
                <form>
                    <div class="row">
                        <!--Columna funcionario-->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Funcionario:</label>
                                <select class="form-control">
                                    <option>option 1</option>
                                    <option>option 2</option>
                                </select>
                            </div>
                        </div>

                        <!--Columna permisos-->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tipo de Permiso:</label>
                                <select class="form-control">
                                    <option>option 1</option>
                                    <option>option 2</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!--Columna fecha inicio y fin-->
                        <div class="col-sm-9">
                            <div class="form-group">
                                <label>Fecha inicio-fin:</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="reservation">
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- <div class="form-group">
                                <label>Fecha inicio-fin:</label>
                                <input type="date" class="form-control" id="reservation">
                            </div> -->
                        </div>

                        <!--Columna número de horas-->
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="exampleInputText">Número de Horas:</label>
                                <input type="number" class="form-control" id="exampleInputText">
                            </div>
                        </div>

                        <!--Columna observaciones-->
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Observaciones</label>
                                <textarea class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!--Columna reposición-->
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Reposición</label>
                                <select class="form-control">
                                    <option>Sí</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>

                        <!--Columna fecha de reposición-->
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Fecha de Reposición:</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>

                        <!--Columna jefe aprobación-->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Jefe que Aprueba:</label>
                                <select class="form-control">
                                    <option>jefe 1</option>
                                    <option>jefe 2</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button class="col-12 btn btn-outline-primary"><i class="fa fa-check-circle"></i>Enviar Solicitud</button>
                </form>
            </div>
        </div>
</section>