<?php
namespace Budkit\Cms\Helper;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Pusher implements WampServerInterface
{

    /**
     * A lookup of all the topics clients have subscribed to
     */
    protected $subscriptions = array();


    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        $this->subscriptions[$topic->getId()] = $topic;
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        if(isset($this->subscriptions[$topic->getId()]))
            unset($this->subscriptions[$topic->getId()]);

        //echo $topic->getId();
    }

    public function onOpen(ConnectionInterface $conn)
    {
       // echo "opened";
    }


    public function onMessage($message){

        //messages should be sent as encode json?
        $data = json_decode($message, true);

        //must tell us the topic
        if(!isset($data["topic"])) return null; //need a better way of sending topic messages;


        $topic = $data["topic"];
        // If the lookup topic object isn't set there is no one to publish to
        if (!array_key_exists($topic, $this->subscriptions)) {
            return;
        }

        $channel = $this->subscriptions[$topic];

        // re-send the data to all the clients subscribed to that category
        $channel->broadcast( $message );

    }

    public function onClose(ConnectionInterface $conn)
    {
       // echo "closed";
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "error";
    }

}