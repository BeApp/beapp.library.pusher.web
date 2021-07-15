<?php

namespace Beapp\Push\Core;

use Beapp\Push\Core\Template\PushTemplate;
use Beapp\Push\Core\Transport\NoopPushTransport;
use Beapp\Push\Core\Transport\PushTransport;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class PushService
 * @package Beapp\Push\Core
 */
class PushService
{
    /** @var LoggerInterface */
    private $logger;
    /** @var PushTransport $pushTransport */
    private $pushTransport;
    /** @var RouterInterface $router */
    private $router;
    /** @var TranslatorInterface $translator */
    private $translator;

    /**
     * @param LoggerInterface $logger
     * @param PushTransport $pushTransport
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     * @param bool $enabled
     */
    public function __construct(
        LoggerInterface $logger,
        PushTransport $pushTransport,
        RouterInterface $router,
        TranslatorInterface $translator,
        bool $enabled
    )
    {
        $this->logger = $logger;
        $this->pushTransport = $enabled ? $pushTransport : new NoopPushTransport($logger);
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * Build a {@link Push} instance from the given {@link PushTemplate} and dispatch it to the configured {@link PushTransport}.
     * The {@link PushTransport} offers an abstraction to send a push thought different channel.
     *
     * @param PushTemplate $pushTemplate
     * @param array|null $recipientsTokens
     *
     * @return PushResult
     * @throws PushException
     */
    public function sendPush(PushTemplate $pushTemplate, array $recipientsTokens = null): PushResult
    {
        $push = $pushTemplate->build($this->router, $this->translator);

        $this->logger->info('Sending {type} push', [
            'type' => get_class($pushTemplate),
            'payload' => $push->jsonSerialize(),
            'recipients' => $recipientsTokens
        ]);

        $push->setRecipientsTokens($recipientsTokens);

        try {
            $result = $this->pushTransport->sendPush($push);

            $this->logger->debug("Push sent", [
                'result' => $result
            ]);

            return $result;
        } catch (PushException $e) {
            $this->logger->error("Couldn't send push", [
                'push' => $push,
                "error" => $e
            ]);
            throw $e;
        }
    }
}
