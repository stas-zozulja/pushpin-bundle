<?php

namespace Gamma\Pushpin\PushpinBundle\Services\Factories;

use Gamma\Pushpin\PushpinBundle\Events\CloseEvent;
use Gamma\Pushpin\PushpinBundle\Interfaces\Events\CloseEventInterface;
use GripControl\WebSocketEvent;

class CloseEventFactory extends AbstractEventFactory
{
    /**
     * {@inheritdoc}
     */
    public function getEvent(WebSocketEvent $webSocketEvent, $format = null)
    {
        $this->ensureCanBeCreated($webSocketEvent);

        return new CloseEvent($webSocketEvent->type, $webSocketEvent->content);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormat()
    {
        return CLoseEventInterface::EVENT_TYPE;
    }
}
