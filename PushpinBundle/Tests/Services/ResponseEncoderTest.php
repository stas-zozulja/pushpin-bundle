<?php

namespace PushpinBundle\Tests\Services;

use Gamma\Pushpin\PushpinBundle\Dto\WebSocketEventsDto;
use Gamma\Pushpin\PushpinBundle\Services\ResponseEncoder;
use GripControl\WebSocketEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ResponseEncoderTest extends TestCase
{
    /**
     * @covers \Gamma\Pushpin\PushpinBundle\Services\ResponseEncoder::encodeToResponse()
     */
    public function testEncodeToResponse()
    {
        $subject = new ResponseEncoder();

        $dto = new WebSocketEventsDto();
        $dto->connectionId = 'connection-id';
        $dto->webSocketEvents = [new WebSocketEvent('TEXT', 'hello world!')];

        $response = $subject->encodeToResponse($dto, 200);

        static::assertInstanceOf(Response::class, $response);
        static::assertSame(200, $response->getStatusCode());
        static::assertSame("TEXT c\r\nhello world!\r\n", $response->getContent());
    }
}
