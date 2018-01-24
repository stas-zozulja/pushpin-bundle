<?php

namespace Gamma\Pushpin\PushpinBundle\Messages;

use GripControl\WebSocketMessageFormat;

class GammaWebSocketMessage extends WebSocketMessageFormat implements PublishableInterface
{
    const FORMAT = 'ws-message';

    /**
     * Arguments:
     *  - message content.
     *
     * @param array ...$args
     *
     * @return PublishableInterface
     */
    public static function build(...$args): PublishableInterface
    {
        $content = $args[0] ?? null;

        return new static($content);
    }

    /**
     * @return array
     */
    public function export()
    {
        $result['formats'] = [
            $this->name() => parent::export(),
        ];

        return $result;
    }
}
