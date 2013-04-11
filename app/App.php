<?php

class App extends Silex\Application {

    public function __construct(array $values = array()) {
        parent::__construct($values);
        if (getenv("BLOG_ENV") === "development") {
            error_reporting(E_ALL);
            $this["debug"] = TRUE;
            ini_set("error_log",__DIR__."..\log\php-script.log");
        }
        $this->register(new Config);
    }

}

