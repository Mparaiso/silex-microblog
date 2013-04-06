<?php


namespace Service;


use Doctrine\ORM\EntityManager;

class PostService
{
    protected $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }



    function findBy(array $criteria, array $orderBy = array(), $limit = NULL, $offset = NULL)
    {
        return $this->em->getRepository('Entity\Post')->findBy($criteria, $orderBy, $limit, $offset);
    }


}