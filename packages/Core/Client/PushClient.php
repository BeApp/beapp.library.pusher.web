<?php

namespace Beapp\Push\Core\Client;

use Beapp\Push\Core\Push;
use Beapp\Push\Core\PushException;
use Beapp\Push\Core\PushResult;

/**
 * Interface PushClient
 * @package Beapp\Push\Core\Push
 */
interface PushClient
{
    /**
     * @param Push $push
     *
     * @return PushResult The result of the sending
     * @throws PushException
     */
    public function sendPush(Push $push): PushResult;
}
