<?php
session_start();
if (isset($_GET['error']) && !empty($_GET['error'])) {
    $_GET['preview'] = $_GET['error'];
}
?>

<?php include 'shared/header.php' ?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include 'shared/nav.php' ?>
        <router></router>
    </div>
</body>
<?php include 'shared/footer.php' ?>

<?php if (isset($_GET['preview']) && !empty($_GET['preview'])) : ?>
    <script>
        sessionStorage.setItem("Content", `<?= $_GET['preview'] ?>`);
    </script>
<?php endif ?>

<?php if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) : ?>
    <script>
        location.replace("../");
    </script>
<?php endif ?>