<?php

namespace Gamma\Pushpin\PushpinBundle\Response\Encoder;

use Gamma\Pushpin\PushpinBundle\Dto\WebSocketEventsDto;
use Gamma\Pushpin\PushpinBundle\Messages\GammaWebSocketMessage;
use GripControl\GripControl;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

class WebSocketOverHttpEncoder implements ResponseEncoderInterface
{
    const DEFAULT_STATUS_CODE = Response::HTTP_OK;
    const CONTENT_TYPE_WS_EVENTS = 'application/websocket-events';

    const KEY_DTO = 'dto';
    const KEY_STATUS_CODE = 'code';

    /**
     * @return string
     */
    public function format(): string
    {
        return GammaWebSocketMessage::FORMAT;
    }

    /**
     * Arguments:
     *  1 - WebSocketEventsDto returned from Controller action
     *  2 - Response Status code (optional) - default is 200.
     *
     * First argument is WebSocketEventsDto,
     *
     * @param array ...$args
     *
     * @return Response
     */
    public function encode(...$args): Response
    {
        $parameters = $this->resolveArgs($args);

        $encodedEvents = GripControl::encode_websocket_events(
            $parameters->get(self::KEY_DTO)->webSocketEvents
        );

        $response = new Response(
            $encodedEvents,
            $parameters->get(self::KEY_STATUS_CODE),
            [
                'content-type' => self::CONTENT_TYPE_WS_EVENTS,
                'Sec-WebSocket-Extensions' => 'grip; message-prefix=""',
            ]
        );

        return $response;
    }

    /**
     * @param array $args
     *
     * @return ParameterBag
     */
    private function resolveArgs(array $args)
    {
        $dto = $args[0] ?? new WebSocketEventsDto();
        $code = $args[1] ?? self::DEFAULT_STATUS_CODE;

        return new ParameterBag([
            self::KEY_DTO => $dto,
            self::KEY_STATUS_CODE => $code,
        ]);
    }
}
