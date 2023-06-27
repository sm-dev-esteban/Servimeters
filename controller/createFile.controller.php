<?php

session_start();
require("automaticForm.php");
$config = AutomaticForm::getConfig();

$data = $_POST["data"] ?? false;
$filename = $_POST["filename"] ?? false;
$folder = $_POST["folder"] ?? false;

if ($data && $filename && $folder) {
    $folder = "{$config->FOLDER_SITE}files/{$folder}/";

    if (!file_exists($folder)) mkdir($folder, 0777, true);

    $filename = "{$folder}{$filename}";

    foreach (["\\" => "/", "//" => "/"] as $search => $replace) {
        $filename = str_replace($search, $replace, $filename);
    }

    $stream = fopen($filename, "w");
    fwrite($stream, $data);
    fclose($stream);
}
