<?php

namespace Gamma\Pushpin\PushpinBundle\Tests\Messages;

use Gamma\Pushpin\PushpinBundle\Messages\GammaHttpStreamMessage;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\CodeCoverage\Report\PHP;

class GammaHttpStreamMessageTest extends TestCase
{
    /**
     * @covers \Gamma\Pushpin\PushpinBundle\Messages\GammaHttpStreamMessageTest::build
     */
    public function testCreate()
    {
        $message = GammaHttpStreamMessage::build('test content');

        static::assertInstanceOf(GammaHttpStreamMessage::class, $message);
        static::assertFalse($message->close);
        static::assertSame(
            ['formats' => [
                'http-stream' => [
                        'content' => 'test content'.PHP_EOL,
                    ],
                ],
            ],
            $message->export()
        );
    }

    /**
     * @covers \Gamma\Pushpin\PushpinBundle\Messages\GammaHttpStreamMessageTest::build
     */
    public function testCreateClosing()
    {
        $message = GammaHttpStreamMessage::build('test content', true);

        static::assertInstanceOf(GammaHttpStreamMessage::class, $message);
        static::assertTrue($message->close);
        static::assertSame(
            ['formats' => [
                'http-stream' => [
                        'action' => 'close',
                    ],
                ],
            ],
            $message->export()
        );
    }
}
