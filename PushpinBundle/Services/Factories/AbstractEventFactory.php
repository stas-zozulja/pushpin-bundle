<?php

namespace Gamma\Pushpin\PushpinBundle\Services\Factories;

use Gamma\Pushpin\PushpinBundle\Exceptions\Factory\UnsupportedEventTypeException;
use Gamma\Pushpin\PushpinBundle\Interfaces\Factory\EventFactoryInterface;
use GripControl\WebSocketEvent;

abstract class AbstractEventFactory implements EventFactoryInterface
{
    /**
     * @param WebSocketEvent $event
     *
     * @throws UnsupportedEventTypeException
     */
    protected function ensureCanBeCreated(WebSocketEvent $event)
    {
        if (static::getFormat() !== $event->type) {
            throw new UnsupportedEventTypeException($this, $event);
        }
    }
}
