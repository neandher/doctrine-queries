<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    public function findAllOrdered()
    {
        $qb = $this->createQueryBuilder('cat')
            ->addOrderBy('cat.name', 'ASC');

        $this->addFortuneCookieJoinAndSelect($qb);

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function search($term)
    {
        return $this->createQueryBuilder('cat')
            ->andWhere('cat.name LIKE :searchTerm 
            OR cat.iconKey LIKE :searchTerm 
            OR fc.fortune LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$term.'%')
            ->leftJoin('cat.fortuneCookies', 'fc')
            ->addSelect('fc')
            ->getQuery()
            ->execute();
    }

    public function findWithFortunesJoin($id)
    {
        return $this->createQueryBuilder('cat')
            ->andWhere('cat.id = :id')
            ->leftJoin('cat.fortuneCookies', 'fc')
            ->addSelect('fc')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Joins over to cat.fortuneCookies AND selects its fields
     *
     * @param QueryBuilder $qb
     * @return QueryBuilder
     */
    private function addFortuneCookieJoinAndSelect(QueryBuilder $qb)
    {
        return $qb->leftJoin('cat.fortuneCookies', 'fc')
            ->addSelect('fc');
    }
}
