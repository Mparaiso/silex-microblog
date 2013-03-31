<?php

$autolad = require __DIR__."/../vendor/autoload.php";

$app = require __DIR__.'/../app/app.php';

$app['http_cache']->run();