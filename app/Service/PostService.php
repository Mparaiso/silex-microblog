<?php

namespace Service;

use DateTime;
use Doctrine\ORM\EntityManager;
use Entity\Account;
use Entity\Post;
use Entity\Search;

class PostService {

    protected $em;

    function __construct(EntityManager $em) {
        $this->em = $em;
    }

    function search(Search $search) {
        $query = $this->em->createQuery(" select p from Entity\Post p where p.body LIKE :expression ORDER BY p.created_at DESC ");
        $query->setParameter("expression", "%" . $search->getExpression() . "%");
        return $query->execute();
    }

    function save(Post $post) {
        $post->setCreatedAt(new DateTime);
        $post->setUpdatedAt(new DateTime);
        $this->em->persist($post);
        $this->em->flush();
        return $post;
    }

    function findBy(array $criteria, array $orderBy = array(), $limit = NULL, $offset = NULL) {
        return $this->em->getRepository('Entity\Post')->findBy($criteria, $orderBy, $limit, $offset);
    }

    function findFollowedAccountPosts(Account $account,$limit,$offset) {
        $query = $this->em->createQuery(" SELECT p FROM Entity\Post p JOIN Entity\Account a WHERE 
            a = :account AND p.account MEMBER of a.followed ORDER BY p.created_at DESC
        ");
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);
        return $query->execute(array("account"=>$account));
    }

}