<?php

namespace Gamma\Pushpin\PushpinBundle\Request\ParamConverter;

use Gamma\Pushpin\PushpinBundle\Dto\WebSocketEventsDto;
use Gamma\Pushpin\PushpinBundle\Interfaces\Events\TextEventInterface;
use Gamma\Pushpin\PushpinBundle\Interfaces\Factory\EventFactoryInterface;
use GripControl\GripControl;
use GripControl\WebSocketEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class WebSocketEventsConverter implements ParamConverterInterface
{
    /**
     * @var array
     */
    private $supportedTextFormats = [
        'json',
    ];

    /**
     * @var EventFactoryInterface
     */
    private $factory;

    /**
     * @param EventFactoryInterface $eventFactory
     */
    public function __construct(EventFactoryInterface $eventFactory)
    {
        $this->factory = $eventFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $events = GripControl::decode_websocket_events($request->getContent());
        $dto = new WebSocketEventsDto();

        $dto->webSocketEvents = $events;
        $dto->connectionId = $request->headers->get('connection-id');

        $format = $configuration->getOptions()['format'];

        $request->attributes->set(
            $configuration->getName(),
            $this->resolveEvents($dto, $format)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        $supports =
            array_key_exists('format', $configuration->getOptions()) &&
            in_array($configuration->getOptions()['format'], $this->supportedTextFormats) &&
            WebSocketEventsDto::class === $configuration->getClass()
        ;

        return $supports;
    }

    /**
     * @param WebSocketEventsDto $dto
     * @param $format
     *
     * @return WebSocketEventsDto
     */
    private function resolveEvents(WebSocketEventsDto $dto, $format)
    {
        /** @var $event WebSocketEvent */
        foreach ($dto->webSocketEvents as $key => $event) {
            if (TextEventInterface::EVENT_TYPE === $event->type) {
                $dto->webSocketEvents[$key] = $this->factory->getEvent($event, $format);
            } else {
                $dto->webSocketEvents[$key] = $this->factory->getEvent($event);
            }
        }

        return $dto;
    }
}
