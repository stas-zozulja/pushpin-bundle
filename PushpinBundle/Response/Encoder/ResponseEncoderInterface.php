<?php

namespace Gamma\Pushpin\PushpinBundle\Response\Encoder;

use Symfony\Component\HttpFoundation\Response;

interface ResponseEncoderInterface
{
    /**
     * @param array ...$args
     *
     * @return Response
     */
    public function encode(...$args): Response;

    public function format(): string;
}
