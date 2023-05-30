<?php
session_start();
// no creo que use este archivo, pero igual lo dejo para tenerlo en cuenta en algun punto 
require_once('automaticForm.php');

// $_POST = json_decode(file_get_contents('php://input'), true);
// $_GET = json_decode(file_get_contents('php://input'), true);

$action = isset($_GET["action"]) ? $_GET["action"] : "";
$params = isset($_POST["param"]) ? $_POST["param"] : "";

define("ACTION", $action);

if (array_filter(AutomaticForm::getClassMethods(), function ($x) {
    return $x["name"] == ACTION;
})) {
    // echo json_encode(AutomaticForm::$action(implode(', ', array_map(function ($x) {
    //     $r = random();
    //     $val[$r] = $x;
    //     return $val[$r];
    // }, $params))), JSON_UNESCAPED_UNICODE);
    // exit();
    $i = -1;
    echo json_encode(AutomaticForm::$action(
        isset($params[$i += 1]) ? $params[$i] : "",
        isset($params[$i += 1]) ? $params[$i] : "",
        isset($params[$i += 1]) ? $params[$i] : "",
        isset($params[$i += 1]) ? $params[$i] : "",
        isset($params[$i += 1]) ? $params[$i] : "",
        isset($params[$i += 1]) ? $params[$i] : ""
    ), JSON_UNESCAPED_UNICODE);
    exit();
} else {
    echo json_encode(["error" => "action in undefined"], JSON_UNESCAPED_UNICODE);
    exit();
}

// function ramdon()
// {
//     return rand(0, date("YmdHis"));
// }
