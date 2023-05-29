<?php
session_start();
require("../controller/automaticForm.php");

$config = AutomaticForm::getConfig();

// comandos
// 1. Entra a la carpeta del proyecto
// 2. Actualiza composer
// 3. Ejecuta el servidor
$cmd = 'cd ' . $config->FOLDER_SITE . ' & composer update & php ' . __DIR__ . '\server.php';
executeCMD('start cmd.exe @cmd /k "' . $cmd . '"'); 

function executeCMD($cmd)
{
    if (substr(php_uname(), 0, 7) == "Windows") {
        pclose(popen("start /B {$cmd}", "r"));
    } else {
        exec("{$cmd} > /dev/null &");
    }
}
