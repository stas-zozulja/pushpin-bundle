<?php

namespace Gamma\Pushpin\PushpinBundle\Interfaces\Factory;

use Gamma\Pushpin\PushpinBundle\Events\Base\AbstractEvent;
use GripControl\WebSocketEvent;

interface EventFactoryInterface
{
    /**
     * @return string
     */
    public function getFormat();

    /**
     * @param WebSocketEvent $webSocketEvent
     * @param null           $format
     *
     * @return AbstractEvent
     */
    public function getEvent(WebSocketEvent $webSocketEvent, $format = null);
}
