<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

$autoload = require __DIR__ . "/../vendor/autoload.php";

$autoload->add("", __DIR__ . "/../app/");
$autoload->add("", __DIR__ . "/");

class Bootstrap {

    static function createDatabase(EntityManager $em) {
        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($em);
        $schemaTool->createSchema($metadatas);
    }

    static function getApp() {
        $app = new App();
        $app["db.options"] = array(
            "driver" => "pdo_sqlite",
            "memory" => true,
        );
        $app->boot();
        self::createDatabase($app['orm.em']);
        return $app;
    }

}
