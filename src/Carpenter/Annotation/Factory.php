<?php

namespace Carpenter\Annotation;

use Doctrine\Common\Annotations\Annotation;

/** @Annotation */
class Factory
{
    public $targetClass;

    public function __construct($args)
    {
        $this->targetClass = array_shift($args);
    }
}
