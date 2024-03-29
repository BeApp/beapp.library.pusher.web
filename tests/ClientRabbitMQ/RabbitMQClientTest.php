<?php

namespace Beapp\Push\Client\RabbitMQ;

use Beapp\Push\Core\Push;
use Beapp\Push\Core\PushException;
use Beapp\Push\Core\PushResult;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use PHPUnit\Framework\TestCase;

class RabbitMQClientTest extends TestCase
{
    public function testSendPush_sent()
    {
        $producer = $this->createMock(ProducerInterface::class);
        $producer->expects($this->once())
                ->method('publish');

        $rabbitMQClient = new RabbitMQClient($producer);

        $result = $rabbitMQClient->sendPush(new Push('foo', 'bar', null, ['token']));

        $this->assertNotNull($result);
        $this->assertEquals(PushResult::STATUS_SENDING, $result->getStatus());
    }

    public function testSendPush_throwException()
    {
        $producer = $this->createMock(ProducerInterface::class);
        $producer->expects($this->once())
            ->method('publish')
            ->willThrowException(new \Exception());

        $this->expectException(PushException::class);

        $rabbitMQClient = new RabbitMQClient($producer);
        $rabbitMQClient->sendPush(new Push('foo', 'bar', null, ['token']));
    }
}
