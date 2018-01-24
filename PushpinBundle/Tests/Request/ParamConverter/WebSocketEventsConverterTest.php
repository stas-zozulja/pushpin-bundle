<?php

namespace  Gamma\Pushpin\PushpinBundle\Tests\Request\ParamConverter;

use Gamma\Pushpin\PushpinBundle\Dto\WebSocketEventsDto;
use Gamma\Pushpin\PushpinBundle\Interfaces\Factory\EventFactoryInterface;
use Gamma\Pushpin\PushpinBundle\Request\ParamConverter\WebSocketEventsConverter;
use GripControl\WebSocketEvent;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class WebSocketEventsConverterTest extends TestCase
{
    const CONVERTER_NAME = 'webSocketEvents';
    const CONNECTION_ID = 'some-id';
    const FORMAT_JSON = 'json';

    /**
     * @var Prophet
     */
    private static $prophet;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$prophet = new Prophet();
    }

    /**
     * @return ObjectProphecy
     */
    private function eventFactoryProphecy()
    {
        $prophecy = (self::$prophet->prophesize(EventFactoryInterface::class))
            ->willImplement(EventFactoryInterface::class)
        ;

        return $prophecy;
    }

    /**
     * @param string $content
     * @param array  $headers
     * @param array  $attributes
     *
     * @return Request
     */
    private function request(string $content, array $headers = [], array $attributes = [])
    {
        $request = Request::create(
            '/target',
            Request::METHOD_POST,
            [],
            [],
            [],
            [],
            $content
        );

        $request->headers = new HeaderBag($headers);
        $request->attributes = new ParameterBag($attributes);

        return $request;
    }

    /**
     * @param string $class
     * @param string $format
     *
     * @return ParamConverter
     */
    private function paramConverter(string $class, string $format)
    {
        $paramConverter = new ParamConverter([
            'name' => self::CONVERTER_NAME,
            'class' => $class,
        ]);

        $paramConverter->setOptions(['format' => $format]);

        return $paramConverter;
    }

    /**
     * @return array
     */
    public function applyData()
    {
        return [
            ["OPEN\r\n", 1],
            ["CLOSE 3\r\n", 1],
            ["DISCONNECT\r\n", 1],
            ["PING\r\n", 1],
            ["PONG\r\n", 1],
            ["BINARY\r\n", 1],
            ["TEXT 5\r\nhello\r\n", 1],
            ["TEXT 5\r\nworld\r\nTEXT 1C\r\nhere is another nice message", 2],
        ];
    }

    /**
     * @covers \Gamma\Pushpin\PushpinBundle\Request\ParamConverter\WebSocketEventsConverter::apply()
     * @dataProvider applyData
     *
     * @param string $requestContent
     * @param int    $eventsCount
     */
    public function testApply(string $requestContent, int $eventsCount)
    {
        $prophecy = $this->eventFactoryProphecy();
        $prophecy->getEvent(
                Argument::type(WebSocketEvent::class),
                Argument::that(function ($argument) {
                    return null === $argument || self::FORMAT_JSON === $argument;
                })
            )
            ->shouldBeCalledTimes($eventsCount)
            ->willReturn(Argument::type(WebSocketEvent::class))
        ;

        $eventFactory = $prophecy->reveal();

        $subject = new WebSocketEventsConverter($eventFactory);
        $request = $this->request($requestContent, ['connection-id' => self::CONNECTION_ID]);

        $subject->apply(
            $request,
            $this->paramConverter(WebSocketEventsDto::class, self::FORMAT_JSON)
        );

        static::assertTrue($request->attributes->has(self::CONVERTER_NAME));

        $dto = $request->attributes->get(self::CONVERTER_NAME);
        static::assertInstanceOf(WebSocketEventsDto::class, $dto);
        static::assertSame(self::CONNECTION_ID, $dto->connectionId);
        static::assertInternalType('array', $dto->webSocketEvents);
        static::assertCount($eventsCount, $dto->webSocketEvents);

        $prophecy->checkProphecyMethodsPredictions();
    }

    /**
     * @return array
     */
    public function supportsData()
    {
        return [
            [WebSocketEventsDto::class, self::FORMAT_JSON, true],
            ['other', 'bson', false],
        ];
    }

    /**
     * @covers \Gamma\Pushpin\PushpinBundle\Request\ParamConverter\WebSocketEventsConverter::supports()
     * @dataProvider supportsData
     *
     * @param string $class
     * @param string $format
     * @param bool   $supports
     */
    public function testSupports(string $class, string $format, bool $supports)
    {
        $subject = new WebSocketEventsConverter($this->eventFactoryProphecy()->reveal());

        static::assertSame(
            $supports,
            $subject->supports($this->paramConverter($class, $format))
        );
    }
}
