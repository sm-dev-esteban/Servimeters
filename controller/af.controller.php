<?php
session_start();

require_once('automaticForm.php');

$action = isset($_GET["action"]) ? $_GET["action"] : "";
$params = isset($_POST["param"]) && is_array($_POST["param"]) ? $_POST["param"] : (isset($_POST["param"]) ? [$_POST["param"]] : []);

define("ACTION", $action);

if (array_filter(AutomaticForm::getClassMethods(), function ($x) {
    return $x["name"] == ACTION;
})) {
    echo json_encode(AutomaticForm::$action(...$params));
    exit();
} else {
    echo json_encode(["error" => "action is undefined"], JSON_UNESCAPED_UNICODE);
    exit();
}
