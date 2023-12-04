<?php session_start();

include_once __DIR__ . "/vendor/autoload.php";
include __DIR__ . "/Config.php";

date_default_timezone_set(TIMEZONE);
header("Content-type: text/html; charset=" . CHARSET);

# @chatgpt
if (CACHE_CONTROL) {
    // Definir variables para controlar la caché
    $lastModified = filemtime(__FILE__);
    $etag = md5_file(__FILE__);

    // Establecer cabeceras HTTP relacionadas con la caché
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModified) . " GMT");
    header("Etag: $etag");
    header("Cache-Control: public, max-age=3600"); // Controla el tiempo máximo de almacenamiento en caché en segundos

    // Comprobar si el navegador ya tiene la versión en caché
    if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? false) == $lastModified || trim($_SERVER['HTTP_IF_NONE_MATCH'] ?? false) == $etag) {
        header("HTTP/1.1 304 Not Modified");
        exit;
    }
}
# @chatgpt

include __DIR__ . "/View/template/" . TEMPLATE_VIEW . ".php";