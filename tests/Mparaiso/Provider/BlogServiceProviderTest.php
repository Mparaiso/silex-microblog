<?php

namespace Mparaiso\Provider;

use Silex\WebTestCase;

class BlogServiceProviderTest extends WebTestCase{

    /**
     * {@inheritdoc}
     */
    public function createApplication()
    {
        return new \Silex\Application();
    }

    public function testRegister(){
        $this->app->register(new BlogServiceProvider);
        $this->assertTrue($this->app['blog.ns']=="blog");
    }
}