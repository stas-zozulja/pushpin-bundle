<?php

namespace Gamma\Pushpin\PushpinBundle\Controller;

use Gamma\Pushpin\PushpinBundle\Dto\WebSocketEventsDto;
use GripControl\GripControl;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GripController.
 *
 * @deprecated Use PushpinResponse annotation instead of calling decode/encode websocket events
 */
class GripController extends Controller
{
    /**
     * @deprecated Use PushpinResponse annotation instead
     *
     * @param WebSocketEventsDto $dto
     * @param int                $statusCode
     *
     * @return Response
     */
    protected function encodeWebSocketEvents(WebSocketEventsDto $dto, $statusCode = Response::HTTP_OK)
    {
        $encodedEvents = GripControl::encode_websocket_events($dto->webSocketEvents);

        $response = new Response(
            $encodedEvents,
            $statusCode,
            [
                'content-type' => 'application/websocket-events',
                'Sec-WebSocket-Extensions' => 'grip; message-prefix=""',
            ]
        );

        return $response;
    }
}
