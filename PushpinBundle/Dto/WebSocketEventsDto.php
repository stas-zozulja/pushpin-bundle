<?php

namespace Gamma\Pushpin\PushpinBundle\Dto;

class WebSocketEventsDto
{
    /**
     * @var string
     */
    public $connectionId;

    /**
     * @var array
     */
    public $webSocketEvents = [];
}
