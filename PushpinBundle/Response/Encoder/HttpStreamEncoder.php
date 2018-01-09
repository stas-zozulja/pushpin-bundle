<?php

namespace Gamma\Pushpin\PushpinBundle\Response\Encoder;

use Gamma\Pushpin\PushpinBundle\Dto\HttpStreamDto;
use Gamma\Pushpin\PushpinBundle\Messages\GammaHttpStreamMessage;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

class HttpStreamEncoder implements ResponseEncoderInterface
{
    const DEFAULT_STATUS_CODE = Response::HTTP_OK;
    const CONTENT_TYPE = 'text/plain';

    const KEY_DTO = 'dto';
    const KEY_STATUS_CODE = 'code';

    /**
     * @return string
     */
    public function format(): string
    {
        return GammaHttpStreamMessage::FORMAT;
    }

    /**
     * Arguments:
     *  1 - HttpStreamDto returned from Controller action
     *  2 - Response Status code (optional) - default 200.
     *
     * @param array ...$args
     *
     * @return Response
     */
    public function encode(...$args): Response
    {
        $parameters = $this->resolveArgs($args);

        $response = Response::create(
            '',
            $parameters->get(self::KEY_STATUS_CODE),
            [
                'Content-Type' => self::CONTENT_TYPE,
                'Grip-Hold' => 'stream',
                'Grip-Channel' => $parameters->get(self::KEY_DTO)->channelName,
            ]
        );

        return $response;
    }

    /**
     * @param array $args
     *
     * @return ParameterBag
     */
    private function resolveArgs(array $args)
    {
        $channel = $args[0] ?? new HttpStreamDto();
        $code = $args[1] ?? self::DEFAULT_STATUS_CODE;

        return new ParameterBag([
            self::KEY_DTO => $channel,
            self::KEY_STATUS_CODE => $code,
        ]);
    }
}
