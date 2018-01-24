<?php

namespace Gamma\Pushpin\PushpinBundle\Services;

use Gamma\Pushpin\PushpinBundle\Interfaces\PushpinChannelInterface;
use Gamma\Pushpin\PushpinBundle\Messages\GammaHttpStreamMessage;
use Gamma\Pushpin\PushpinBundle\Messages\GammaWebSocketMessage;
use Gamma\Pushpin\PushpinBundle\Messages\PublishableInterface;
use GripControl\GripControl;
use GripControl\GripPubControl;

class MessagePublisher implements MessagePublisherInterface
{
    /** @var GripPubControl */
    private $gripPubControl;

    /**
     * @param string $pushpinControlUri
     */
    public function setPushpinControlUri(string $pushpinControlUri)
    {
        //TODO: refactor to use DI
        $this->setGripPubControl(
            new GripPubControl(
                GripControl::parse_grip_uri($pushpinControlUri)
            )
        );
    }

    /**
     * @param GripPubControl $gripPubControl
     */
    public function setGripPubControl(GripPubControl $gripPubControl)
    {
        $this->gripPubControl = $gripPubControl;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(string $channelName, PublishableInterface $message)
    {
        $this->gripPubControl->publish($channelName, $message);
    }

    /**
     * @param PushpinChannelInterface $channel
     * @param string                  $message
     */
    public function publishWebSocketMessage(
        PushpinChannelInterface $channel,
        string $message
    ) {
        $wsMessage = GammaWebSocketMessage::build($message);

        $this->publish($channel->getChannelName(), $wsMessage);
    }

    /**
     * @param PushpinChannelInterface $channel
     * @param string                  $message
     * @param bool                    $closeConnection
     */
    public function publishHttpStreamMessage(
        PushpinChannelInterface $channel,
        string $message,
        bool $closeConnection = false
    ) {
        $httpMessage = GammaHttpStreamMessage::build(
            $message,
            $closeConnection
        );

        $this->publish($channel->getChannelName(), $httpMessage);
    }
}
