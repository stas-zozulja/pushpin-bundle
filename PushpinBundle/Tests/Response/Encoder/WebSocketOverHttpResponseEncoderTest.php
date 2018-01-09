<?php

namespace PushpinBundle\Tests\Response\Encoder;

use Gamma\Pushpin\PushpinBundle\Dto\WebSocketEventsDto;
use Gamma\Pushpin\PushpinBundle\Response\Encoder\WebSocketOverHttpEncoder;
use GripControl\WebSocketEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class WebSocketOverHttpResponseEncoderTest extends TestCase
{
    /**
     * @covers \Gamma\Pushpin\PushpinBundle\Response\Encoder\WebSocketOverHttpEncoder::encode()
     */
    public function testEncode()
    {
        $subject = new WebSocketOverHttpEncoder();

        $dto = new WebSocketEventsDto();
        $dto->connectionId = 'connection-id';
        $dto->webSocketEvents = [new WebSocketEvent('TEXT', 'hello world!')];

        $response = $subject->encode($dto);

        static::assertInstanceOf(Response::class, $response);
        static::assertSame(200, $response->getStatusCode());
        static::assertSame("TEXT c\r\nhello world!\r\n", $response->getContent());
    }
}
