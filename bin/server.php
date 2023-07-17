<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Servimeters;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/config/LoadConfig.config.php';

$config = LoadConfig::getConfig();

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Servimeters()
        )
    ),
    $config->WEBSOCKET ?? 0
);

$server->run();
