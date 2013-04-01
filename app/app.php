<?php
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\FormServiceProvider;
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
$app->register(new FormServiceProvider);
$app->register(new ValidatorServiceProvider);
$app->register(new TranslationServiceProvider,array("locale_fallback"=>"en"));
$app->register(new SessionServiceProvider);
$app->register(new UrlGeneratorServiceProvider);

/***
 * Controllers
 */
$app->match("/index", function (Request $req, Application $app) {
    #@note @silex forward request
    $sub = $req::create("/");
    return $app->handle($sub, HttpKernelInterface::SUB_REQUEST);
});

$app->mount("/", $app["blog.default_controller"]);

return $app;