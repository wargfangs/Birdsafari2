<?php

namespace AppBundle\Repository;
use Doctrine\ORM\QueryBuilder;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{

    function getTotalCount()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('COUNT(u)')
            ->from("AppBundle:User", "u");
            return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $pageNumber
     * @return mixed : array
     */
    public function getByPage($pageNumber, $order,$limit=25)
    {
        $results['nombreResultat'] =  $this->getTotalCount();
        $results['nombrePage'] = ceil($results['nombreResultat']/$limit);//Total number of page per 25 Users
        $page = intval($pageNumber);
        if($results['nombrePage'] < $page)     //25 Users per page
            $page = ceil($results['nombreResultat']/$limit);    //
        if($page<1)
            $page=1;

        $results["page"]= $page;

        $qb = $this->_em->createQueryBuilder('u')->select('u')
            ->from('AppBundle:User','u');


        //Order by:
        if($order == "user")
        {
            $qb->orderBy('u.username');
        }
        else if($order == "role")
        {
            $qb->orderBy('u.roles');
        }
        $qb->setFirstResult(($page-1)*$limit)->setMaxResults($limit);

        $results['results']= $qb->getQuery()->getResult();

        return $results;




    }
}
