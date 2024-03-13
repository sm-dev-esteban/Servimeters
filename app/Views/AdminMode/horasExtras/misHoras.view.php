<?php

use Config\Bs4;
use Config\USEFUL;

$USEFUL = new USEFUL;

$thead = [
    "#",
    "Documento",
    "Centro Costo",
    "Clase",
    "Mes Reportado",
    "Aprobador",
    "Estado",
    ""
];

?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Mis Horas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">horasExtras</li>
                    <li class="breadcrumb-item active">misHoras</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-1">
                    <div class="card-header">
                        <h3 class=card-title>Mis Horas</h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm">
                                <input type="search" class="form-control" placeholder="Buscar">
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table" data-action="sspReport">
                                <thead>
                                    <tr>
                                        <?= ($USEFUL->thead)($thead) ?>
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
    HTML
);

$modalReport = str_replace(
    ["Línea de tiempo", "modal-timeline",   "modal-dialog"],
    ["Reporte",         "modal-report",     "modal-dialog modal-xl"],
    $modalTimeline
);

echo $modalTimeline;
echo $modalReport;
