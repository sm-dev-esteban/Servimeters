<?php
$user = $_SESSION["usuario"];

$acceso = array_map(function ($name) {
    return strtoupper($name);
}, [
    "Esteban Serna Palacios",
    "William Ricardo Enciso Bautista"
]);

$accesoPremium = in_array(strtoupper($user), $acceso);

$isApprover = $_SESSION["isApprover"] ?? false;
$staffRequest = $_SESSION["staffRequest"] ?? false;
$manages = $_SESSION["manages"] ?? false;

?>
<aside class="main-sidebar sidebar-light-primary elevation-4">
    <a href="<?= SERVER_SIDE ?>" class="brand-link">
        <img src="<?= COMPANY["LOGO"] ?>" alt="<?= COMPANY["NAME"] ?>" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?= COMPANY["NAME"] ?></span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= COMPANY["LOGO"] ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $user ?></a>
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
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <?php if ($accesoPremium || $isApprover === true) : ?>
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
                                <a href="<?= SERVER_SIDE ?>/Administrar/clase" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Clase</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= SERVER_SIDE ?>/Administrar/centroCosto" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Centros de costo</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= SERVER_SIDE ?>/Administrar/aprobadores" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Aprobadores</p>
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
                            <a href="<?= SERVER_SIDE ?>/horasExtras/reportarHoras" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Reportar horas extras</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= SERVER_SIDE ?>/horasExtras/misHoras" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mis horas extras</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= SERVER_SIDE ?>/horasExtras/gestion" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gestionar horas extras</p>
                            </a>
                        </li>
                        <li class="nav-header">Reportes</li>
                        <li class="nav-item">
                            <a href="<?= SERVER_SIDE ?>/horasExtras/reporte?type=<?= base64_encode(1) ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Reporte Contable</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= SERVER_SIDE ?>/horasExtras/reporte?type=<?= base64_encode(2) ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Reporte de horas</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-clock"></i>
                        <p>
                            Solicitud de permisos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= SERVER_SIDE ?>/SolicitudPermisos/formularioSolicitud" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Formulario Solicitud</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= SERVER_SIDE ?>/SolicitudPermisos/mostrarSolicitud" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mostrar Solicitudes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= SERVER_SIDE ?>/SolicitudPermisos/tipoPermisos" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tipo de Permisos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php if ($staffRequest === "SI" || $manages === "RH" || $accesoPremium) : ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-address-book"></i>
                            <p>
                                Solicitud de personal
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Solicitud
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview" style="display: none">
                                    <li class="nav-item">
                                        <a href="<?= SERVER_SIDE ?>/solicitudPersonal/solicitud/crearSolicitud" class="nav-link">
                                            <i class="far fa-dot-circle nav icon"></i>
                                            <p>Crear Solicitud</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= SERVER_SIDE ?>/solicitudPersonal/solicitud/solicitudes" class="nav-link">
                                            <i class="far fa-dot-circle nav icon"></i>
                                            <p>Mis Solicitudes</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= SERVER_SIDE ?>/solicitudPersonal/solicitud/cargarHojasDeVida" class="nav-link">
                                            <i class="far fa-dot-circle nav icon"></i>
                                            <p>Cargar hojas de vida</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= SERVER_SIDE ?>/solicitudPersonal/solicitud/agregarCandidatos" class="nav-link">
                                            <i class="far fa-dot-circle nav icon"></i>
                                            <p>Agregar Candidatos</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= SERVER_SIDE ?>/solicitudPersonal/solicitud/seleccionarCandidatos" class="nav-link">
                                            <i class="far fa-dot-circle nav icon"></i>
                                            <p>Seleccionar Candidatos</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <?php if ($staffRequest === "SI") : ?>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Aprobación jefe
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="display: none">
                                        <li class="nav-item">
                                            <a href="<?= SERVER_SIDE ?>/solicitudPersonal/solicitud/aprobacion" class="nav-link">
                                                <i class="far fa-dot-circle nav icon"></i>
                                                <p>Solicitudes</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif ?>
                        </ul>
                    </li>
                <?php endif ?>
                <li class="nav-item">
                    <a href="<?= SERVER_SIDE ?>/exit" class="nav-link text-danger">
                        <i class="nav-icon fas fa-door-open"></i>
                        <p class="text">Salir</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>