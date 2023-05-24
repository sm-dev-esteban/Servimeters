<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../images/Logo Prueba SM.png" alt="ServimetersLogo" height="180" width="180">
</div>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="Principal/default.view" class="nav-link">Home</a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Buscar" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="dark-mode" href="#" role="button">
                <i class="fa fa-sun"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="Principal/default.view" class="brand-link">
        <img src="../images/Logo Prueba SM.png" alt="Servimeters Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Servimeters</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <!-- <div class="image">
                <img src="../images/Logo Prueba SM.png" class="img-circle elevation-2" alt="User Image">
            </div> -->
            <div class="info">
                <a href="prueba.view" class="d-block"><?= $_SESSION['usuario'] ?></a>
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
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- <li class="nav-header">Titulo</li> -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            Administrar
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="admin/clase.view" data-script="claseAdmin" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Clase</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="admin/centroCosto.view" data-script="cecoAdmin" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Centros de Costo</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="admin/Aprobadores.view" data-script="aprobadoresAdmin" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Aprobadores</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="prueba.view" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tipos de Recargo</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="prueba.view" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tipos de HE</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- <li class="nav-header">Titulo</li> -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-clock"></i>
                        <p>
                            Horas extras
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="reportar/index.view" class="nav-link" data-script="reporteHE">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Reportar horas extras</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <!-- <a href="estado/listEstado.view" class="nav-link" data-script="listadoHE, detailsReporte"> -->
                            <a href="estado/listEstado.view" class="nav-link" data-script="listadoHE">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mis horas extras</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <!-- <a href="gestionHE/gestionar.view" class="nav-link" data-script="aproveRejectHE, detailsReporte"> -->
                            <a href="gestionHE/gestionar.view" class="nav-link" data-script="aproveRejectHE">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gestionar horas extras</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            Permisos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="permisos/solicitud.view" class="nav-link" data-script="solicitud">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Solicitar permiso</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="permisos/listSolicitud.view" class="nav-link" data-script="solicitud">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mis solicitudes</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="exit" class="nav-link text-danger">
                        <i class="nav-icon fas fa-times"></i>
                        <p>
                            salir
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<script>
    // pequeño controlador para el estilo de la barra de navegación
    document.querySelectorAll(`nav .nav-item .nav-link`).forEach(nav => { // recorro cada uno de los items del nav
        nav.addEventListener(`click`, () => { // y detectamos cuando se le da click a cualquiera de ellos
            document.querySelectorAll(`nav .nav-item .nav-link.active`).forEach(nav_active => { // hacemos un recorrido para quitar al que esta activo actualemente y le quitamos la clase (Lo hago en un ciclo por seguridad no se si pueda darse el caso de que tenga a varios items activos y al intentar llamarlo esto no funcionaria - ipoteticamente)
                nav_active.classList.remove("active"); // quitamos la clase al que este activo
            });
            nav.classList.add("active"); // agregamos la clase al que estamos presionando
        })
    })
</script>