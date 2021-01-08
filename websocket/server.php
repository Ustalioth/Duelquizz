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
            if (isset($users[$data['user_id']])) {
                die;
            } else {
                $users[$data['user_id']] = $userNode;
            }

            break;

        case 'quizz_duel_start':
            // il y a un utilisateur en attente de jouer
            if ($userWaiting !== null) {
                $quizzInProgress[] = [
                    'user1' => $userWaiting,
                    'user2' => getUserIdFromNode($users, $userNode),
                ];

                $websocket->send(json_encode([
                    'type' => 'start_quizz',
                ]), $users[$userWaiting]);

                $userWaiting = null;
            } else {
                $userId = getUserIdFromNode($users, $userNode);
                $userWaiting = $userId;
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
                }
            }
            break;
        case 'passTurn':
            if (isset($data['last'])) {
                foreach ($quizzInProgress as $quizz) {
                    if (getUserIdFromNode($users, $userNode) === $quizz['user1']) {
                        $websocket->send(json_encode([
                            'type' => 'next',
                            'end' => 'true',
                            'user1points' => $data['points']
                        ]), $users[$quizz['user2']]);
                    }
                }
            } else {
                foreach ($quizzInProgress as $quizz) {
                    if (getUserIdFromNode($users, $userNode) === $quizz['user1']) {
                        $websocket->send(json_encode([
                            'type' => 'next'
                        ]), $users[$quizz['user2']]);
                    } else if (getUserIdFromNode($users, $userNode) === $quizz['user2']) {
                        $websocket->send(json_encode([
                            'type' => 'next'
                        ]), $users[$quizz['user1']]);
                    }
                }
            }
            break;
        case 'informUser1':
            foreach ($quizzInProgress as $quizz) {
                if (getUserIdFromNode($users, $userNode) === $quizz['user2']) {
                    $websocket->send(json_encode([
                        'type' => 'informUser1',
                        'result' => $data['result']
                    ]), $users[$quizz['user1']]);
                }
            }
            break;
    }
});

$websocket->on('close', function (Bucket $bucket) use (&$users, &$quizzInProgress, &$userWaiting, $websocket) {
    $userNodeId = $bucket->getSource()->getConnection()->getCurrentNode()->getId();

    foreach ($users as $userId => $node) {
        if ($node->getId() === $userNodeId) {
            unset($users[$userId]);

            // echo $userId . "\n";
            // echo $userWaiting . "\n";

            if ($userId === $userWaiting) {
                $userWaiting = null;
            }
            $quizzAndKey = findInQuizz($quizzInProgress, $userId);

            if ($quizzAndKey !== null) {
                if ($quizzAndKey['quizz']['user1'] === $userId || $quizzAndKey['quizz']['user2'] === $userId) {

                    if ($quizzAndKey['quizz']['user1'] === $userId) {
                        echo "if l130\n";
                        $websocket->send(json_encode([
                            'type' => 'disconnected',
                        ]), $users[$quizzAndKey['quizz']['user2']]);
                    } else {
                        echo "if l136\n";

                        $websocket->send(json_encode([
                            'type' => 'disconnected',
                        ]), $users[$quizzAndKey['quizz']['user1']]);
                    }
                    unset($quizzInProgress[$quizzAndKey['key']]);
                }
            }
        }
    }
});

function findInQuizz($quizzInProgress, $userId)
{
    foreach ($quizzInProgress as $key => $quizz) {
        if ($quizz['user1'] === $userId || $quizz['user2'] === $userId) {
            return ['quizz' => $quizz, "key" => $key];
        }
    }

    return null;
}

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
