<?php

class App extends Silex\Application {

    public function __construct(array $values = array()) {
        parent::__construct($values);
        if (getenv("BLOG_ENV") === "development") {
            error_reporting(E_ALL);
            $this["debug"] = TRUE;
        }
        $this->register(new Config);
    }

}

