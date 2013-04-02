#!/bin/env php
<?php
#execute with 'php console.php' on the command line
$autoload = require __DIR__.'/vendor/autoload.php';

$autoload->add('',__DIR__.'/app/');

$app = require __DIR__.'/app/app.php';

$app->boot();

$cli = $app['console'];
$app['orm.console.boot_commands']();
$cli->run();