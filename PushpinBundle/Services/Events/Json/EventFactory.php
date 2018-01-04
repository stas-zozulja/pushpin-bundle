<?php

namespace Gamma\Pushpin\PushpinBundle\Services\Events\Json;

use Gamma\Pushpin\PushpinBundle\Events\Base\AbstractJsonEvent;
use Gamma\Pushpin\PushpinBundle\Interfaces\Events\TextEventInterface;
use Gamma\Pushpin\PushpinBundle\Interfaces\Factory\EventFactoryInterface;
use GripControl\WebSocketEvent;

class EventFactory implements EventFactoryInterface
{
    /**
     * @var string
     */
    private $baseNamespace = '';

    /**
     * @var array
     */
    private $events = [];

    /**
     * @var EventParser
     */
    private $parser;

    /**
     * @var EventSerializer
     */
    private $serializer;

    /**
     * @param $baseNamespace
     * @param array $events
     */
    public function configure($baseNamespace, array $events)
    {
        $this->baseNamespace = $baseNamespace;
        $this->events = $events;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormat()
    {
        return 'json';
    }

    /**
     * {@inheritdoc}
     */
    public function getEvent(WebSocketEvent $event, $format = null)
    {
        return $this->resolveJsonEvent($event);
    }

    /**
     * @param EventParser     $parser
     * @param EventSerializer $serializer
     */
    public function __construct(EventParser $parser, EventSerializer $serializer)
    {
        $this->parser = $parser;
        $this->serializer = $serializer;
    }

    /**
     * @param $eventName
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    private function getClassByEventName($eventName)
    {
        if (isset($this->events[$eventName]['class'])) {
            return $this->events[$eventName]['class'];
        }

        throw new \RuntimeException(
            sprintf('Undefined WebSocket event "%s"', $eventName)
        );
    }

    /**
     * @param WebSocketEvent $webSocketEvent
     *
     * @return AbstractJsonEvent
     *
     * @throws \RuntimeException
     */
    private function resolveJsonEvent(WebSocketEvent $webSocketEvent)
    {
        if (TextEventInterface::EVENT_TYPE !== $webSocketEvent->type) {
            throw new \RuntimeException(
                sprintf(
                    'Cannot parse event with type "%s". Expected type is "%s"',
                    $webSocketEvent->type,
                    TextEventInterface::EVENT_TYPE
                )
            );
        }

        $eventName = $this->parser->getEventName($webSocketEvent);
        $className = sprintf(
            '%s\%s',
            $this->baseNamespace,
            $this->getClassByEventName($eventName)
        );

        if (class_exists($className)) {
            $event = new $className(
                $webSocketEvent->type,
                $webSocketEvent->content
            );

            $deSerialized = $this->serializer->deserialize($event);

            return $deSerialized;
        }

        throw new \RuntimeException(sprintf('Class "%s" not exists', $className));
    }
}
