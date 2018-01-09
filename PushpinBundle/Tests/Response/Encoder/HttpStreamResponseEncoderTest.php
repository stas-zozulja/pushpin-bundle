<?php

namespace PushpinBundle\Tests\Response\Encoder;

use Gamma\Pushpin\PushpinBundle\Dto\HttpStreamDto;
use Gamma\Pushpin\PushpinBundle\Response\Encoder\HttpStreamEncoder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class HttpStreamResponseEncoderTest extends TestCase
{
    /**
     * @covers \Gamma\Pushpin\PushpinBundle\Response\Encoder\WebSocketOverHttpEncoder::encode()
     */
    public function testEncode()
    {
        $subject = new HttpStreamEncoder();

        $dto = new HttpStreamDto();
        $dto->connectionId = 'connection-id';
        $dto->channelName = 'test-channel';

        $response = $subject->encode($dto);

        static::assertInstanceOf(Response::class, $response);
        static::assertSame(200, $response->getStatusCode());
        static::assertSame('', $response->getContent());

        static::assertTrue($response->headers->has('grip-hold'));
        static::assertSame('stream', $response->headers->get('grip-hold'));

        static::assertTrue($response->headers->has('grip-channel'));
        static::assertSame('test-channel', $response->headers->get('grip-channel'));
    }

    /**
     * @covers \Gamma\Pushpin\PushpinBundle\Response\Encoder\WebSocketOverHttpEncoder::format()
     */
    public function testFormat()
    {
        $subject = new HttpStreamEncoder();

        static::assertSame('http-stream', $subject->format());
    }
}
