<?php

namespace Gamma\Pushpin\PushpinBundle\Messages;

use GripControl\HttpStreamFormat;

class GammaHttpStreamMessage extends HttpStreamFormat implements PublishableInterface
{
    const FORMAT = 'http-stream';

    /**
     * Arguments:
     *  - message content (string).
     *  - close stream (boolean).
     *
     * @param array ...$args
     *
     * @return PublishableInterface
     */
    public static function build(...$args): PublishableInterface
    {
        $content = (string) ($args[0] ?? null);
        $closeStream = (bool) ($args[1] ?? null);

        return new static($content.PHP_EOL, $closeStream);
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
