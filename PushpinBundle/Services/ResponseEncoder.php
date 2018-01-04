<?php

namespace Gamma\Pushpin\PushpinBundle\Services;

use Gamma\Pushpin\PushpinBundle\Dto\WebSocketEventsDto;
use GripControl\GripControl;
use Symfony\Component\HttpFoundation\Response;

class ResponseEncoder
{
    const CONTENT_TYPE_WS_EVENTS = 'application/websocket-events';

    /**
     * @param WebSocketEventsDto $dto
     * @param int                $statusCode
     * @param string             $contentType
     *
     * @return Response
     */
    public function encodeToResponse(
        WebSocketEventsDto $dto,
        $statusCode = Response::HTTP_OK,
        $contentType = self::CONTENT_TYPE_WS_EVENTS
    ) {
        $encodedEvents = GripControl::encode_websocket_events($dto->webSocketEvents);

        $response = new Response(
            $encodedEvents,
            $statusCode,
            [
                'content-type' => $contentType,
                'Sec-WebSocket-Extensions' => 'grip; message-prefix=""',
            ]
        );

        return $response;
    }
}
