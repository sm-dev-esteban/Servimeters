<?php

    $request_view = (isset($_POST["view"]) && !empty($_POST["view"]) ? $_POST["view"] : false);

    $folder = "{$_SERVER["DOCUMENT_ROOT"]}/ReporteHE-main/view";

    $view = "{$folder}/{$request_view}";

    if (!strpos($view, "php")) { 
        $view = "$view.php";
    }

    if ($request_view) { // si lo que recibimos es diferente de false pasa la validación
        if (file_exists($view)) {
            include($view); // carga el contenido de la pagina
            exit();
        } else {
            include("$folder/error/404.php"); // no existe el archivo
            exit();
        }
    } else { // seria raro que entrara
        include("$folder/error/500.php"); // no recive nada
        exit();
    }
