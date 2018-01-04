<?php

namespace Gamma\Pushpin\PushpinBundle\Services\Factories;

use Gamma\Pushpin\PushpinBundle\Events\DisconnectEvent;
use Gamma\Pushpin\PushpinBundle\Interfaces\Events\DisconnectEventInterface;
use GripControl\WebSocketEvent;

class DisconnectEventFactory extends AbstractEventFactory
{
    /**
     * {@inheritdoc}
     */
    public function getEvent(WebSocketEvent $webSocketEvent, $format = null)
    {
        $this->ensureCanBeCreated($webSocketEvent);

        return new DisconnectEvent($webSocketEvent->type, $webSocketEvent->content);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormat()
    {
        return DisconnectEventInterface::EVENT_TYPE;
    }
}
