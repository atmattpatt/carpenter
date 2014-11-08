<?php

namespace Carpenter\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation denoting that a class is a factory
 * @Annotation
 */
class Factory
{
    /**
     * The name of the class to build
     * @var string
     */
    public $targetClass;

    /**
     * Constructs a new Factory annotation
     *
     * @param array $args
     */
    public function __construct($args)
    {
        $this->targetClass = array_shift($args);
    }
}
