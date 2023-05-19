<?php
// archivo independiente para validar la session
session_start();
require_once("./session.controller.php");

$session = new sessionController;

$_count = count($session->readSession());

$return = [
    "STATUS" => !empty($_count) && isset($_SESSION["usuario"]) ? true : false,
    "COUNT" => $_count,
];

if (!isset($_SESSION["usuario"])) {
    session_destroy();
}

echo json_encode($return, JSON_UNESCAPED_UNICODE);
exit;
