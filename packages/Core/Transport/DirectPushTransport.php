<?php

namespace Beapp\Push\Core\Transport;

use Beapp\Push\Core\Client\PushClient;
use Beapp\Push\Core\Push;
use Beapp\Push\Core\PushException;

/**
 * Class DirectPushTransport
 * @package Beapp\Push\Core\Transport
 */
class DirectPushTransport implements PushTransport
{
    /** @var PushClient $client */
    private $client;

    /**
     * @param PushClient $client
     */
    public function __construct(PushClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param Push $push
     * @return mixed The result of the sending
     * @throws PushException
     */
    public function sendPush(Push $push)
    {
        return $this->client->sendPush($push);
    }
}
