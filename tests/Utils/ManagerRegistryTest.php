<?php

namespace Kematjaya\WilayahBundle\Tests\Utils;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

class ManagerRegistryTest implements ManagerRegistry
{

    public function getDefaultConnectionName(): string
    {
        // TODO: Implement getDefaultConnectionName() method.
    }

    public function getConnection(?string $name = null): object
    {
        // TODO: Implement getConnection() method.
    }

    public function getConnections(): array
    {
        // TODO: Implement getConnections() method.
    }

    public function getConnectionNames(): array
    {
        // TODO: Implement getConnectionNames() method.
    }

    public function getDefaultManagerName(): string
    {
        // TODO: Implement getDefaultManagerName() method.
    }

    public function getManager(?string $name = null): ObjectManager
    {
        // TODO: Implement getManager() method.
    }

    public function getManagers(): array
    {
        // TODO: Implement getManagers() method.
    }

    public function resetManager(?string $name = null): ObjectManager
    {
        // TODO: Implement resetManager() method.
    }

    public function getManagerNames(): array
    {
        // TODO: Implement getManagerNames() method.
    }

    public function getRepository(string $persistentObject, ?string $persistentManagerName = null,): ObjectRepository
    {
        // TODO: Implement getRepository() method.
    }

    public function getManagerForClass(string $class): ObjectManager|null
    {
        // TODO: Implement getManagerForClass() method.
    }
}