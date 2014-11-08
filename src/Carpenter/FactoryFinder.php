<?php

namespace Carpenter;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

/**
 * Utility class to find available factories
 */
class FactoryFinder
{
    /**
     * Filesystem paths to search for factories
     * @var array
     */
    private $paths = [];

    /**
     * Constructs a new FactoryFinder
     */
    public function __construct()
    {
        foreach (Configuration::$factoryPaths as $path) {
            $this->addPath($path);
        }
    }

    /**
     * Add a path to be searched for factories
     *
     * @param string $path The filesystem path to search
     */
    public function addPath($path)
    {
        $this->paths[] = $path;
    }

    /**
     * Find available factories
     *
     * @return array An array of factory class names which are available
     */
    public function discoverFactories()
    {
        $this->loadFactories();

        return $this->getDefinedFactories();
    }

    /**
     * Search the filesystem for and load files which might contain a factory
     */
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

    /**
     * Get a list of factory class names which are loaded
     *
     * @return array An array of factory class names
     */
    public function getDefinedFactories()
    {
        return array_filter(get_declared_classes(), array($this, 'isFactory'));
    }

    /**
     * Determine if a given class is a factory
     *
     * @param string $class The name of the class to examine
     * @return bool
     */
    private function isFactory($class)
    {
        $reader = new AnnotationReader();

        $reflection = new ReflectionClass($class);
        return $reader->getClassAnnotation($reflection, '\Carpenter\Annotation\Factory') !== null;
    }
}
