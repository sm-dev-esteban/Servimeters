<?php

# Includes your controller

?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">horasExtras</li>
                    <li class="breadcrumb-item active">reporte</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <form>
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="row">
                        <div class="col-2">
                            <div class="form-group">
                                <label>ID</label>
                                <input type="number" name="data[id]" id="id" class="form-control" placeholder="N° Reporte">
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Fecha Inicio:</label>
                                <input type="month" name="data[fechaInicio]" id="fechaInicio" class="form-control">
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Fecha Fin:</label>
                                <input type="month" name="data[fechaFin]" id="fechaFin" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-lg">
                            <input type="search" class="form-control form-control-lg" placeholder="Nombre del empleado">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-lg btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-default">
                            <i class="fa fa-file-excel"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-default" id="descargarPRN">
                            <b>PRN</b>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="row mt-3">
            <div class="col-md-10 offset-md-1">
                <div class="table-reponsive">
                    <table class="table" id="table-result">
                        <thead>
                            <tr>
                                <th>mucho texto</th>
                                <th>mucho texto</th>
                                <th>mucho texto</th>
                                <th>mucho texto</th>
                                <th>mucho texto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < rand(0, 100); $i++) :
                                print <<<HTML
                                <tr>
                                    <td>mucho texto</td>
                                    <td>mucho texto</td>
                                    <td>mucho texto</td>
                                    <td>mucho texto</td>
                                    <td>mucho texto</td>
                                </tr>
                                HTML;
                            endfor ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>