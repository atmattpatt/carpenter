<?php

namespace Carpenter;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionMethod;

class FactoryParser
{
    private $class;

    public function __construct(ReflectionClass $class)
    {
        $this->class = $class;
    }

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

    private function getNewFactory()
    {
        return $this->class->newInstance();
    }

    private function getTargetClass()
    {
        $reader = new AnnotationReader();
        $factory = $reader->getClassAnnotation($this->class, '\Carpenter\Annotation\Factory');

        return $factory->targetClass;
    }

    private function getModifiers()
    {
        return array_filter($this->class->getMethods(), array($this, 'isModifier'));
    }

    private function isModifier(ReflectionMethod $method)
    {
        $reader = new AnnotationReader();

        return $reader->getMethodAnnotation($method, '\Carpenter\Annotation\Modifier') !== null;
    }

    public function getDeferreds()
    {
        return array_filter($this->class->getMethods(), array($this, 'isDeferred'));
    }

    public function isDeferred(ReflectionMethod $method)
    {
        return $this->class->hasProperty($method->name);
    }
}
