<?php

namespace Gamma\Pushpin\PushpinBundle\Tests\Messages;

use Gamma\Pushpin\PushpinBundle\Messages\GammaWebSocketMessage;
use PHPUnit\Framework\TestCase;

class GammaWebSocketMessageTest extends TestCase
{
    /**
     * @covers \Gamma\Pushpin\PushpinBundle\Messages\GammaWebSocketMessage::export
     */
    public function testCreate()
    {
        $message = GammaWebSocketMessage::build('test content');

        static::assertInstanceOf(GammaWebSocketMessage::class, $message);
        static::assertSame(
            ['formats' => [
                'ws-message' => [
                        'content' => 'test content',
                    ],
                ],
            ],
            $message->export()
        );
    }
}
