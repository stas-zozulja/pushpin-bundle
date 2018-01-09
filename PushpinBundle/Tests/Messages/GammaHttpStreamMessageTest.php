<?php

namespace Gamma\Pushpin\PushpinBundle\Tests\Messages;

use Gamma\Pushpin\PushpinBundle\Messages\GammaHttpStreamMessage;
use PHPUnit\Framework\TestCase;

class GammaHttpStreamMessageTest extends TestCase
{
    /**
     * @covers \Gamma\Pushpin\PushpinBundle\Messages\GammaHttpStreamMessageTest::export
     */
    public function testCreate()
    {
        $message = new GammaHttpStreamMessage('test content');

        static::assertSame(
            ['formats' => [
                    'http-stream' => [
                        'content' => 'test content',
                    ],
                ],
            ],
            $message->export()
        );
    }
}
