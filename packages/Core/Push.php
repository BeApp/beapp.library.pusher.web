<?php

namespace Beapp\Push\Core;

/**
 * Class Push
 * @package Beapp\Push\Core
 */
class Push
{
    /** @var string */
    private $title;
    /** @var string */
    private $body;
    /** @var array|null */
    private $customData;

    /** @var string[]|null */
    private $recipientsTokens;
    /** @var string|null */
    private $recipientTopic;

    // ANDROID/IOS SPECIFIC
    /** @var string|null */
    private $sound;
    /** @var string|null */
    private $clickAction;
    /** @var int|null */
    private $ttlSeconds;

    // ANDROID SPECIFIC
    /** @var string|null */
    private $icon;
    /** @var string|null */
    private $color;
    /** @var string|null */
    private $tag;

    // IOS SPECIFIC
    /** @var integer|null */
    private $badge;

    /**
     * Push constructor.
     * @param string $title
     * @param string $body
     * @param null|string[] $recipientsTokens
     * @param null|array $customData
     */
    public function __construct(string $title, string $body, ?array $customData, array $recipientsTokens = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->customData = $customData;
        $this->recipientsTokens = $recipientsTokens;
    }

    public static function fromDeserializedData($data): Push
    {
        $push = new self(
            $data['title'],
            $data['body'],
            $data['customData'],
            $data['recipientsTokens']
        );

        foreach (['recipientTopic', 'badge', 'icon', 'color', 'sound', 'clickAction', 'tag', 'ttlSeconds'] as $prop) {
            if (array_key_exists($prop, $data) && !is_null($data[$prop])
                && property_exists(self::class, $prop)
            ) {
                $push->{$prop} = $data[$prop];
            }
        }

        return $push;
    }

    public function jsonSerialize(): array
    {
        return array_filter([
            'title' => $this->getTitle(),
            'body' => $this->getBody(),
            'recipientsTokens' => $this->getRecipientsTokens(),
            'recipientTopic' => $this->getRecipientTopic(),
            'badge' => $this->getBadge(),
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
            'sound' => $this->getSound(),
            'clickAction' => $this->getClickAction(),
            'tag' => $this->getTag(),
            'ttl' => $this->getTtlSeconds(),
            'customData' => $this->getCustomData(),
        ], function ($data) {
            return !is_null($data);
        });
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return null|string[]
     */
    public function getRecipientsTokens(): ?array
    {
        return $this->recipientsTokens;
    }

    /**
     * @param null|string[] $recipientsTokens
     */
    public function setRecipientsTokens(?array $recipientsTokens): void
    {
        $this->recipientsTokens = $recipientsTokens;
    }

    /**
     * @return null|string
     */
    public function getRecipientTopic(): ?string
    {
        return $this->recipientTopic;
    }

    /**
     * @param null|string $recipientTopic
     */
    public function setRecipientTopic(?string $recipientTopic): void
    {
        $this->recipientTopic = $recipientTopic;
    }

    /**
     * @return int|null
     */
    public function getBadge(): ?int
    {
        return $this->badge;
    }

    /**
     * @param int|null $badge
     */
    public function setBadge(?int $badge): void
    {
        $this->badge = $badge;
    }

    /**
     * @return null|string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @param null|string $icon
     */
    public function setIcon(?string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return null|string
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param null|string $color
     */
    public function setColor(?string $color): void
    {
        $this->color = $color;
    }

    /**
     * @return null|string
     */
    public function getSound(): ?string
    {
        return $this->sound;
    }

    /**
     * @param null|string $sound
     */
    public function setSound(?string $sound): void
    {
        $this->sound = $sound;
    }

    /**
     * @return null|string
     */
    public function getClickAction(): ?string
    {
        return $this->clickAction;
    }

    /**
     * @param null|string $clickAction
     */
    public function setClickAction(?string $clickAction): void
    {
        $this->clickAction = $clickAction;
    }

    /**
     * @return null|string
     */
    public function getTag(): ?string
    {
        return $this->tag;
    }

    /**
     * @param null|string $tag
     */
    public function setTag(?string $tag): void
    {
        $this->tag = $tag;
    }

    /**
     * @return int|null
     */
    public function getTtlSeconds(): ?int
    {
        return $this->ttlSeconds;
    }

    /**
     * @param int|null $ttlSeconds
     */
    public function setTtlSeconds(?int $ttlSeconds): void
    {
        $this->ttlSeconds = $ttlSeconds;
    }

    /**
     * @return null|array
     */
    public function getCustomData(): ?array
    {
        return $this->customData;
    }

    /**
     * @param null|array $customData
     */
    public function setCustomData(?array $customData): void
    {
        $this->customData = $customData;
    }

}
