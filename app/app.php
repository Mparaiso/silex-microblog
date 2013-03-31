<?php
use Silex\Provider\HttpCacheServiceProvider;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;


$app = new Silex\Application;
$app['temp']=__DIR__."/../temp/";
$app["debug"]=true;
$app->register(new HttpCacheServiceProvider,array(
"http_cache.cache_dir"=>$app['temp'],
));
$app->match("/", function () {
    return "Hello, World!";
});
$app->match("/index", function (Request $req, Application $app) {
    #@note @silex forward request
    $sub = $req::create("/");
    return $app->handle($sub, HttpKernelInterface::SUB_REQUEST);
});
return $app;