<?php

class AppTest extends \Silex\WebTestCase
{
    /**
     * {@inheritdoc}
     */
    public function createApplication()
    {
        $app =  new App();
        $app->boot();
        return $app;
    }

    function testConstruct(){
        $this->assertNotNull($this->app['user_provider']);
    }
}