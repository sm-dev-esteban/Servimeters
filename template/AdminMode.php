<?php

use Config\ImageProcessor;
use Config\Route;
use Config\Template;
use System\Config\AppConfig;

$route = new Route;

$style = [
    "https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback",
    "https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css",
    "plugins/fontawesome-free/css/all.min.css",
    "plugins/sweetalert2/sweetalert2.min.css",
    "plugins/icheck-bootstrap/icheck-bootstrap.min.css",
    "plugins/datatables-bs4/css/dataTables.bootstrap4.min.css",
    "plugins/datatables-responsive/css/responsive.bootstrap4.min.css",
    "plugins/datatables-buttons/css/buttons.bootstrap4.min.css",
    "plugins/datatables-select/css/select.bootstrap4.min.css",
    "plugins/fullcalendar/main.css",
    "plugins/daterangepicker/daterangepicker.css",
    "plugins/icheck-bootstrap/icheck-bootstrap.min.css",
    "plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css",
    "plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css",
    "plugins/select2/css/select2.min.css",
    "plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css",
    "plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css",
    "plugins/bs-stepper/css/bs-stepper.min.css",
    "plugins/dropzone/min/dropzone.min.css",
    "plugins/codemirror/codemirror.css",
    "plugins/codemirror/theme/ayu-dark.css",
    "plugins/toastr/toastr.min.css",
    "dist/css/adminlte.min.css",
    AppConfig::BASE_SERVER . "/assets/custom.css",
];

$script = [
    "plugins/jquery/jquery.min.js",
    "plugins/jquery-ui/jquery-ui.min.js",
    "plugins/bootstrap/js/bootstrap.bundle.min.js",
    "plugins/chart.js/Chart.min.js",
    "plugins/datatables/jquery.dataTables.min.js",
    "plugins/datatables-bs4/js/dataTables.bootstrap4.min.js",
    "plugins/datatables-responsive/js/dataTables.responsive.min.js",
    "plugins/datatables-responsive/js/responsive.bootstrap4.min.js",
    "plugins/datatables-buttons/js/dataTables.buttons.min.js",
    "plugins/datatables-buttons/js/buttons.bootstrap4.min.js",
    "plugins/jszip/jszip.min.js",
    "plugins/pdfmake/pdfmake.min.js",
    "plugins/pdfmake/vfs_fonts.js",
    "plugins/datatables-buttons/js/buttons.html5.min.js",
    "plugins/datatables-buttons/js/buttons.print.min.js",
    "plugins/datatables-buttons/js/buttons.colVis.min.js",
    "plugins/datatables-select/js/dataTables.select.min.js",
    "plugins/datatables-select/js/select.bootstrap4.min.js",
    "plugins/sweetalert2/sweetalert2.all.min.js",
    "plugins/bs-custom-file-input/bs-custom-file-input.min.js",
    "plugins/select2/js/select2.full.min.js",
    "plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js",
    "plugins/moment/moment.min.js",
    "plugins/fullcalendar/main.js",
    "plugins/inputmask/jquery.inputmask.min.js",
    "plugins/daterangepicker/daterangepicker.js",
    "plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js",
    "plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js",
    "plugins/bootstrap-switch/js/bootstrap-switch.min.js",
    "plugins/dropzone/min/dropzone.min.js",
    "plugins/codemirror/codemirror.js",
    "plugins/codemirror/mode/css/css.js",
    "plugins/codemirror/mode/xml/xml.js",
    "plugins/codemirror/mode/htmlmixed/htmlmixed.js",
    "plugins/codemirror/mode/php/php.js",
    "plugins/codemirror/mode/javascript/javascript.js",
    "plugins/toastr/toastr.min.js",
    "dist/js/adminlte.min.js",
    AppConfig::BASE_SERVER . "/assets/ldapAutoComplete/ldapAutoComplete.js",
    AppConfig::BASE_SERVER . "/assets/forDatatable/forDatatable.js",
    AppConfig::BASE_SERVER . "/assets/menu/menu.js",
    AppConfig::BASE_SERVER . "/assets/main.js"
];

if (AppConfig::USE_WEBSOCKET)
    $script[] = AppConfig::BASE_SERVER . "/assets/WebSocket/WebSocket.js";

$configForJs = json_encode([
    "BASE_SERVER" => AppConfig::BASE_SERVER,
    "CURRENCY" => AppConfig::CURRENCY,
    "LOCALE" => AppConfig::LOCALE
], JSON_UNESCAPED_UNICODE);

?>

<!DOCTYPE html>
<html lang="<?= AppConfig::LANGUAGE ?>">

<head>
    <meta charset="<?= AppConfig::CHARSET ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lorem ipsum dolor sit.">
    <meta name="keywords" content="Lorem, ipsum dolor, Veritatis, debitis quas, Ipsa, tenetur suscipit!">
    <meta name="author" content="Esteban serna palacios Dev Jr">
    <title>
        <?= AppConfig::COMPANY["NAME"] ?>
    </title>
    <link rel="icon" href="<?= ImageProcessor::correctImageURL(AppConfig::COMPANY['LOGO']) ?>" type="image/*" sizes="16x16">
    <?= Template::styles($style) ?>
    <base href="<?= trim(AppConfig::BASE_SERVER, "/") . "/" ?>">
</head>

<body class="sidebar-mini layout-fixed layout-navbar-fixed sidebar-collapse">
    <div class="wrapper">
        <?php include(__DIR__ . "/shared/menu.php") ?>
        <div class="content-wrapper">
            <?php $route->view(true) ?>
        </div>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
    <script type="module" src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs"></script>
    <script>
        const CONFIG = (find = null) => {
            const array = <?= $configForJs ?>;
            return find === null ? array : array[find] || null
        }
    </script>
    <?= Template::scripts($script) . Template::loadScriptClass() . $route->loadComponets() ?>
</body>

</html>