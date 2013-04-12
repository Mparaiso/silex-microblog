<?php
/**
 * @author MParaiso <mparaiso@online.fr>
 * @license GPL
 * 
 * FR : l'application microblog
 * les variables d'envirronement suivantes doivent être définies
 * 
 * EN : the microblog silex application
 * the following server variables need to be defined : 
 * 
 * BLOG_ENV development | production 
 * BLOG_DBNAME database name
   BLOG_PASSWORD database password 
   BLOG_USER database username
   BLOG_HOST database server address | localhost
   BLOG_PATH database path if sqlite
   BLOG_MEMORY true | false for sqlite
 * BLOG_DRIVER pdo_mysql | etc ....
 */
class App extends Silex\Application {

    public function __construct(array $values = array()) {
        parent::__construct($values);
        if (getenv("BLOG_ENV") === "development") {
            error_reporting(E_ALL);
            $this["debug"] = TRUE;
            ini_set("error_log",__DIR__."..\log\php-script.log");
        }
        $this->register(new AppConfig);
    }

}

