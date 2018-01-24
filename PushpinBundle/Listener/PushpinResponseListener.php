<?php

namespace Gamma\Pushpin\PushpinBundle\Listener;

use Gamma\Pushpin\PushpinBundle\Configuration\PushpinResponse;
use Gamma\Pushpin\PushpinBundle\Dto\HttpStreamDto;
use Gamma\Pushpin\PushpinBundle\Dto\WebSocketEventsDto;
use Gamma\Pushpin\PushpinBundle\Response\Encoder\ResponseEncoderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PushpinResponseListener implements EventSubscriberInterface
{
    /**
     * @var array|ResponseEncoderInterface[]
     */
    private $encoders;

    /**
     * @param ResponseEncoderInterface $encoder
     */
    public function addEncoder(ResponseEncoderInterface $encoder)
    {
        $this->encoders[$encoder->format()] = $encoder;
    }

    /**
     * @param string $format
     *
     * @return ResponseEncoderInterface
     */
    private function getEncoder(string $format)
    {
        $encoder = $this->encoders[$format] ?? null;

        if (null === $encoder) {
            throw new \RuntimeException(
                sprintf('Unknown response encoder for format "%s"', $format)
            );
        }

        return $encoder;
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

        /** @var WebSocketEventsDto|HttpStreamDto $dto */
        $dto = $event->getControllerResult();
        $response = $this->getEncoder($pushpinResponse->getFormat())->encode($dto);

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
