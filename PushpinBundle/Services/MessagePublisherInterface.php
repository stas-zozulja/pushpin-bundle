<?php

namespace Gamma\Pushpin\PushpinBundle\Services;

use Gamma\Pushpin\PushpinBundle\Interfaces\PushpinChannelInterface;
use Gamma\Pushpin\PushpinBundle\Messages\PublishableInterface;

interface MessagePublisherInterface
{
    /**
     * Publish a $message to channel in websocket format - "ws-message".
     *
     * @param PushpinChannelInterface $channel
     * @param string                  $message
     */
    public function publishWebSocketMessage(PushpinChannelInterface $channel, string $message);

    /**
     * Publish a $message to channel in HTTP stream format - "http-stream".
     *
     * @param PushpinChannelInterface $channel
     * @param string                  $message
     */
    public function publishHttpStreamMessage(PushpinChannelInterface $channel, string $message);

    /**
     * @param string               $channelName
     * @param PublishableInterface $message
     */
    public function publish(string $channelName, PublishableInterface $message);
}
