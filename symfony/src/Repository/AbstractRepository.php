<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\QueryBuilder;

/**
 * {@inheritdoc}
 *
 * @method Entity find($id, $lockMode = null, $lockVersion = null)
 * @method Entity findOneBy(array $criteria, array $orderBy = null)
 * @method Entity[] findAll()
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('this')->select('this');

        return $qb;
    }
}
