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
 * <pre>
 * BLOG_ENV development | production 
 * BLOG_DBNAME database name
   BLOG_PASSWORD database password 
   BLOG_USER database username
   BLOG_DBHOST database server address | localhost
   BLOG_PATH database path if sqlite
   BLOG_MEMORY true | false for sqlite
 * BLOG_HOST blog host for openid authentication ( like http://localhost , http://myserver.com , etc.... )
 * BLOG_DRIVER pdo_mysql | etc ....
 * 
 * folders :
 * 
 * Controller  : the controllers 
 * Form: the forms
 * Service: the services holding the business logic of the application
 * Resources: files like templates , metadatas for doctrine orm , ...
 * Entity: Doctrine entites
 * Proxy: Entity proxies
 *</pre>
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

