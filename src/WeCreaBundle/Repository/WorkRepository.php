<?php

namespace WeCreaBundle\Repository;
use Doctrine\ORM\Query;

/**
 * WorkRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WorkRepository extends \Doctrine\ORM\EntityRepository
{
    /*
    public function myFindBy($title, $technic, $dimensions, $weight){
        $query = $this->createQueryBuilder('w');
        $query->select('w.id, w.title, w.technic, w.dimensions, w.weight, w.timelimit, w.quantity')

        ->where('w.title = :title')->setParameter('title', $title)
        ->Andwhere('w.technic = :technic')->setParameter('technic', $technic)
        ->AndWhere('w.dimensions = :dimensions')->setParameter('dimensions', $dimensions)
        ->AndWhere('w.weight = :weight')->setParameter('weight', $weight);
        return $query->getQuery()->getResult();

    }
    */
}
