<?php
use Silex\Application;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

class App extends Silex\Application
{
    public function __construct(array $values = array())
    {
        parent::__construct($values);
//        if (getenv("environment") === "development") {
//            error_reporting(E_ALL);
            $this["debug"] = TRUE;
//        }
        $this->register(new Config);


    }

}


