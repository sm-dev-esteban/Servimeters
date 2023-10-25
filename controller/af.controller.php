<?php
session_start();

require_once('automaticForm.php');

$action = $_GET["action"] ?? "";
$params = isset($_POST["param"]) && is_array($_POST["param"]) ? $_POST["param"] : (isset($_POST["param"]) ? [$_POST["param"]] : []);

if (array_filter(AutomaticForm::getClassMethods(), function ($x) use ($action) {
    return $x["name"] == $action;
})) {
    echo json_encode(AutomaticForm::$action(...$params));
    exit();
} else {
    echo json_encode(["error" => "action is undefined"], JSON_UNESCAPED_UNICODE);
    exit();
}
