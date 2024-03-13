<?php

use System\Config\AppConfig;
use Config\ImageProcessor;
use Config\Route;

$session = fn (string $name): mixed => $_SESSION[$name] ?? null;

?>

<aside class="main-sidebar main-sidebar-custom sidebar-light-primary">
    <a href="<?= Route::href() ?>" class="brand-link">
        <img src="<?= ImageProcessor::correctImageURL(AppConfig::COMPANY["LOGO"]) ?>" alt="<?= AppConfig::COMPANY["NAME"] ?> Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">
            <?= AppConfig::COMPANY["NAME"] ?>
        </span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="<?= Route::href("profile") ?>" class="d-block">.
                    <?= $session("usuario") ?>
                </a>
            </div>
        </div>
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-legacy nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <?php if ($session("usuario") === "Esteban Serna Palacios") : ?>
                    <li class="nav-item">
                        <a href="<?= Route::href("Calendar") ?>" class="nav-link">
                            <i class="nav-icon fas fa-calendar"></i>
                            <p>
                                Calendar
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-code"></i>
                            <p>
                                Dev
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= Route::href("dev/projects") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Proyectos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= Route::href("dev/project-add") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Agregar proyecto</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= Route::href("dev/project-edit") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Editar proyecto</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= Route::href("dev/project-detail") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Ver proyecto</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif ?>
                <?php if ($session("isApprover") && $session("admin")) : ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Administrar
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= Route::href("Administrar/clase") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Clase</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= Route::href("Administrar/centroCosto") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Centros de costo</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= Route::href("Administrar/aprobadores") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Aprobadores</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= Route::href("Administrar/cargos") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Cargos</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif ?>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>
                            Horas extras
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= Route::href("horasExtras/reportarHoras") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Reportar horas extras</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= Route::href("horasExtras/misHoras") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mis horas extras</p>
                            </a>
                        </li>
                        <?php if ($session("isApprover")) : ?>
                            <li class="nav-item">
                                <a href="<?= Route::href("horasExtras/gestion") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Gestionar horas extras</p>
                                </a>
                            </li>
                        <?php endif ?>
                        <?php if ($session("isApprover")) : ?>
                            <li class="nav-header">Reportes</li>
                            <?php if ($session("id_gestiona") == 3) : ?>
                                <li class="nav-item">
                                    <a href="<?= Route::href("horasExtras/reporte", ["type" => base64_encode(1)]) ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Reporte Contable</p>
                                    </a>
                                </li>
                            <?php endif ?>
                            <li class="nav-item">
                                <a href="<?= Route::href("horasExtras/reporte", ["type" => base64_encode(2)]) ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Reporte de horas</p>
                                </a>
                            </li>
                        <?php endif ?>
                    </ul>
                </li>
                <?php if ($session("apruebaSolicitudPersonal") || $session("id_gestiona") == 2) : ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-address-book"></i>
                            <p>
                                Solicitud de personal
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if ($session("id_gestiona") == 2) : ?>
                                <li class="nav-item">
                                    <a href="<?= Route::href("solicitudPersonal/crearSolicitud") ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Crear Solicitud</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= Route::href("solicitudPersonal/solicitudes") ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Mis Solicitudes</p>
                                    </a>
                                </li>
                            <?php endif ?>
                            <?php if ($session("apruebaSolicitudPersonal")) : ?>
                                <li class="nav-item">
                                    <a href="<?= Route::href("solicitudPersonal/aprobarSolicitud") ?>" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Aprobar Solicitudes</p>
                                    </a>
                                </li>
                            <?php endif ?>
                        </ul>
                    </li>
                <?php endif ?>
                <li class="nav-item">
                    <a href="#" class="nav-link text-danger" id="btnDisconnect">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p class="text">Salir</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>