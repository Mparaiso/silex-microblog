<?php

namespace Mparaiso\Blog\Controller;

use Silex\WebTestCase;
use Controller\DefaultController;

class DefaultControllerTest extends WebTestCase{
    /**
     * @return \App
     */
    public function createApplication()
    {
       $app =  new \App();
       $app->boot();
       return $app;
    }

    public function testConstruct(){
        $controller = new DefaultController();
        $this->assertNotNull($controller);
    }
}