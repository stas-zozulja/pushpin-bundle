<?php

namespace Gamma\Pushpin\PushpinBundle\Configuration;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * Class PushpinResponse.
 *
 * @Annotation
 */
class PushpinResponse extends ConfigurationAnnotation
{
    /**
     * The format of Response. "http-stream" and "ws-message" are supported.
     *
     * @var string
     */
    protected $format;

    /**
     * {@inheritdoc}
     */
    public function getAliasName()
    {
        return 'pushpin_response';
    }

    /**
     * {@inheritdoc}
     */
    public function allowArray()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }
}
