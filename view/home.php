<?php session_start() ?>

<?php include 'shared/header.php' ?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include 'shared/nav.php' ?>
        <router></router>
    </div>
</body>
<?php include 'shared/footer.php' ?>

<?php if (isset($_GET['error']) || !empty($_GET['error'])) : ?>
    <script>
        sessionStorage.setItem("errorContent", `<?= $_GET['error'] ?>`);
    </script>
<?php endif ?>

<?php if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) : ?>
    <script>
        location.replace("../");
    </script>
<?php endif ?>