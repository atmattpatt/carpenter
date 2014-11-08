<?php

namespace Carpenter\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;

/**
 * Adapter to build and persist Doctrine entities
 */
class DoctrineAdapter implements AdapterInterface
{
    /**
     * Doctrine EntityManager
     * @var Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * Whether or not to flush the EntityManager after each persist
     * @var bool
     */
    private $flushAfterPersist;

    /**
     * Constructs a new DoctrineAdapter
     *
     * @param Doctrine\ORM\EntityManagerInterface $entityManager
     *    The EntityManager to use for persisting fixtures
     * @param bool $flushAfterPersist Whether or not to flush the EntityManager
     *    after each persist
     */
    public function __construct(EntityManagerInterface $entityManager, $flushAfterPersist = true)
    {
        $this->entityManager = $entityManager;
        $this->flushAfterPersist = $flushAfterPersist;
    }

    /**
     * Builds an entity
     *
     * This method uses a dumb hydration strategy and assumes that you have defined
     * a setter method for each property.
     *
     * @example
     * The factory property `$firstName` requires the method `setFirstName()` to exist
     *
     * @param string $targetClass The entity class to build
     * @param mixed $resolved A factory with all properties resolved
     * @return mixed A built entity
     */
    public function build($targetClass, $resolved)
    {
        $output = new $targetClass;
        $reflection = new ReflectionClass($resolved);

        foreach ($reflection->getProperties() as $property) {
            $setter = 'set' . ucfirst($property->getName());
            $output->$setter($property->getValue($resolved));
        }

        return $output;
    }

    /**
     * Persists an entity via the EntityManager
     *
     * @param mixed $built A built entity
     */
    public function persist($built)
    {
        $this->entityManager->persist($built);

        if ($this->flushAfterPersist) {
            $this->entityManager->flush();
        }
    }

    /** {@inheritDoc} */
    public function beginTransaction()
    {
        $this->entityManager->beginTransaction();
    }

    /** {@inheritDoc} */
    public function rollback()
    {
        $this->entityManager->rollback();
    }
}
