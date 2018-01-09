<?php

namespace Gamma\Pushpin\PushpinBundle\Tests\Services\Events\Json;

use Gamma\Pushpin\PushpinBundle\Services\Events\Json\EventFactory;
use Gamma\Pushpin\PushpinBundle\Services\Events\Json\EventParser;
use Gamma\Pushpin\PushpinBundle\Services\Events\Json\EventSerializer;
use GripControl\WebSocketEvent;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class JsonEventFactoryTest extends KernelTestCase
{
    /**
     * @var EventFactory
     */
    private $instance;

    public function setUp()
    {
        $this->instance = new EventFactory(
            new EventParser(),
            new EventSerializer()
        );

        $this->instance->configure(
            'Gamma\Pushpin\PushpinBundle\Tests\Utils\Events',
                ['testAction' => ['class' => 'SimpleJsonEvent']]
            );
    }

    /**
     * @covers \Gamma\Pushpin\PushpinBundle\Services\Events\Json\EventFactory::getEvent
     */
    public function testGetJsonEvents()
    {
        $event = new WebSocketEvent(
            'TEXT',
            'testAction:{
                "string":"test string",
                "bool":true,
                "int":150,
                "float":150.9999,
                "array": {
                    "key":"value"
                }
            }'
        );
        $jsonEvent = $this->instance->getEvent($event);

        static::assertInstanceOf('Gamma\Pushpin\PushpinBundle\Tests\Utils\Events\SimpleJsonEvent', $jsonEvent);
        static::assertEquals('testAction', $jsonEvent->getName());
        static::assertEquals('test string', $jsonEvent->string);
        static::assertEquals(true, $jsonEvent->bool);
        static::assertEquals(150, $jsonEvent->int);
        static::assertEquals(150.9999, $jsonEvent->float);
        static::assertEquals(['key' => 'value'], $jsonEvent->array);
        static::assertTrue($jsonEvent->hasSubtypes());
    }
}
