<?php

namespace Beapp\Push\Core\Transport;

use Beapp\Push\Core\Push;
use Psr\Log\LoggerInterface;

/**
 * Only log the given {@link Push}.
 * This is useful in development environment to prevent spamming users
 */
class NoopPushTransport implements PushTransport
{
    /** @var LoggerInterface $logger */
    private $logger;

    /**
     * NoopPushTransport constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function sendPush(Push $push): Push
    {
        $this->logger->debug("NOOP sending push", ['push' => $push]);

        return $push;
    }
}
