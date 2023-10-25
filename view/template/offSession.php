<?php

use Controller\RouteController;

$route = new RouteController(FOLDER_VIEW);
?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE ?>">

<head>
    <meta charset="<?= CHARSET ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Servimeters</title>
    <link rel="icon" href="<?= SERVER_SIDE ?>/Img/SM CIRCULAR.png" type="image/*" sizes="16x16">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?= SERVER_SIDE ?>/AdminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
</head>

<!-- jQuery -->
<script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/jquery/jquery.min.js"></script>

<body class="hold-transition login-page" data-router style="--image: url('<?= SERVER_SIDE . "/Img/pic10.webp" ?>')">
    <?php $route->showPage(!PRODUCTION) ?>
</body>
<script src="<?= SERVER_SIDE ?>/View/assets/autoload.js"></script>
<script>
    GETSERVERSIDE = () => {
        return `<?= SERVER_SIDE ?>`
    }

    $(document).ready(() => {
        LOADALL();
    })
</script>

</html>