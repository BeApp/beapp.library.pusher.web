<?php

namespace Beapp\Push\Core\Transport;

use Beapp\Push\Core\Client\PushClient;
use Beapp\Push\Core\Push;
use Beapp\Push\Core\PushException;
use Beapp\Push\Core\PushResult;
use PHPUnit\Framework\TestCase;

class DirectPushTransportTest extends TestCase
{
    public function testSendPush()
    {
        $push = new Push('foo', 'bar', null, ['token']);

        $pushClient = $this->createMock(PushClient::class);
        $pushClient->expects($this->any())
                    ->method('sendPush')
                    ->willReturn(new PushResult($push, PushResult::STATUS_SENT));

        $pushTransport = new DirectPushTransport($pushClient);

        $result = $pushTransport->sendPush($push);

        $this->assertNotNull($result);
        $this->assertEquals(PushResult::STATUS_SENT, $result->getStatus());
    }

    public function testSendPush_throwException()
    {
        $push = new Push('foo', 'bar', null, ['token']);

        $pushClient = $this->createMock(PushClient::class);
        $pushClient->expects($this->any())
                    ->method('sendPush')
                    ->willThrowException(new PushException());

        $pushTransport = new DirectPushTransport($pushClient);

        $this->expectException(PushException::class);

        $result = $pushTransport->sendPush($push);

        $this->assertEquals('Sent', $result);
    }
}
