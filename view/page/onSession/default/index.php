<?php

use Controller\CRUD;
use Controller\SeeHoursReport;
use Model\RouteModel;

$routeM = new RouteModel;
$rol = $_SESSION["rol"] ?? false;
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Home</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><?= "Dashboard" ?></li>
                </ol>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <?php $array = [
                [
                    "count" => "1000",
                    "title" => "####",
                    "icon" => "ion ion-person-add",
                    "color" => "primary",
                    "href" => "#"
                ],
                [
                    "count" => "1000",
                    "title" => "####",
                    "icon" => "ion ion-person-add",
                    "color" => "success",
                    "href" => "#"
                ],
                [
                    "count" => "1000",
                    "title" => "####",
                    "icon" => "ion ion-person-add",
                    "color" => "warning",
                    "href" => "#"
                ],
                [
                    "count" => "1000",
                    "title" => "####",
                    "icon" => "ion ion-person-add",
                    "color" => "info",
                    "href" => "#"
                ]
            ] ?>
            <?php foreach ($array as $show) : ?>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-<?= $show["color"] ?>">
                        <div class="inner">
                            <h3><?= $show["count"] ?></h3>
                            <p></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="<?= $show["href"] ?>" class="small-box-footer">More <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <div class="row">
            <!-- <div class="col-12">
                <?= CRUD::view("CentrosCosto", [
                    [
                        "db" => "titulo",
                        "tag" => [
                            "input" => [
                                "class" => "form-control",
                                "label" => [
                                    "innerText" => "mamaguevo",
                                ]
                            ]
                        ]
                    ], [
                        "db" => "id_Clase",
                        "tag" => [
                            "select" => [
                                "class" => "form-control",
                                "innerJoin" => [
                                    "table" => "Clase",
                                    "id" => "id",
                                    "value" => "titulo"
                                ],
                                "label" => [
                                    "innerText" => "Clase"
                                ]
                            ]
                        ]
                    ]
                ]) ?>
            </div> -->
            <!-- <div class="col-12 col-xl-12">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="fas fa-time"></i>
                            Demostración
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn bg-danger btn-sm" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="embed-responsive embed-responsive-16by9">
                            <video class="embed-responsive-item" src="../files/Demostración básica del sistema - Made with Clipchamp.mp4" controls></video>
                        </div>
                    </div>
                </div>
            </div> -->
            <?php /* --------------------------------------------------------------------- */ ?>
            <?php /* Gerente - Jefe */ ?>
            <?php if ($rol == "Gerente" || $rol == "Jefe") : ?>
                <div class="col-12 col-xl-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="fas fa-calendar mr-1"></i>
                                Reporte anual de horas
                            </h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52" aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>

                                    <div class="dropdown-menu" role="menu">
                                        <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">line</span>
                                        <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">bar</span>
                                        <!-- <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">horizontalBar</span> -->
                                        <!-- <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">radar</span> -->
                                        <!-- <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">pie</span> -->
                                        <!-- <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">doughnut</span> -->
                                        <!-- <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">polarArea</span> -->
                                        <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">bubble</span>
                                        <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">scatter</span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success btn-sm" data-chart-download="he_anual">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn bg-danger btn-sm" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas class="chart" data-chart-ident="he_anual" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="row" data-chart-knob="he_anual">
                                <div class="col-5 col-xl-4 text-center">
                                    <input type="text" class="knob" data-knob="he_anual" data-readonly="true" data-width="60" data-height="60" data-fgColor="#28a745">
                                    <div data-chart-knob-title>% Horas Aprobadas</div>
                                </div>
                                <div class="col-5 col-xl-4 text-center">
                                    <input type="text" class="knob" data-knob="he_anual" data-readonly="true" data-width="60" data-height="60" data-fgColor="#dc3545">
                                    <div data-chart-knob-title>% Horas Rechazadas</div>
                                </div>
                                <div class="col-5 col-xl-4 text-center">
                                    <input type="text" class="knob" data-knob="he_anual" data-readonly="true" data-width="60" data-height="60" data-fgColor="#17a2b8">
                                    <div data-chart-knob-title>% Horas en Proceso</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="fas fa-clock mr-1"></i>
                                Horas reportadas en centros de costos
                            </h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52" aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">line</span> -->
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">bar</span> -->
                                        <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">horizontalBar</span>
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">radar</span> -->
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">pie</span> -->
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">doughnut</span> -->
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">polarArea</span> -->
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">bubble</span> -->
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">scatter</span> -->
                                    </div>
                                </div>
                                <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas class="chart" data-chart-ident="he_ceco" data-chart-type="horizontalBar" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="row" data-chart-knob="he_ceco"></div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <?php /* --------------------------------------------------------------------- */ ?>
            <?php /* Gerente */ ?>
            <?php if ($rol == "Gerente") : ?>
            <?php endif ?>
            <?php /* --------------------------------------------------------------------- */ ?>
            <?php /* Jefe */ ?>
            <?php if ($rol == "Jefe") : ?>
            <?php endif ?>
            <?php /* --------------------------------------------------------------------- */ ?>
        </div>
    </div>
</section>