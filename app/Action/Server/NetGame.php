<?php

namespace App\Action\Server;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use stdClass;

class NetGame implements MessageComponentInterface
{
    protected ?int $user1 = null;

    protected ?int $user2 = null;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);

        $truc = new stdClass();
        $this->clients[$truc] = "Oui";

        $nbClients = count($this->clients) - 1;

        echo $nbClients . "\n";

        if ($nbClients === 0) {
            $this->user1 = $conn->resourceId;
        } else {
            $this->user2 = $conn->resourceId;
        }

        echo "New connection! ({$conn->resourceId})\n";

        // if ($nbClients === 2) {
        //     foreach ($this->clients as $client) {
        //         $client->send("Démarrer le quizz");
        //     }
        // }

    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf(
            'Connection %d sending message "%s" to %d other connection%s' . "\n",
            $from->resourceId,
            $msg,
            $numRecv,
            $numRecv == 1 ? '' : 's'
        );

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    /**
     * Get the value of nbClients
     */
    public function getNbClients()
    {
        return $this->nbClients;
    }

    /**
     * Set the value of nbClients
     *
     * @return  self
     */
    public function setNbClients($nbClients)
    {
        $this->nbClients = $nbClients;

        return $this;
    }
}
