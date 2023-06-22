<?php

session_start();
require("automaticForm.php");
$config = AutomaticForm::getConfig();

$html = $_POST["html"] ?? false;
$filename = $_POST["filename"] ?? false;
$folder = $_POST["folder"] ?? false;

if ($html && $filename && $folder) {
    $f = "{$config->FOLDER_SITE}files/$folder/$filename";
    foreach (["\\" => "/", "//" => "/"] as $search => $replace) {
        $f = str_replace($search, $replace, $f);
    }

    $open = fopen($f, "w");
    fwrite($open, $html);
    fclose($open);
}
