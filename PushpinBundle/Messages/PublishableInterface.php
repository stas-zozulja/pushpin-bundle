<?php

namespace Gamma\Pushpin\PushpinBundle\Messages;

interface PublishableInterface
{
    /**
     * The format of Message. "http-stream" OR "ws-message" are supported.
     *
     * @return string
     */
    public function name();

    /**
     * Factory method.
     *
     * @param array ...$args
     *
     * @return PublishableInterface
     */
    public static function build(...$args): self;
}
