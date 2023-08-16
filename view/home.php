<?php
session_start();

if (isset($_GET["error"]) && !empty($_GET["error"])) {
    $error = $_GET["error"];
    $_GET["p"] = base64_encode("error/error.view?filenotfound=errorUrlByUser&error={$error}");
    $_GET["t"] = base64_encode($error == "404" ? "File not fount" : "Error");
};

if (isset($_GET["p"]) && !empty($_GET["p"]))
    $_SESSION["contentPage"] = [
        "page" => isset($_GET["p"]) ? base64_decode($_GET["p"]) : "",
        "title" => isset($_GET["t"]) ? base64_decode($_GET["t"]) : "",
        "scripts" => isset($_GET["s"]) ? base64_decode($_GET["s"]) : ""
    ]
?>

<?php include "shared/header.php" ?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <?php include "shared/nav.php" ?>
        <router></router>
        <?php include "shared/Comentarios.php" ?>
    </div>
</body>

<?php include "shared/footer.php" ?>

<?php if (!isset($_SESSION["usuario"]) || empty($_SESSION["usuario"])) : ?>
    <script>
        location.replace("../");
    </script>
<?php endif ?>