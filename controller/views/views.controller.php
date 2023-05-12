<?php
    require_once "../../config/LoadConfig.config.php";
    $config = LoadConfig::getConfig();

    // con esto ya no es necesario iniciar la session en ninguna archivo
    session_start();
    
    $view = (isset($_POST["view"]) && !empty($_POST["view"]) ? $_POST["view"] : false);
    $titl = (isset($_POST["titl"]) && !empty($_POST["titl"]) ? $_POST["titl"] : false);
    $router = explode("?", $view);
    $request_view = $router[0];
    
    // gets
    // creamos los gets que estan enviando
    if ($view && count($router) == 2) {
        $gets = explode("&", $router[1]);
        $countG = count($gets);
        for ($i = 0; $i < $countG; $i++) {
            $x = explode("=", $gets[$i]);
            $countX = count($gets);
            $_GET[$x[0]] = $x[1];
        }
    }
    // gets
    
    // timezone
    // configuramos el timezone para no tener problemas con la hora en php
    $timenoze = (isset($_GET["t"]) && !empty($_GET["t"]) ? $_GET["t"] : false);
    date_default_timezone_set($timenoze); // con esto todas las paginas van a tener un timezone configurado
    // timezone
    
    $folder = "{$config->FOLDER_SITE}view";
    $view = "{$folder}/{$request_view}";

    if (!strpos($view, "php")) {
        $view = "$view.php";
    }

?>

<?php if ($titl): ?>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 p-0"><?= $titl ?></h1>
                </div>
                <div class="col-sm-6">
                    <div class="breadcrumb float-sm-right">    
                        <!-- Pendiente -->
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php
    if ($request_view) { // si lo que recibimos es diferente de false pasa la validación
        if (file_exists($view)) {
            // require($view);
            include($view); // carga el contenido de la pagina
            exit();
        } else {
            $_GET["filenotfound"] = $request_view;
            $_GET["error"] = 404;
            // require("$folder/error/error.view.php");
            include("$folder/error/error.view.php"); // no existe el archivo
            exit();
        }
    } else { // seria raro que entrara a esta validación, pero uno nunca sabe
        $_GET["filenotfound"] = $request_view;
        $_GET["error"] = 500;
        // require("$folder/error/error.view.php");
        include("$folder/error/error.view.php"); // no recive nada
        exit();
    }
?>