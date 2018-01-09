<?php

namespace PushpinBundle\Tests\Services;

use Gamma\Pushpin\PushpinBundle\Messages\GammaHttpStreamMessage;
use Gamma\Pushpin\PushpinBundle\Messages\GammaWebSocketMessage;
use Gamma\Pushpin\PushpinBundle\Services\MessagePublisher;
use Gamma\Pushpin\PushpinBundle\Tests\Utils\SimpleChannel;
use Gamma\Pushpin\PushpinBundle\Tests\Utils\SimpleHttpStreamChannel;
use GripControl\GripPubControl;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophet;

class MessagePublisherTest extends TestCase
{
    /**
     * @var MessagePublisher
     */
    private $subject;

    /**
     * @var Prophet
     */
    private static $prophet;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$prophet = new Prophet();
    }

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->subject = new MessagePublisher();
        parent::setUp();
    }

    /**
     * @covers \Gamma\Pushpin\PushpinBundle\Services\MessagePublisher::publishWebSocketMessage()
     */
    public function testPublishWebSocketMessage()
    {
        $gripControlProphecy = self::$prophet->prophesize(GripPubControl::class);
        $gripControlProphecy->publish(
            Argument::exact(SimpleChannel::CHANNEL_NAME),
            Argument::that(function ($message) {
                return
                    $message instanceof GammaWebSocketMessage &&
                    'test-message' === $message->content
                ;
            })
        )
            ->shouldBeCalledTimes(1)
        ;

        $gripPubControl = $gripControlProphecy->reveal();
        $this->subject->setGripPubControl($gripPubControl);

        $channel = new SimpleChannel();
        static::assertSame('test-channel', $channel->getChannelName());

        $this->subject->publishWebSocketMessage(
            $channel,
            'test-message'
        );

        $gripControlProphecy->checkProphecyMethodsPredictions();
    }

    /**
     * @covers \Gamma\Pushpin\PushpinBundle\Services\MessagePublisher::publishHttpStreamMessage()
     */
    public function testPublishHttpStreamMessage()
    {
        $gripControlProphecy = self::$prophet->prophesize(GripPubControl::class);
        $gripControlProphecy->publish(
            Argument::exact(SimpleChannel::CHANNEL_NAME),
            Argument::that(function ($message) {
                return
                    $message instanceof GammaHttpStreamMessage &&
                    'test-message' === $message->content
                ;
            })
        )
            ->shouldBeCalledTimes(1)
        ;

        $gripPubControl = $gripControlProphecy->reveal();
        $this->subject->setGripPubControl($gripPubControl);

        $channel = new SimpleHttpStreamChannel();
        static::assertSame('test-channel', $channel->getChannelName());

        $this->subject->publishHttpStreamMessage(
            $channel,
            'test-message'
        );

        $gripControlProphecy->checkProphecyMethodsPredictions();
    }
}
