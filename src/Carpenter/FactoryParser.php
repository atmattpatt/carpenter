<?php

namespace Carpenter;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionMethod;

/**
 * Parses factory definitions to create a template
 */
class FactoryParser
{
    /**
     * A reflection of the factory class
     * @var ReflectionClass
     */
    private $class;

    /**
     * Constructs a new FactoryParser
     *
     * @param ReflectionClass $class A reflection of the factory class
     */
    public function __construct(ReflectionClass $class)
    {
        $this->class = $class;
    }

    /**
     * Get a template for building fixtures from the given factory
     *
     * @return Carpenter\Template
     */
    public function getTemplate()
    {
        $template = new Template($this->getNewFactory());

        foreach ($this->getModifiers() as $modifier) {
            $template->addModifier($modifier->name);
        }

        foreach ($this->getDeferreds() as $deferred) {
            $template->addDeferred($deferred->name);
        }

        $template->setTargetClass($this->getTargetClass());

        return $template;
    }

    /**
     * Gets a new instance of the factory class
     *
     * @return mixed An instance of the factory class
     */
    private function getNewFactory()
    {
        return $this->class->newInstance();
    }

    /**
     * Gets the name of the fixture class to be built
     *
     * @return string
     */
    private function getTargetClass()
    {
        $reader = new AnnotationReader();
        $factory = $reader->getClassAnnotation($this->class, '\Carpenter\Annotation\Factory');

        return $factory->targetClass;
    }

    /**
     * Gets all of the modifiers defined by the factory
     *
     * @return array A list of methods which act as modifiers
     */
    private function getModifiers()
    {
        return array_filter($this->class->getMethods(), array($this, 'isModifier'));
    }

    /**
     * Determine if a given method acts as a modifier
     *
     * A method acts as a modifier if it has the @Modifier annotation
     *
     * @param ReflectionMethod $method The method to examine
     * @return bool
     */
    private function isModifier(ReflectionMethod $method)
    {
        $reader = new AnnotationReader();

        return $reader->getMethodAnnotation($method, '\Carpenter\Annotation\Modifier') !== null;
    }

    /**
     * Gets all of the deferred properties defined by the factory
     *
     * @return array A list of methods which act as deferred properties
     */
    public function getDeferreds()
    {
        return array_filter($this->class->getMethods(), array($this, 'isDeferred'));
    }

    /**
     * Determine if a given method acts as a deferred property
     *
     * A method acts as a deferred property if there exists a public instance property of the same name
     *
     * @param ReflectionMethod $method The method to examine
     * @return bool
     */
    public function isDeferred(ReflectionMethod $method)
    {
        return $this->class->hasProperty($method->name);
    }
}
