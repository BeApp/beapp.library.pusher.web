<?php

namespace Beapp\Push\Core;

use PHPUnit\Framework\TestCase;

class PushTest extends TestCase
{
    public function testFromDeserializedData()
    {
        $data = [
            'title' => 'Title',
            'body' => 'Body',
            'customData' => ['foo' => 'bar'],
            'recipientsTokens' => ['token'],
            'recipientTopic' => 'Recipient Topic',
            'badge' => 123,
            'icon' => 'Icon',
            'color' => 'Color',
            'sound' => 'Sound',
            'clickAction' => 'Click Action',
            'tag' => 'Tag',
            'ttlSeconds' => 456
        ];

        $push = Push::fromDeserializedData($data);

        $this->assertEquals('Title', $push->getTitle());
        $this->assertEquals('Body', $push->getBody());
        $this->assertIsArray($push->getRecipientsTokens());
        $this->assertIsArray($push->getCustomData());
        $this->assertArrayHasKey('foo', $push->getCustomData());

        $this->assertEquals('Recipient Topic', $push->getRecipientTopic());
        $this->assertEquals(123, $push->getBadge());
        $this->assertEquals('Icon', $push->getIcon());
        $this->assertEquals('Color', $push->getColor());
        $this->assertEquals('Sound', $push->getSound());
        $this->assertEquals('Click Action', $push->getClickAction());
        $this->assertEquals('Tag', $push->getTag());
        $this->assertEquals(456, $push->getTtlSeconds());
    }

    public function testJsonSerialize()
    {
        $push = new Push('Title', 'Body', null, ['token']);

        $data = $push->jsonSerialize();

        $this->assertIsArray($data);

        $this->assertArrayHasKey('title', $data);
        $this->assertEquals('Title', $data['title']);

        $this->assertArrayHasKey('body', $data);
        $this->assertEquals('Body', $data['body']);

        $this->assertArrayNotHasKey('customData', $data);

        $this->assertArrayHasKey('recipientsTokens', $data);
        $this->assertIsArray($data['recipientsTokens']);
    }
}

