<?php

if (!isset($_SESSION["estadoAutentica"])) {
    require_once "../../config/LoadConfig.config.php";
    $config = LoadConfig::getConfig();
    header('Location:' . $config['URL_SITE'] . 'index.php');
}
?>

<section id="four" class="content">
    <div class="container summaryContainer">
        <header>
            <h3>Aprobar / Rechazar Horas Extra</h3>
            <div id="typeGestion" data-type="" data-rol=""></div>
        </header>

        <section class="col-12 col-md-4 col-sm-12" style="--left:0px">
            <div class="top">
                <ul class="actions special">
                    <li><button type="submit" id="allAprove" class="button fas fa-check-circle fit" style="background-color: #3c763d">Aprobar</button></li>
                    <li><button type="submit" id="allReject" class="button fas fa-trash-alt fit" style="background-color: tomato">Rechazar</button></li>
                </ul>
                <div class="col-4 col-sm-6" id="moduloGestionar" style="margin: auto; padding: 10px; width: 550px;">
                </div>
                <div class="col-4 col-sm-6" id="resultTest" style="margin: auto; padding: 10px; width: 550px;">
                </div>
                <hr>
            </div>
            <div class="row">
                <section class="col-4 col-md-6 col-sm-12 buttonRejectAprove">
                    <button id="selectAllRows" class="btn btn-primary fas fa-check-circle fit" style="background-color: #3c763d;">Seleccionar Todo</button>
                </section>
                <section class="col-4 col-md-6 col-sm-12 buttonRejectAprove">
                    <button id="deselectAllRows" class="btn btn-primary fas fa-check-circle fit">Deseleccionar Todo</button>
                </section>
            </div>
            <br>
            <div style="overflow: auto; max-width: 100%;">
                <table class="alt tableSummary cell-border display compact" id="dataTable" style="max-width:100%;">
                    <thead>
                        <tr id="encabezadoTable">
                            <th>Ver Mas</th>
                            <th>Num</th>
                            <th>Id</th>
                            <th># Documento</th>
                            <th>Año</th>
                            <th>Mes</th>
                            <th>Colaborador</th>
                            <th>Estado</th>
                            <th>Clase</th>
                            <th>CeCo</th>
                            <th>Descuento</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Llenar tabla -->
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</section>