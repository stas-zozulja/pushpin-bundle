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
}
