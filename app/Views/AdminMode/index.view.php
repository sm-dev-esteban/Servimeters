<?php

use Config\CRUD;

$crud = new CRUD;

function orderedMonths(bool $onlyMonths = false): array
{
    $changeYear = fn (array $array, int $year): array => array_map(function ($arr) use ($year) {
        $arr["year"] = $year;
        return $arr;
    }, $array);

    $n = 0;

    $arrayMonths = [
        ["month" => "Enero",        "number" => ++$n, "year" => "?"],
        ["month" => "Febrero",      "number" => ++$n, "year" => "?"],
        ["month" => "Marzo",        "number" => ++$n, "year" => "?"],
        ["month" => "Abril",        "number" => ++$n, "year" => "?"],
        ["month" => "Mayo",         "number" => ++$n, "year" => "?"],
        ["month" => "Junio",        "number" => ++$n, "year" => "?"],
        ["month" => "Julio",        "number" => ++$n, "year" => "?"],
        ["month" => "Agosto",       "number" => ++$n, "year" => "?"],
        ["month" => "Septiembre",   "number" => ++$n, "year" => "?"],
        ["month" => "Octubre",      "number" => ++$n, "year" => "?"],
        ["month" => "Noviembre",    "number" => ++$n, "year" => "?"],
        ["month" => "Diciembre",    "number" => ++$n, "year" => "?"]
    ];

    $currentMonth = date("n");

    $currentYear = date("Y");
    $lastYear = $currentYear - 1;

    $monthsCurrentYear = $changeYear(array_slice($arrayMonths, 0, $currentMonth), $currentYear);
    $monthsLastYear = $changeYear(array_slice($arrayMonths, $currentMonth), $lastYear);

    $orderedMonths = [...$monthsLastYear, ...$monthsCurrentYear];

    $months = array_map(fn ($arr): string => $arr["month"], $orderedMonths);

    return !$onlyMonths ? $orderedMonths : $months;
}

$months = orderedMonths(true);
$dataMonths = orderedMonths(false);

?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-1"></i>
                            Grafica
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="w-100 h-100 d-flex justify-content-center align-items-center">
                            <canvas id="line-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card mt-1">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ion ion-person mr-1"></i>
                            Aprobadores
                        </h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm">
                                <input type="search" class="form-control" placeholder="Buscar">
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-reponsive">
                        <table class="table" id="table-users">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Aprueba Solicitudes de personal</th>
                                    <th>Aprueba Solicitudes de permisos</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <?php if ($_SESSION["usuario"] === "Esteban Serna Palacios") : ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user mr-1"></i>
                                Session
                            </h3>
                        </div>
                        <div class="card-body">
                            <pre><?= json_encode($_SESSION, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?></pre>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</section>

<script>
    const months = <?= json_encode($months) ?>

    const rand = (i = 10) => Math.floor(Math.random() * i)

    const data = {
        labels: months,
        datasets: [{
            label: 'Aprobado',
            borderColor: 'green',
            data: [rand(), rand(), rand(), rand(), rand(), rand(), rand(), rand(), rand(), rand(), rand(), rand()],
            fill: false,
        }, {
            label: 'Pendiente',
            borderColor: 'red',
            data: [rand(), rand(), rand(), rand(), rand(), rand(), rand(), rand(), rand(), rand(), rand(), rand()],
            fill: false,
        }]
    }
</script>