<?php

namespace Gamma\Pushpin\PushpinBundle\Services\Factories;

use Gamma\Pushpin\PushpinBundle\Events\OpenEvent;
use Gamma\Pushpin\PushpinBundle\Interfaces\Events\OpenEventInterface;
use GripControl\WebSocketEvent;

class OpenEventFactory extends AbstractEventFactory
{
    /**
     * {@inheritdoc}
     */
    public function getEvent(WebSocketEvent $webSocketEvent, $format = null)
    {
        $this->ensureCanBeCreated($webSocketEvent);

        return new OpenEvent($webSocketEvent->type, $webSocketEvent->content);
    }

    /**
     * {@inheritdoc}
     */
    public function getFormat()
    {
        return OpenEventInterface::EVENT_TYPE;
    }
}
