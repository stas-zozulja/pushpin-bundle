<?php

namespace Gamma\Pushpin\PushpinBundle\Messages;

use GripControl\HttpStreamFormat;

class GammaHttpStreamMessage extends HttpStreamFormat
{
    const FORMAT = 'http-stream';

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
