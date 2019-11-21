<?php

namespace Beapp\Push\Core;

/**
 * Class PushResult
 * @package Beapp\Push\Core
 */
class PushResult
{
    const STATUS_SENDING = "sending";
    const STATUS_SENT = "sent";
    const STATUS_ERROR = "error";
    const STATUS_IGNORED = "ignored";

    /** @var Push */
    private $push;
    /** @var string */
    private $status;
    /** @var mixed|null */
    private $extras;
    /** @var string|null */
    private $errorMessage;

    /**
     * PushResult constructor.
     * @param Push $push
     * @param string $status
     * @param mixed|null $extras
     * @param string|null $errorMessage
     */
    public function __construct(Push $push, string $status, $extras = null, ?string $errorMessage = null)
    {
        $this->push = $push;
        $this->status = $status;
        $this->extras = $extras;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return Push
     */
    public function getPush(): Push
    {
        return $this->push;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return mixed|null
     */
    public function getExtras()
    {
        return $this->extras;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

}
