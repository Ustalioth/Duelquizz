<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

use App\Action\Server\NetGame;

    require dirname(__DIR__) . '../../../vendor/autoload.php';

    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new NetGame()
            )
        ),
        8080
    );
    $server->run();