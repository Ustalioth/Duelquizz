<?php

use Hoa\Event\Bucket;
use Hoa\Socket\Server;
use Hoa\Websocket\Server as WsServer;

require_once(__DIR__ . '/../vendor/autoload.php');

$server = new Server('ws://127.0.0.1:8889');
$websocket = new WsServer($server);

$users = [];
$userWaiting = null;
$quizzInProgress = [];

$websocket->on('open', function (Bucket $bucket) {
    echo 'Un utilisateur vient de se connecter : ' . $bucket->getSource()->getConnection()->getCurrentNode()->getId() . "\n";
});

$websocket->on('message', function (Bucket $bucket) use (&$users, $websocket, &$userWaiting, &$quizzInProgress) {
    $data = json_decode($bucket->getData()['message'], true);
    $userNode = $bucket->getSource()->getConnection()->getCurrentNode();

    switch ($data['type']) {
        case 'connect':
            $users[$data['user_id']] = $userNode;
            break;
        case 'quizz_duel_start':
            // il y a un utilisateur en attente de jouer
            if ($userWaiting !== null) {
                echo "Not null\n";
                $quizzInProgress[] = [
                    'user1' => $userWaiting['user_id'],
                    'user2' => getUserIdFromNode($users, $userNode),
                ];

                $websocket->send(json_encode([
                    'type' => 'start_quizz',
                ]), $users[$userWaiting['user_id']]);

                $userWaiting = null;
            } else {
                $userId = getUserIdFromNode($users, $userNode);
                echo $userId . "\n";
                $userWaiting = ['user_id' => $userId];
                $websocket->send(json_encode([
                    'type' => 'no_player'
                ]), $userNode);
            }
            break;
        case 'handlePlayer2':

            foreach ($quizzInProgress as $quizz) {
                if (getUserIdFromNode($users, $userNode) === $quizz['user1']) {
                    $websocket->send(json_encode([
                        'type' => 'need_to_wait',
                        'idQuizz' => $data['idQuizz']
                    ]), $users[$quizz['user2']]);
                    break;
                }
            }

        case 'quizz_duel_respond':
            $questionnumber = $data['questionNumber'];

            break;
        case 'forward':
            [
                'user_id' => $userId,
                'message' => $message,
            ] = $data;

            $websocket->send($message, $users[$userId]);
            break;
    }
});

$websocket->on('close', function (Bucket $bucket) use (&$users) {
    $userNodeId = $bucket->getSource()->getConnection()->getCurrentNode()->getId();

    foreach ($users as $userId => $node) {
        if ($node->getId() === $userNodeId) {
            unset($users[$userId]);
        }
    }
});

function getUserIdFromNode($users, $userNode)
{
    foreach ($users as $id => $node) {
        if ($node->getId() === $userNode->getId()) {
            return $id;
        }
    }

    return null;
}

echo "WebSocket Server is listening on port 8889\n";
$websocket->run();
