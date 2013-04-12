<?php

namespace Service;

use Bootstrap;
use Entity\Account;
use Entity\Post;
use Silex\WebTestCase;

class AccountServiceTest extends WebTestCase {

    public function createApplication() {
        return Bootstrap::getApp();
    }

    function testConstruct() {
        $this->assertTrue($this->app != null);
    }

    /**
     * FR : un compte $a1 suit un compte $a2 , puis ne suit plus le compte $a2
     */
    function testFollow() {
        $a1 = new Account();
        $a1->setUsername("john")->setEmail("john@acme.com");
        $a2 = new Account();
        $a2->setUsername('susan')->setEmail("susan@acme.com");
        $this->app['account_service']->save($a1);
        $this->app['account_service']->save($a2);
        $this->assertNull($this->app['account_service']->unfollow($a1, $a2));
        $this->app['account_service']->follow($a1, $a2);
        $this->assertNull($this->app['account_service']->follow($a1, $a2));
        $this->assertTrue($this->app['account_service']->isFollowing($a1, $a2));
        $this->assertEquals(1, $a1->getFollowed()->count());
        $this->assertEquals('susan', $a1->getFollowed()->first()->getUsername());
//        \Doctrine\Common\Util\Debug::dump($a2);        
//        \Doctrine\Common\Util\Debug::dump($a1);
        $this->assertEquals(1, $a2->getFollowers()->count());
        $this->assertEquals('john', $a2->getFollowers()->first()->getUsername());
        $a = $this->app['account_service']->unfollow($a1, $a2);
        $this->assertNotNull($a);
        $this->assertFalse($this->app['account_service']->isFollowing($a1, $a2));
        $this->assertEquals(0, $a1->getFollowed()->count());
        $this->assertEquals(0, $a2->getFollowers()->count());
    }

    function testFollowPosts() {
        $u[1] = new Account();
        $u[2] = new Account();
        $u[3] = new Account();
        $u[4] = new Account();
        $u[1]->setUsername('john')->setEmail('john@example.com');
        $u[2]->setUsername('susan')->setEmail("susan@example.com");
        $u[3]->setUsername('mary')->setEmail('mary@example.com');
        $u[4]->setUsername("david")->setEmail("david@example.com");
        foreach ($u as $account) {
            $this->app['account_service']->save($account);
        }
        $this->assertNotNull($u[1]->getId());
        $p[1] = new Post;
        $p[2] = new Post;
        $p[3] = new Post;
        $p[4] = new Post;
        $now = new \DateTime;
        $p[1]->setBody('post from john')->setAccount($u[1])->setCreatedAt($now)
                ->setUpdatedAt($now);
        $p[2]->setBody('post from susan')->setAccount($u[2])->setCreatedAt($now)
                ->setUpdatedAt($now);
        $p[3]->setBody('post from mary')->setAccount($u[3])->setCreatedAt($now)
                ->setUpdatedAt($now);
        $p[4]->setBody('post from david')->setAccount($u[4])->setCreatedAt($now)
                ->setUpdatedAt($now);
        foreach ($p as $post) {
            $this->app["post_service"]->save($post);
        }
        $this->assertNotNull($p[1]->getID());
        $this->app['account_service']->follow($u[1], $u[1]);
        $this->app['account_service']->follow($u[1], $u[3]);
        $this->app['account_service']->follow($u[1], $u[4]);
        $this->app['account_service']->follow($u[2], $u[2]);
        $this->app['account_service']->follow($u[2], $u[3]);
        $this->app['account_service']->follow($u[3], $u[3]);
        $this->app['account_service']->follow($u[3], $u[4]);
        $this->app['account_service']->follow($u[4], $u[4]);
        $f1 = $this->app['post_service']->findFollowedAccountPosts($u[1]);
        $f2 = $this->app['post_service']->findFollowedAccountPosts($u[2]);
        $f3 = $this->app['post_service']->findFollowedAccountPosts($u[3]);
        $f4 = $this->app['post_service']->findFollowedAccountPosts($u[4]);
        $this->assertTrue(count($f1) === 3);
        $this->assertTrue(count($f2) === 2);
        $this->assertTrue(count($f3) === 2);
        $this->assertTrue(count($f4) === 1);
    }

}