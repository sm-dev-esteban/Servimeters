<?php

use Model\RouteModel;

$routeM = new RouteModel;

$report = base64_decode($_GET["report"] ?? 0);
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Cargar hojas de vida</h1>
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
                <h3 class="card-title">(～﹃～)~zZ</h3>
            </div>
            <div class="card-body">
                <form id="formAdjuntos">
                    <div class="mb-3">
                        <label for="">Adjuntar Hojas de vida</label>
                        <input type="file" name="file[hojasDeVida]" id="hojasDeVida" class="form-control" multiple>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" name="data[id_requisicion]" value="<?= $report ?>">
                        <input type="hidden" name="data[estado]" value="1"> <?php /* estado pendiente */ ?>
                        <button class="btn btn-success" <?= !$report ? "disabled" : "" ?>>Enviar</button>
                    </div>
                </form>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
</section>