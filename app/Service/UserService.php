<?php

namespace Service;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Entity\User;

/***
 * EN : manager users used with the security service
 * FR : gère les utilisateurs liés au service de sécurité
 */
class UserService
{
    protected $em;
    protected $sc;

    function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    function register(User $user)
    {
        $role_user = $this->em->getRepository('Entity\Role')->findOneBy(array('role' => "ROLE_USER"));
        $user->addRole($role_user);
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    /**
     * Finds a single entity by a set of criteria.
     * @param array $criteria
     * @return \Entity\User
     */
    function findOneBy(array $criteria)
    {
        return $this->em->getRepository('Entity\User')->findOneBy($criteria);
    }

}