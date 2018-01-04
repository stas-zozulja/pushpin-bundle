<?php

namespace Gamma\Pushpin\PushpinBundle\Tests\Services\Factories;

use Gamma\Pushpin\PushpinBundle\Services\Factories\CloseEventFactory;
use GripControl\WebSocketEvent;
use PHPUnit\Framework\TestCase;

class CloseEventFactoryTest extends TestCase
{
    /**
     * @var CloseEventFactory
     */
    private static $instance;

    public static function setUpBeforeClass()
    {
        self::$instance = new CloseEventFactory();
    }

    /**
     * @covers \Gamma\Pushpin\PushpinBundle\Services\Factories\CloseEventFactory::getEvent()
     */
    public function testGetEventSuccess()
    {
        $event = new WebSocketEvent('CLOSE');
        $closeEvent = self::$instance->getEvent($event);

        static::assertInstanceOf('Gamma\Pushpin\PushpinBundle\Events\CloseEvent', $closeEvent);
    }

    /**
     * @expectedException \Gamma\Pushpin\PushpinBundle\Exceptions\Factory\UnsupportedEventTypeException
     * @covers \Gamma\Pushpin\PushpinBundle\Services\Factories\CloseEventFactory::getEvent()
     * @dataProvider getWrongTypeData
     *
     * @param $type
     */
    public function testGetEventWrongType($type)
    {
        $this->setExpectedExceptionFromAnnotation();
        $event = new WebSocketEvent($type);

        self::$instance->getEvent($event);
    }

    /**
     * @return array
     */
    public function getWrongTypeData()
    {
        return [
            ['OPEN'],
            ['TEXT'],
            ['PING'],
            ['DISCONNECT'],
            ['UNKNOWN'],
        ];
    }

    /**
     * @covers \Gamma\Pushpin\PushpinBundle\Services\Factories\OpenEventFactory::getFormat()
     */
    public function testGetFormat()
    {
        static::assertEquals(
            'CLOSE',
            self::$instance->getFormat()
        );
    }
}
