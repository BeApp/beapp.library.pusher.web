<?php

namespace Beapp\Push\Core\Transport;

use Beapp\Push\Core\Push;
use Beapp\Push\Core\PushException;

/**
 * Interface PushTransport
 * @package Beapp\Push\Core\Transport
 */
interface PushTransport
{
    /**
     * @param Push $push
     * @return Push
     * @throws PushException
     */
    public function sendPush(Push $push): Push;
}
