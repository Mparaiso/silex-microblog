<?php

namespace Service;

use Doctrine\ORM\EntityManager;
use Entity\Account;
use Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

/**
 * EN : manage accounts
 * FR : gère les comptes utilisateurs
 */
class AccountService {

    private $em;

    const ENTITY = 'Entity\Account';

    private $encoder;

    function __construct(EntityManager $em, EncoderFactory $encoder = null) {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    function save(Account $account) {
        if ($account->getCreatedAt() === null) {
            $account->setCreatedAt(new \DateTime);
        }
        $account->setUpdatedAt(new \DateTime);
        if (!$this->isFollowing($account, $account)) {
            $this->follow($account, $account);
        }
        $this->em->persist($account);
        $this->em->flush();
        return $account;
    }

    function register(Account $account, User $user = null) {
        $account->setUsername($this->makeUserName($account->getUsername()));
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
        return $this->save($account);
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

    /**
     * FR : ajoute un ami si pas ami
     * @param \Entity\Account $account
     * @param \Entity\Account $follower
     * @return Account
     */
    function follow(Account $account, Account $friend) {
        if (!$this->isFollowing($account, $friend)) {
            $account->addFollowed($friend);
            $friend->addFollower($account);
            return $this->save($account);
        }
    }

    /**
     * FR : enlève un ami si $follower est un ami
     * @param \Entity\Account $account
     * @param \Entity\Account $follower
     */
    function unfollow(Account $account, Account $friend) {
        if ($this->isFollowing($account, $friend)) {
            $account->getFollowed()->removeElement($friend);
            $friend->getFollowers()->removeElement($account);
            return $this->save($account);
        }
    }

    /**
     * FR : retourne vrai si le compte $account suit $follower
     * @param \Entity\Account $account
     * @param \Entity\Account $follower
     * @return bool
     */
    function isFollowing(Account $account, Account $friend) {
        return $account->getFollowed()->contains($friend);
    }

}