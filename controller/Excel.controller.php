<?php

session_start();
include "automaticForm.php";

$DATA = json_decode(trim(file_get_contents("php://input")));

$param = $DATA->param ?? "Error send request";
$content = $DATA->content ?? "Error send request";
$title = $param->title ?? false;

// header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=" . ($param->filename ?? "Error") . "");
?>

<table>
    <?php if ($title != false && $title != "false") : ?>
        <caption><?= $title ?></caption>
    <?php endif ?>
    <?= AutomaticForm::utf8decode($content) ?>
</table>

<?php exit() ?>