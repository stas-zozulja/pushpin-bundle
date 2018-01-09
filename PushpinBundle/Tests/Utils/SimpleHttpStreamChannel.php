<?php
/**
 * Created for testing purposes only.
 */

namespace Gamma\Pushpin\PushpinBundle\Tests\Utils;

use Gamma\Pushpin\PushpinBundle\Interfaces\HttpStreamChannelInterface;

class SimpleHttpStreamChannel implements HttpStreamChannelInterface
{
    const CHANNEL_NAME = 'test-channel';

    /**
     * @return string
     */
    public function getChannelName()
    {
        return self::CHANNEL_NAME;
    }
}
