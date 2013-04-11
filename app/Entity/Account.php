<?php

namespace Entity;

// DON'T forget this use statement!!!


use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Account
 */
class Account {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $bio;

    /**
     * @var DateTime
     */
    private $last_login;

    /**
     * @var \Entity\User
     */
    private $user;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Account
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set bio
     *
     * @param string $bio
     * @return Account
     */
    public function setBio($bio) {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio
     *
     * @return string 
     */
    public function getBio() {
        return $this->bio;
    }

    /**
     * Set last_login
     *
     * @param DateTime $lastLogin
     * @return Account
     */
    public function setLastLogin($lastLogin) {
        $this->last_login = $lastLogin;

        return $this;
    }

    /**
     * Get last_login
     *
     * @return DateTime 
     */
    public function getLastLogin() {
        return $this->last_login;
    }

    /**
     * Set user
     *
     * @param \Entity\User $user
     * @return Account
     */
    public function setUser(\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Entity\User 
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @var string
     */
    private $username;

    /**
     * Set username
     *
     * @param string $username
     * @return Account
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @var DateTime
     */
    private $created_at;

    /**
     * @var DateTime
     */
    private $updated_at;

    /**
     * Set created_at
     *
     * @param DateTime $createdAt
     * @return Account
     */
    public function setCreatedAt($createdAt) {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return DateTime 
     */
    public function getCreatedAt() {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param DateTime $updatedAt
     * @return Account
     */
    public function setUpdatedAt($updatedAt) {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return DateTime 
     */
    public function getUpdatedAt() {
        return $this->updated_at;
    }

    /** contraintes de validation * */
    public static function loadValidatorMetadata(ClassMetadata $metadata) {
        // username doit être unique //
        $metadata->addConstraint(new UniqueEntity(array(
            'fields' => array('username'),
            "service" => "validator.unique_entity",
        )));
    }

    /**
     * @var \Entity\Account
     */
    private $account;

    /**
     * Set account
     *
     * @param \Entity\Account $account
     * @return Account
     */
    public function setAccount(\Entity\Account $account = null) {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Entity\Account 
     */
    public function getAccount() {
        return $this->account;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $followers;

    /**
     * Constructor
     */
    public function __construct() {
        $this->followers = new ArrayCollection();
        $this->followed = new ArrayCollection();
    }

    /**
     * Add followers
     *
     * @param \Entity\Account $followers
     * @return Account
     */
    public function addFollower(\Entity\Account $followers) {
        $this->followers[] = $followers;

        return $this;
    }

    /**
     * Remove followers
     *
     * @param \Entity\Account $followers
     */
    public function removeFollower(\Entity\Account $followers) {
        $this->followers->removeElement($followers);
    }

    /**
     * Get followers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFollowers() {
        return $this->followers;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $followed;

    /**
     * Add followed
     *
     * @param \Entity\Account $followed
     * @return Account
     */
    public function addFollowed(\Entity\Account $followed) {
        $this->followed[] = $followed;

        return $this;
    }

    /**
     * Remove followed
     *
     * @param \Entity\Account $followed
     */
    public function removeFollowed(\Entity\Account $followed) {
        $this->followed->removeElement($followed);
    }

    /**
     * Get followed
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFollowed() {
        return $this->followed;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $posts;

    /**
     * Add posts
     *
     * @param \Entity\Post $posts
     * @return Account
     */
    public function addPost(\Entity\Post $posts) {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \Entity\Post $posts
     */
    public function removePost(\Entity\Post $posts) {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts() {
        return $this->posts;
    }

}