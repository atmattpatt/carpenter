<?php

namespace Carpenter\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;

class DoctrineAdapter implements AdapterInterface
{
    private $entityManager;
    private $flushAfterPersist;

    public function __construct(EntityManagerInterface $entityManager, $flushAfterPersist = true)
    {
        $this->entityManager = $entityManager;
        $this->flushAfterPersist = $flushAfterPersist;
    }

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

    public function persist($built)
    {
        $this->entityManager->persist($built);

        if ($this->flushAfterPersist) {
            $this->entityManager->flush();
        }
    }
}
