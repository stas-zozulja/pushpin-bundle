<?php

namespace Gamma\Pushpin\PushpinBundle\Services;

use Gamma\Pushpin\PushpinBundle\Interfaces\HttpStreamChannelInterface;
use Gamma\Pushpin\PushpinBundle\Interfaces\WebSocketChannelInterface;
use Gamma\Pushpin\PushpinBundle\Messages\GammaHttpStreamMessage;
use Gamma\Pushpin\PushpinBundle\Messages\GammaWebSocketMessage;
use GripControl\GripControl;
use GripControl\GripPubControl;

class MessagePublisher
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
     * @param WebSocketChannelInterface $channel
     * @param string                    $message
     */
    public function publishWebSocketMessage(
        WebSocketChannelInterface $channel,
        string $message
    ) {
        $wsMessage = new GammaWebSocketMessage($message);

        $this->gripPubControl->publish($channel->getChannelName(), $wsMessage);
    }

    /**
     * @param HttpStreamChannelInterface $channel
     * @param string                     $message
     * @param bool                       $closeConnection
     */
    public function publishHttpStreamMessage(
        HttpStreamChannelInterface $channel,
        string $message,
        bool $closeConnection = false
    ) {
        $httpMessage = new GammaHttpStreamMessage(
            $message,
            $closeConnection
        );

        $this->gripPubControl->publish($channel->getChannelName(), $httpMessage);
    }
}
