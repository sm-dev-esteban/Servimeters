<?php

/*---------------------------------- php config ----------------------------------*/
# IN PRODUCTION AND CACHE CONTROL
define("PRODUCTION", false);
define("CACHE_CONTROL", false);

# FOLDER AND SERVER
define("FOLDER_SIDE", str_replace("\\", "/", __DIR__));
define("SERVER_SIDE", str_replace($_SERVER["DOCUMENT_ROOT"], ($_SERVER["REQUEST_SCHEME"] ?? "http") . "://" . $_SERVER["HTTP_HOST"], FOLDER_SIDE));

# TEMPLATE
define("TEMPLATE_VIEW", isset($_SESSION["usuario"]) ? "onSession" : "offSession");
define("FOLDER_VIEW", FOLDER_SIDE . "/View/page/" . TEMPLATE_VIEW);

# LOCATION
define("TIMEZONE", "America/Bogota");
define("CHARSET", "utf-8");
define("LANGUAGE", "es");
define("CURRENCY", "COP");
define("UPS_CODE", "CO");
define("LOCALE", LANGUAGE . "-" . UPS_CODE);

# DATABASE
define("DATABASE", [
    "HOSTNAME" => (PRODUCTION ? "10.10.10.6" : "localhost"),
    "USERNAME" => (PRODUCTION ? "sa" : "sa"),
    "PASSWORD" => (PRODUCTION ? "S3rv1830117370*" : "Es123456*"),
    "DATABASE" => (PRODUCTION ? "new_Horas_Extras" : "new_Horas_Extras"),
    "PORT" => "3306",
    "FILE" => FOLDER_SIDE . "/Database/db.sql",
    "GESTOR" => "SQLSRV" // ACEPTED (MYSQL, SQLSRV, SQLITE)
]);

# MAIL
# PORTS: 25, 465, 587 y 2525
# (TLS): 587
# (SSL): 465
define("MAIL", [
    // "FROM" => "soportesm@servimeters.net",
    "FROM" => "esteban.serna.p@gmail.com",
    // "HOST" => "smtp.office365.com",
    "HOST" => "smtp.gmail.com",
    // "USERNAME" => "soportesm@servimeters.net",
    "USERNAME" => "esteban.serna.p@gmail.com",
    // "PASSWORD" => "Sm-123456*",
    "PASSWORD" => "bnng gszc exfh eotx",
    "PORT" => 587,
    "SMTPSECURE" => "tls"
]);

# DATATABLE
define("DATATABLE", [
    "responsive" => true, "lengthChange" => true, "autoWidth" => false, "dom" => "Bfrtip",
    "buttons" => ["copy", "csv", "excel", "pdf", "print", "colvis"],
    "language" => [
        "lengthMenu" => "mostrar _MENU_ entradas",
        "zeroRecords" => "No conseguimos ningún resultado",
        "info" => "Mostrando _PAGE_ de _PAGES_",
        "infoFiltered" => "(filtrado _MAX_ registros totales)",
        "search" => "Buscar",
        "loadingRecords" => "Cargando...",
        "processing" => "Procesando...",
        "emptyTable" => "Sin resultados para mostrar",
        "infoEmpty" => "Sin resultados para mostrar",
        "paginate" => [
            "first" => "Primero",
            "last" => "Ultimo",
            "next" => "Siguiente",
            "previous" => "Anterior"
        ]
    ]
]);

# MAX SIZE
define("MAX_SIZE", "100M");

/*---------------------------------- FROM JS ----------------------------------*/
$GETCONFIG = json_encode([
    "SERVER_SIDE" => SERVER_SIDE,
    "DATATABLE" => DATATABLE,
    "TIMEZONE" => TIMEZONE,
    "CHARSET" => CHARSET,
    "LANGUAGE" => LANGUAGE,
    "CURRENCY" => CURRENCY,
    "LOCALE" => LOCALE
], JSON_UNESCAPED_UNICODE);

$GETWITHJS = trim(<<<JS
    const GETCONFIG = (e) => { return {$GETCONFIG}[e.toLocaleUpperCase()] ?? false }
JS);
