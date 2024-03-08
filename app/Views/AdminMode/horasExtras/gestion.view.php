<?php

use Config\Bs4;
use Controller\GestionarHorasExtras;

$gestion = new GestionarHorasExtras;

$thead = [
    "Ver Detalle",
    "Documento",
    "Mes",
    "Colaborador",
    "Estado",
    "Clase",
    "Ceco",
    "Total descuento",
    "Total Extras Diu_Ord",
    "Total Extras Noc_Ord",
    "Total Extras Diu_Fes",
    "Total Extras Noc_Fes",
    "Total Recargo Noc",
    "Total Recargo Fes_Diu",
    "Total Recargo Fes_Noc",
    "Total Recargo Ord_Fes_Noc"
];

$showTH = fn (array $array): string => implode("\n", array_map(function ($str) use ($array) {
    $countThead = count($array);
    $width = 100 / $countThead;
    return "<th style=\"width: {$width}%\">{$str}</th>";
}, $array));


# Queria evitar hacer peticiones para obtener estos datos ¯\_(ツ)_/¯
$aprobadores = $gestion->getApprover();
$filtrar = fn ($column, $value) => array_values(array_filter($aprobadores, fn ($data) => $data[$column] == $value));

$jefe = $filtrar("id_tipo", 2);
$gerente = $filtrar("id_tipo", 3);
$rh = $filtrar("id_gestiona", 2);
$contable = $filtrar("id_gestiona", 3);

$encode = fn ($value) => json_encode($value, JSON_UNESCAPED_UNICODE);

?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Aprobar Reportes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">horasExtras</li>
                    <li class="breadcrumb-item active">gestion</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class=card-title>Reportes pendiente por aprobación</h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm">
                                <input type="search" class="form-control" placeholder="Buscar">
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="controls">
                            <div class="btn-group mx-1">
                                <button type="button" class="btn btn-success btn-sm" data-action="aprobar_horas">
                                    <i class="fas fa-check"></i>
                                    <b> Aprobar</b>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-action="rechazar_horas">
                                    <i class="fas fa-times"></i>
                                    <b> Rechazar</b>
                                </button>
                            </div>
                            <div class="btn-group mx-1">
                                <button type="button" class="btn btn-outline-success btn-sm" data-action="seleccionar_horas">
                                    <i class="fas fa-check-circle"></i>
                                    <b>Seleccionar todo</b>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-action="deseleccionar_horas">
                                    <i class="fas fa-times-circle"></i>
                                    <b>Deseleccionar todo</b>
                                </button>
                            </div>
                            <div class="btn-group mx-1">
                                <button type="button" class="btn btn-default btn-sm" data-action="refresh">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" data-action="sspGestion">
                                <thead>
                                    <tr>
                                        <?= $showTH($thead) ?>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // aprobadores
    const listJefe = <?= $encode($jefe) ?>;
    const listSelectJefe = {}
    listJefe.map(array => listSelectJefe[array.id] = array.nombre)

    const listGerente = <?= $encode($gerente) ?>;
    const listSelectGerente = {}
    listGerente.map(array => listSelectGerente[array.id] = array.nombre)

    const listRH = <?= $encode($rh) ?>;
    const listSelectRH = {}
    listRH.map(array => listSelectRH[array.id] = array.nombre)

    const listContable = <?= $encode($contable) ?>;
    const listSelectContable = {}
    listContable.map(array => listSelectContable[array.id] = array.nombre)
</script>

<?php

$modalTimeline = Bs4::Modal(
    id: "modal-timeline",
    header: <<<HTML
        <h4 class="modal-title">Línea de tiempo</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    HTML,
    body: <<<HTML
    ...
    HTML,
    footer: <<<HTML
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-default" data-action="print-modal"><i class="fa fa-print"></i></button>
    HTML
);

$modalReport = str_replace(
    ["Línea de tiempo", "modal-timeline",   "modal-dialog"],
    ["Reporte",         "modal-report",     "modal-dialog modal-xl"],
    $modalTimeline
);

echo $modalTimeline;
echo $modalReport;
