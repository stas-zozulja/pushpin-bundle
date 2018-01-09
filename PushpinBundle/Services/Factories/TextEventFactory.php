<?php

namespace Gamma\Pushpin\PushpinBundle\Services\Factories;

use Gamma\Pushpin\PushpinBundle\Exceptions\Factory\UnsupportedEventTypeException;
use Gamma\Pushpin\PushpinBundle\Interfaces\Events\TextEventInterface;
use Gamma\Pushpin\PushpinBundle\Interfaces\Factory\EventFactoryInterface;
use GripControl\WebSocketEvent;

class TextEventFactory extends AbstractEventFactory
{
    /**
     * @var array
     */
    private $factories;

    /**
     * @param EventFactoryInterface $factory
     */
    public function addFactory(EventFactoryInterface $factory)
    {
        $this->factories[$factory->getFormat()] = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormat()
    {
        return TextEventInterface::EVENT_TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function getEvent(WebSocketEvent $webSocketEvent, $format = null)
    {
        $this->ensureCanBeCreated($webSocketEvent, $format);
        $factory = $this->factories[$format];

        return $factory->getEvent($webSocketEvent);
    }

    /**
     * {@inheritdoc}
     */
    protected function ensureCanBeCreated(WebSocketEvent $event, $format = null)
    {
        if (null === $format) {
            throw new \RuntimeException('Format cannot be null');
        }

        if (false === array_key_exists($format, $this->factories)) {
            throw new \RuntimeException(
                sprintf('Unknown event format "%s"', $format)
            );
        }

        if (TextEventInterface::EVENT_TYPE !== $event->type) {
            throw new UnsupportedEventTypeException($this, $event);
        }
    }
}
