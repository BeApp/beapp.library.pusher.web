<?php

namespace Beapp\Push\Core;

use Beapp\Push\Core\Template\PushTemplate;
use Beapp\Push\Core\Transport\DirectPushTransport;
use Beapp\Push\Core\Transport\PushTransport;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class PushServiceTest extends TestCase
{
    public function testSendPush_emptyRecipients()
    {
        $pushTransportMock = $this->createMock(DirectPushTransport::class);
        $pushTransportMock->expects($this->once())
                        ->method('sendPush');

        $pushTemplate = $this->getPushTemplate();
        $pushService = $this->getPushService($pushTransportMock);

        $pushService->sendPush($pushTemplate, []);
    }

    public function testSendPush_sentWithTopic()
    {
        $pushTransportMock = $this->createMock(DirectPushTransport::class);
        $pushTransportMock->expects($this->once())
                        ->method('sendPush');

        $push = new Push('foo', 'bar', null);
        $push->setRecipientTopic('topic');
        $pushTemplate = $this->getPushTemplate($push);
        $pushService = $this->getPushService($pushTransportMock);

        $pushService->sendPush($pushTemplate, []);
    }

    public function testSendPush_throwException()
    {
        $pushTransportMock = $this->createMock(DirectPushTransport::class);
        $pushTransportMock->expects($this->once())
            ->method('sendPush')
            ->willThrowException(new PushException());

        $pushTemplate = $this->getPushTemplate();
        $pushService = $this->getPushService($pushTransportMock);

        $this->expectException(PushException::class);

        $pushService->sendPush($pushTemplate, ['token']);
    }

    public function testSendPush_sent()
    {
        $push = new Push('foo', 'bar', null, ['recipient']);

        $pushTransportMock = $this->createMock(DirectPushTransport::class);
        $pushTransportMock->expects($this->once())
            ->method('sendPush')
            ->willReturn(new PushResult($push, PushResult::STATUS_SENT));

        $pushTemplate = $this->getPushTemplate();
        $pushService = $this->getPushService($pushTransportMock);

        $push = $pushService->sendPush($pushTemplate, ['token']);

        $this->assertNotNull($push);
        $this->assertEquals(PushResult::STATUS_SENT, $push->getStatus());
    }

    public function getPushTemplate(Push $push = null)
    {
        $push = $push ?? new Push('foo', 'bar', null);

        $pushTemplate = $this->createMock(PushTemplate::class);

        $pushTemplate->expects($this->any())
                    ->method('build')
                    ->willReturn($push);

        return $pushTemplate;
    }

    public function getPushService(PushTransport $pushTransport)
    {
        $logger = $this->createMock(LoggerInterface::class);
        $router = $this->createMock(RouterInterface::class);
        $translator = $this->createMock(TranslatorInterface::class);

        return new PushService($logger, $pushTransport, $router, $translator, true);
    }
}
