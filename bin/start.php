<?php
session_start();
require("../controller/automaticForm.php");

$config = AutomaticForm::getConfig();

$cmd = 'cd ' . $config->FOLDER_SITE . ' & composer update & php ' . __DIR__ . '\server.php';

$check_bat = fopen("check.bat", "w");
fwrite($check_bat, '
@echo off

setlocal

REM Puerto a verificar
set "puerto=8080"

REM Comando a ejecutar si encuentra el puerto
set "comando=' . $cmd . '"

REM Verificar el puerto
netstat -ano | findstr ":%puerto%" >nul

REM Si encuentra el puerto, ejecuta el comando
if %errorlevel% equ 0 (
    echo El puerto %puerto% esta activo.
) else (
    %comando%
)

endlocal
');
fclose($check_bat);

// comandos
// 1. Entra a la carpeta del proyecto
// 2. Actualiza composer
// 3. Ejecuta el servidor
executeCMD('start cmd.exe @cmd /k "' . __DIR__ . '\check.bat"');

function executeCMD($cmd)
{
    if (substr(php_uname(), 0, 7) == "Windows") {
        pclose(popen("start /B {$cmd}", "r"));
    } else {
        exec("{$cmd} > /dev/null &");
    }
}
