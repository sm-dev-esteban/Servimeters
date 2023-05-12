<?php

$fnf = (isset($_GET['filenotfound']) && !empty($_GET['filenotfound']) ? $_GET['filenotfound'] : false);
$e = (isset($_GET['error']) && !empty($_GET['error']) ? $_GET['error'] : false);

$x = "undefined";

if ($fnf !== false) {
    $fnf = explode(".", $fnf);
    $x = $fnf[0];
}
$error = ($e == "404" ? 'No pudimos encontrar el siguiente archivo <b class="text-danger">' . $x . '</b>' : 'Ha ocurrido un error inesperado');

$color = [
    "404" => "warning",
    "500" => "danger"
];

?>
<section class="content">
    <div class="error-page">
        <h2 class="headline text-<?= $color[$e] ?>"><?= $e ?></h2>
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-<?= $color[$e] ?>"></i><?= $error ?></h3>
            <p>
                <a href="javascript:contentPage('Principal/default.view')">Volver al Inicio</a>
            </p>
        </div>
    </div>
</section>