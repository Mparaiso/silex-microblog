<?php

namespace Service;

use Bootstrap;
use Entity\Account;
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

}