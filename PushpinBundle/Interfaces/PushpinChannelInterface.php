<?php

namespace Gamma\Pushpin\PushpinBundle\Interfaces;

interface PushpinChannelInterface
{
    /**
     * Name of channel.
     *
     * @return string
     */
    public function getChannelName();
}
