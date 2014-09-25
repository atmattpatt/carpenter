<?php

namespace Carpenter;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class FactoryFinder
{
    private $paths = [];

    public function __construct()
    {
        foreach (Configuration::$factoryPaths as $path) {
            $this->addPath($path);
        }
    }

    public function addPath($path)
    {
        $this->paths[] = $path;
    }

    public function discoverFactories()
    {
        $this->loadFactories();

        return $this->getDefinedFactories();
    }

    public function loadFactories()
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in($this->paths)
            ->name('*Factory.php');

        foreach ($finder as $file) {
            include_once($file);
        }
    }

    public function getDefinedFactories()
    {
        return array_filter(get_declared_classes(), array($this, 'isFactory'));
    }

    private function isFactory($class)
    {
        $reader = new AnnotationReader();

        $reflection = new ReflectionClass($class);
        return $reader->getClassAnnotation($reflection, '\Carpenter\Annotation\Factory') !== null;
    }
}
