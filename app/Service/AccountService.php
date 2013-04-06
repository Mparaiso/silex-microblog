<?php

namespace Service;

use Doctrine\ORM\EntityManager;
use Entity\Account;
use Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class AccountService {

    private $em;

    const ENTITY = 'Entity\Account';

    private $encoder;

    function __construct(EntityManager $em, EncoderFactory $encoder = null) {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    function save(Account $account) {
        $account->setUpdatedAt(new \DateTime);
        $this->em->persist($account);
        $this->em->flush();
        return $account;
    }

    function register(Account $account, User $user = null) {
        $account->setUsername($this->makeUserName($account->getUsername()));
        $account->setCreatedAt(new \DateTime);
        $account->setUpdatedAt(new \DateTime);
        if ($user != null) {
            $account->setUser($user);
        }
        #@TODO fix it
        /* }else{
          $user = new User();
          $user->setUsername($account->getEmail());
          $user->
          } */
        $account->getUser()->addRole($this->em->getRepository('Entity\Role')->findOneBy(array("role" => "ROLE_USER")));
        $account->getUser()->setCreatedAt(new \DateTime);
        $account->getUser()->setUpdatedAt(new \Datetime);
        $this->em->persist($account);
        $this->em->flush();
        return $account;
    }

    function findOneBy(array $criteria) {
        return $this->em->getRepository(self::ENTITY)->findOneBy($criteria);
    }
    
    /**
     * 
     * @param string $username
     * @return string
     */
    function makeUserName($username = null) {
        if ($username == null || $this->findOneBy(array("username" => $username)) != null) {
            $username = uniqid("user");
        }
        return $username;
    }

}