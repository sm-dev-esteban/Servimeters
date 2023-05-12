<?php
    // archivo independiente para validar la session
    session_start();
    $_count = count($_SESSION);

    if (!isset($_SESSION["usuario"])) {
        session_destroy();
    }

    echo json_encode([
        "STATUS" => !empty($_count) && isset($_SESSION["usuario"]) ? true : false,
        "COUNT" => $_count,
    ], JSON_UNESCAPED_UNICODE);
    exit;
?>