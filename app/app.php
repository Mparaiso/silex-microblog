<?php
use Silex\Provider\HttpCacheServiceProvider;
use Mparaiso\Provider\BlogServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;


$app         = new Silex\Application;
$app['root'] = dirname(__DIR__);
$app['temp'] = __DIR__ . "/../temp/";
if (getenv("environment") === "development") {
    error_reporting(E_ALL);
    $app["debug"] = TRUE;
}
$app->register(new HttpCacheServiceProvider, array(
    "http_cache.cache_dir" => $app['temp'],
));
$app->register(new TwigServiceProvider, array(
    "twig.options" => array(
        "cache" => $app["temp"] . "/twig/"
    ),
));
$app->register(new BlogServiceProvider);
/***
 * Controllers
 */
$app->match("/", function (Application $app) {
    $user  = array("nickname" => "John doe");
    $posts = array(
        array(
            "author" => array("nickname" => "Jesus Christ"),
            "body"   => "The holy bible"
        ),
        array(
            "author" => array("nickname" => "Stan Lee"),
            "body"   => "The amazing spiderman",
        )
    );
    return $app["twig"]->render("blog.index", array(
        "user" => $user, "posts" => $posts
    ));
});
$app->match("/index", function (Request $req, Application $app) {
    #@note @silex forward request
    $sub = $req::create("/");
    return $app->handle($sub, HttpKernelInterface::SUB_REQUEST);
});
return $app;