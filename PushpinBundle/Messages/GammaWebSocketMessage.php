<?php

namespace Gamma\Pushpin\PushpinBundle\Messages;

use GripControl\WebSocketMessageFormat;

class GammaWebSocketMessage extends WebSocketMessageFormat
{
    const FORMAT = 'ws-message';

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
