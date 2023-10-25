<?php

use Controller\RouteController;

$route = new RouteController(FOLDER_VIEW);
?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servimeters</title>
    <link rel="icon" href="<?= SERVER_SIDE ?>/Img/SM CIRCULAR.png" type="image/*" sizes="16x16">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/bs-stepper/css/bs-stepper.min.css">
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/dropzone/min/dropzone.min.css">
    <style>
        <?php for ($i = 0; $i <= 100; $i += 50) :
            echo <<<CSS
                .start-{$i} {
                    left: $i% !important;
                }

                .top-{$i} {
                    top: $i% !important;
                }
            CSS;
        endfor ?>
    </style>
    <!-- jQuery -->
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/jquery/jquery.min.js"></script>
    <!-- <script src="<?= SERVER_SIDE ?>/View/assets/websocket.js"></script> -->
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <?php include(__DIR__ . "/shared/menu.php") ?>
        <div class="content-wrapper" data-router>
            <?php $route->showPage(!PRODUCTION) ?>
        </div>
        <?php include(__DIR__ . "/shared/footer.php") ?>

        <?php include(__DIR__ . "/shared/modalMain.php") ?>
        <?php include(__DIR__ . "/shared/modalReportHE.php") ?>
        <?php include(__DIR__ . "/shared/modalCommentsHE.php") ?>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/select2/js/select2.full.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/moment/moment.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/inputmask/jquery.inputmask.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/dropzone/min/dropzone.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/jszip/jszip.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= SERVER_SIDE ?>/AdminLTE/dist/js/adminlte.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- <script src="<?= SERVER_SIDE ?>/AdminLTE/dist/js/demo.js"></script> -->
    <script>
        <?= $GETWITHJS ?>

        $(document).ready(() => {
            LOADALL();
        })
    </script>
    <script src="<?= SERVER_SIDE ?>/View/assets/main.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/View/assets/autoload.min.js"></script>
    <script src="<?= SERVER_SIDE ?>/View/assets/createDropzone/createDropzone.min.js"></script>
</body>

</html>