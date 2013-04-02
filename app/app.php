<?php
use Silex\Provider\HttpCacheServiceProvider;
use Mparaiso\Provider\ConsoleServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Mparaiso\Provider\DoctrineORMServiceProvider;
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
    "http_cache.cache_dir" => $app['temp'] . "/cache/",
));
$app->register(new TwigServiceProvider, array(
    "twig.options" => array(
        "cache" => $app["temp"] . "/twig/"
    ),
));
$app->register(new MonologServiceProvider, array(
    "monolog.logfile" => $app['temp'] . "/" . date('Y-m-d') . ".text",
));
$app->register(new ConsoleServiceProvider);
$app->register(new BlogServiceProvider);
$app->register(new FormServiceProvider);
$app->register(new ValidatorServiceProvider);
$app->register(new TranslationServiceProvider, array("locale_fallback" => "en"));
$app->register(new SessionServiceProvider);
$app->register(new UrlGeneratorServiceProvider);
$app->register(new DoctrineServiceProvider, array(
    "db.options" => array(
        "driver" => "pdo_sqlite",
        "path"   => $app['root'] . "/db/blog.sqlite",
    )
));
$app->register(new DoctrineORMServiceProvider);
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