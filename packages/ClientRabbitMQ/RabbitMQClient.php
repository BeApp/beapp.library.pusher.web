<?php

namespace Beapp\Push\Client\RabbitMQ;

use Beapp\Push\Core\Client\PushClient;
use Beapp\Push\Core\Push;
use Beapp\Push\Core\PushException;
use Beapp\Push\Core\PushResult;
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
     * @return PushResult The result of the sending
     * @throws PushException
     */
    public function sendPush(Push $push): PushResult
    {
        try {
            $this->producer->publish(json_encode($push->jsonSerialize()));
        } catch (\Exception $e) {
            throw new PushException($e->getMessage(), $e->getCode(), $e);
        }

        // TODO We should add an option to handle Rabbitmq's RPC messages and return STATUS_SENT when the message was actually sent
        return new PushResult($push, PushResult::STATUS_SENDING);
    }
}
