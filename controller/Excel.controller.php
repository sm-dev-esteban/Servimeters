<?php

session_start();
include "automaticForm.php";

$DATA = json_decode(trim(file_get_contents("php://input")));

$param = (isset($DATA->param) ? $DATA->param : "Error send request");
$content = (isset($DATA->content) ? $DATA->content : "Error send request");

// header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=" . ($param->filename ?? "Error") . "");
?>

<table>
    <caption><?= $param->title ?? "Error" ?></caption>
    <?= AutomaticForm::utf8decode($content) ?>
</table>

<?php exit() ?>