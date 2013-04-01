<?php

$autoload = require __DIR__."/../vendor/autoload.php";
$autoload->add("",__DIR__."/../app/");
$app = require __DIR__.'/../app/app.php';

$app['http_cache']->run();