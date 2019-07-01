<?php

namespace Beapp\Push\Core\Transport;

use Beapp\Push\Core\Push;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class NoopPushTransportTest extends TestCase
{
    public function testSendPush()
    {
        $push = new Push('foo', 'bar', null, ['recipient']);

        $mockedLogger = $this->createMock(LoggerInterface::class);
        $transport = new NoopPushTransport($mockedLogger);

        $result = $transport->sendPush($push);

        $this->assertNull($result);
    }
}
