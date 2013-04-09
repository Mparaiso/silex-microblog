<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 */
class Post {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $body;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $updated_at;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Post
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Post
     */
    public function setBody($body) {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Post
     */
    public function setCreatedAt($createdAt) {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Post
     */
    public function setUpdatedAt($updatedAt) {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt() {
        return $this->updated_at;
    }

    /**
     * @var \Entity\Account
     */
    private $account;

    /**
     * Set account
     *
     * @param \Entity\Account $account
     * @return Post
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

}