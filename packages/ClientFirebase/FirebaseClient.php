<?php

namespace Beapp\Push\Client\Firebase;

use Beapp\Push\Core\Client\PushClient;
use Beapp\Push\Core\Push;
use Beapp\Push\Core\PushException;
use GuzzleHttp\Client;

class FirebaseClient implements PushClient
{
    const DEFAULT_API_URL = 'https://fcm.googleapis.com/fcm/send';

    /** @var string */
    private $apiKey;

    private $client;

    /**
     * FirebaseClient constructor.
     *
     * @param string $apiKey
     * @param Client $client
     */
    public function __construct(string $apiKey, ?Client $client)
    {
        $this->apiKey = $apiKey;
        $this->client = $client ?? new Client();
    }

    /**
     * @param Push $push
     * @return Push
     * @throws PushException
     */
    public function sendPush(Push $push): Push
    {
        $message = $this->buildMessage($push);
        $message['notification'] = $this->buildNotification($push);

        $this->client->post(
            $this->getApiUrl(),
            [
                'headers' => [
                    'Authorization' => sprintf('key=%s', $this->apiKey),
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($message)
            ]
        );
    }

    /**
     * @param Push $push
     *
     * @return array
     * @throws PushException
     */
    protected function buildMessage(Push $push): array
    {
        $message = [];
        if (!is_null($push->getCustomData())) {
            $message['data'] = $push->getCustomData();
        }
        if (!is_null($push->getTtlSeconds())) {
            $message['time_to_live'] = $push->getTtlSeconds();
        }
        if (!is_null($push->getRecipientTopic())) {
            $message['to'] = $push->getRecipientTopic();
        } elseif (!empty($push->getRecipientsTokens())) {
            if (count($push->getRecipientsTokens()) == 1) {
                $message['to'] = $push->getRecipientsTokens()[0];
            } else {
                $message['registration_ids'] = $push->getRecipientsTokens();
            }
        } else {
            throw new PushException('You must have either a topic or a recipient to send a push');
        }
        return $message;
    }

    /**
     * @param Push $push
     *
     * @return array
     */
    protected function buildNotification(Push $push): array
    {
        $notification = [
            'title' => $push->getTitle(),
            'body' => $push->getBody(),
        ];
        if (!is_null($push->getBadge())) {
            $notification['badge'] = $push->getBadge();
        }
        if (!is_null($push->getIcon())) {
            $notification['icon'] = $push->getIcon();
        }
        if (!is_null($push->getClickAction())) {
            $notification['click_action'] = $push->getClickAction();
        }
        if (!is_null($push->getSound())) {
            $notification['sound'] = $push->getSound();
        }
        if (!is_null($push->getColor())) {
            $notification['color'] = $push->getColor();
        }
        if (!is_null($push->getTag())) {
            $notification['tag'] = $push->getTag();
        }
        return $notification;
    }

    /**
     * @return string
     */
    protected function getApiUrl(): string
    {
        return self::DEFAULT_API_URL;
    }
}
