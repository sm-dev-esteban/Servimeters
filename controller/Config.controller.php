<?php

require_once('../config/LoadConfig.config.php');

function getConfig($keyClient, $ivClient){
    $config = LoadConfig::getConfig();
    $text_json = json_encode($config);

    //Clave de cifrado AES
    //$key = openssl_random_pseudo_bytes(16);
    $key = base64_decode($keyClient);
    $iv = base64_decode($ivClient);

    //Cifrar archivo
    //$iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($text_json, "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv);

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="archivo_cifredo"');
    $response = [
        'data' => base64_encode($encrypted),
    ];

    //'iv' => base64_encode($iv)
    //'key' => base64_encode($key),

    echo json_encode($response);
}

$key = $_POST['key'];
$iv = $_POST['iv'];

getConfig($key, $iv);
