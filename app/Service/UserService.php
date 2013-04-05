<?php

namespace Service;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Entity\User;

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
}