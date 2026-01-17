<?php

namespace Kematjaya\WilayahBundle\Tests\Utils;

use DateTimeInterface;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Cache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Proxy\ProxyFactory;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\FilterCollection;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\UnitOfWork;

class EntityManager implements EntityManagerInterface 
{
    public function getRepository(string $className): EntityRepository
    {
        // TODO: Implement getRepository() method.
    }

    public function getCache(): Cache|null
    {
        // TODO: Implement getCache() method.
    }

    public function getConnection(): Connection
    {
        // TODO: Implement getConnection() method.
    }

    public function getMetadataFactory(): ClassMetadataFactory
    {
        // TODO: Implement getMetadataFactory() method.
    }

    public function getExpressionBuilder(): Expr
    {
        // TODO: Implement getExpressionBuilder() method.
    }

    public function beginTransaction(): void
    {
        // TODO: Implement beginTransaction() method.
    }

    public function wrapInTransaction(callable $func): mixed
    {
        // TODO: Implement wrapInTransaction() method.
    }

    public function commit(): void
    {
        // TODO: Implement commit() method.
    }

    public function rollback(): void
    {
        // TODO: Implement rollback() method.
    }

    public function createQuery(string $dql = ''): Query
    {
        // TODO: Implement createQuery() method.
    }

    public function createNativeQuery(string $sql, ResultSetMapping $rsm): NativeQuery
    {
        // TODO: Implement createNativeQuery() method.
    }

    public function createQueryBuilder(): QueryBuilder
    {
        // TODO: Implement createQueryBuilder() method.
    }

    public function find(string $className, mixed $id, int|LockMode|null $lockMode = null, ?int $lockVersion = null): object|null
    {
        // TODO: Implement find() method.
    }

    public function refresh(object $object, int|LockMode|null $lockMode = null): void
    {
        // TODO: Implement refresh() method.
    }

    public function getReference(string $entityName, mixed $id): object|null
    {
        // TODO: Implement getReference() method.
    }

    public function close(): void
    {
        // TODO: Implement close() method.
    }

    public function lock(object $entity, int|LockMode $lockMode, DateTimeInterface|int|null $lockVersion = null): void
    {
        // TODO: Implement lock() method.
    }

    public function getEventManager(): EventManager
    {
        // TODO: Implement getEventManager() method.
    }

    public function getConfiguration(): Configuration
    {
        // TODO: Implement getConfiguration() method.
    }

    public function isOpen(): bool
    {
        // TODO: Implement isOpen() method.
    }

    public function getUnitOfWork(): UnitOfWork
    {
        // TODO: Implement getUnitOfWork() method.
    }

    public function newHydrator(int|string $hydrationMode): AbstractHydrator
    {
        // TODO: Implement newHydrator() method.
    }

    public function getProxyFactory(): ProxyFactory
    {
        // TODO: Implement getProxyFactory() method.
    }

    public function getFilters(): FilterCollection
    {
        // TODO: Implement getFilters() method.
    }

    public function isFiltersStateClean(): bool
    {
        // TODO: Implement isFiltersStateClean() method.
    }

    public function hasFilters(): bool
    {
        // TODO: Implement hasFilters() method.
    }

    public function getClassMetadata(string $className): Mapping\ClassMetadata
    {
        // TODO: Implement getClassMetadata() method.
    }

    public function persist(object $object): void
    {
        // TODO: Implement persist() method.
    }

    public function remove(object $object): void
    {
        // TODO: Implement remove() method.
    }

    public function clear(): void
    {
        // TODO: Implement clear() method.
    }

    public function detach(object $object): void
    {
        // TODO: Implement detach() method.
    }

    public function flush(): void
    {
        // TODO: Implement flush() method.
    }

    public function initializeObject(object $obj): void
    {
        // TODO: Implement initializeObject() method.
    }

    public function isUninitializedObject(mixed $value): bool
    {
        // TODO: Implement isUninitializedObject() method.
    }

    public function contains(object $object): bool
    {
        // TODO: Implement contains() method.
    }
}
