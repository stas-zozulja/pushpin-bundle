<?php

namespace Gamma\Pushpin\PushpinBundle\Listener;

use Gamma\Pushpin\PushpinBundle\Dto\WebSocketEventsDto;
use Gamma\Pushpin\PushpinBundle\Configuration\PushpinResponse;
use Gamma\Pushpin\PushpinBundle\Services\ResponseEncoder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PushpinResponseListener implements EventSubscriberInterface
{
    /**
     * @var ResponseEncoder
     */
    private $eventsEncoder;

    /**
     * PushpinResponseListener constructor.
     *
     * @param ResponseEncoder $eventsEncoder
     */
    public function __construct(ResponseEncoder $eventsEncoder)
    {
        $this->eventsEncoder = $eventsEncoder;
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $pushpinResponse = $request->attributes->get('_pushpin_response');
        if (!$pushpinResponse instanceof PushpinResponse) {
            return;
        }

        /** @var WebSocketEventsDto $dto */
        $dto = $event->getControllerResult();
        $response = $this->eventsEncoder->encodeToResponse(
            $dto
        );

        $event->setResponse($response);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => 'onKernelView',
        ];
    }
}
