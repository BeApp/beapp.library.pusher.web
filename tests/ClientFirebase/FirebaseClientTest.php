<?php

namespace Beapp\Push\Client\Firebase;

use Beapp\Push\Core\Push;
use Beapp\Push\Core\PushException;
use Beapp\Push\Core\PushResult;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class FirebaseClientTest extends TestCase
{
    public function testSendPush_sent()
    {
        $firebaseClient = new FirebaseClient('key', $this->buildGuzzleClientMockWithResponse());

        $push = new Push('title', 'body', null, ['token']);

        $result = $firebaseClient->sendPush($push);

        $this->assertNotNull($result);
        $this->assertEquals(PushResult::STATUS_SENT, $result->getStatus());
    }

    public function testSendPush_sentWithTopic()
    {
        $firebaseClient = new FirebaseClient('key', $this->buildGuzzleClientMockWithResponse());

        $push = new Push('title', 'body', null, null);
        $push->setRecipientTopic('topic');

        $result = $firebaseClient->sendPush($push);

        $this->assertNotNull($result);
        $this->assertEquals(PushResult::STATUS_SENT, $result->getStatus());
    }

    public function testSendPush_throwException()
    {
        $guzzleClient = $this->createMock(Client::class);

        $guzzleClient->expects($this->once())
            ->method('__call')
            ->willThrowException(new \Exception());

        $push = new Push('title', 'body', null, ['token']);
        $firebaseClient = new FirebaseClient('key', $guzzleClient);

        $this->expectException(PushException::class);

        $firebaseClient->sendPush($push);
    }

    public function testSendPush_hasNoRecipientsThrowException()
    {
        $guzzleClient = $this->createMock(Client::class);

        $guzzleClient->expects($this->never())
            ->method('__call');

        $push = new Push('title', 'body', null, null);
        $firebaseClient = new FirebaseClient('key', $guzzleClient);

        $this->expectException(PushException::class);

        $firebaseClient->sendPush($push);
    }

    public function getFirebaseClient(): FirebaseClient
    {
        $guzzleClient = $this->createMock(Client::class);

        return new FirebaseClient('key', $guzzleClient);
    }

    protected function buildGuzzleClientMockWithResponse(): Client
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn("{\"response\": \"ok\"}");

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream);

        $guzzleClient = $this->createMock(Client::class);

        $guzzleClient->expects($this->once())
            ->method('__call')
            ->willReturn($response);

        return $guzzleClient;
    }
}
