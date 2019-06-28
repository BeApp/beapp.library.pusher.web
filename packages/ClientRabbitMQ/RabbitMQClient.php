<?php

namespace Beapp\Push\Client\RabbitMQ;

use Beapp\Push\Core\Client\PushClient;
use Beapp\Push\Core\Push;
use Beapp\Push\Core\PushException;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class RabbitMQClient implements PushClient
{
    /** @var ProducerInterface */
    private $producer;

    /**
     * RabbitMQPushService constructor.
     * @param ProducerInterface $producer
     */
    public function __construct(ProducerInterface $producer)
    {
        $this->producer = $producer;
    }

    /**
     * @param Push $push
     * @return Push
     * @throws PushException
     */
    public function sendPush(Push $push): Push
    {
        try{
            $this->producer->publish(json_encode($push->jsonSerialize()));
        }catch(\Exception $e){
            throw new PushException($e->getMessage(), $e->getCode(), $e);
        }

        return $push;
    }
}
