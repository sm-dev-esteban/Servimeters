<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Servimeters implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        echo "App Running \n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "Nueva conexión! ({$conn->resourceId}) \n";
        echo "Conexiones activas: (" . count($this->clients) . ") \n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf(
            'La conexión %d envio un mensaje "%s" a %d conexión%s ' . "\n",
            $from->resourceId,
            $msg,
            $numRecv,
            $numRecv == 1 ? "" : "es"
        );

        foreach ($this->clients as $client) {
            if (strpos($msg, '"general":true') ? true : $from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);

        echo "La conexión {$conn->resourceId} se desconecto \n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Ha occurrido un error: {$e->getMessage()} \n";
        $conn->close();
    }
}
