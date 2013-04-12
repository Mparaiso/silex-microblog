<?php

use Silex\ServiceProviderInterface;
use Service\PostService;
use Service\UserService;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\FormServiceProvider;
use Mparaiso\Provider\ConsoleServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Mparaiso\Provider\DoctrineORMServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Silex\Application;

/**
 * FR : Configuration principale de l'application
 * EN : app main configuration , using BlogConfig  for registering services specific to the microblogs
 */
class AppConfig implements ServiceProviderInterface {

    /**
     * 
     * @{inherit doc}
     */
    public function register(Application $app) {
        /**
         * EN : service definitions
         * FR : définition des services et configuration de l'application
         */
        $app['root'] = dirname(__DIR__);
        $app['temp'] = __DIR__ . "/../temp/";
        /* user provider for security */
        $app['user_provider'] = $app->share(function (Application $app) {
                    return new EntityUserProvider($app["orm.manager_registry"], 'Entity\User', "username");
                }
        );
        $app->register(new ServiceControllerServiceProvider);
        $app->register(new HttpCacheServiceProvider, array(
            "http_cache.cache_dir" => $app['temp'] . "/cache/",
        ));
        $app->register(new TwigServiceProvider, array(
            "twig.path" => array(__DIR__ . "/Resources/views/"),
            "twig.options" => array(
                "cache" => $app["temp"] . "/twig/",
            ),
        ));
        $app->register(new MonologServiceProvider, array(
            "monolog.logfile" => $app['temp'] . "/" . date('Y-m-d') . ".text",
        ));
        $app->register(new ConsoleServiceProvider);
        $app->register(new FormServiceProvider);
        $app->register(new ValidatorServiceProvider);
        $app->register(new TranslationServiceProvider, array("locale_fallback" => "en"));
        $app->register(new SessionServiceProvider);
        $app->register(new UrlGeneratorServiceProvider);
        $app->register(new DoctrineServiceProvider, array(
            "db.options" => array(
                "driver" => getenv("BLOG_DRIVER"),
                "dbname" => getenv("BLOG_DBNAME"),
                "password" => getenv("BLOG_PASSWORD"),
                "user" => getenv("BLOG_USER"),
                "host" => getenv("BLOG_HOST"),
                "path" => getenv("BLOG_PATH"),
                "memory" => getenv("BLOG_MEMORY")
            )
        ));
        $app->register(new DoctrineORMServiceProvider, array(
            //"orm.cache"=>new FilesystemCache(__DIR__."/../temp/orm/"),
            "orm.proxy_dir" => __DIR__ . "/Proxy/",
        ));

        $app->register(new SecurityServiceProvider, array(
            "security.firewalls" => array(
                "protected" => array(
                    "anonymous" => TRUE,
                    "pattern" => "^/",
                    "form" => array(
                        "login_path" => "/login",
                        "check_path" => "/private/authenticate",
                        "default_target_path" => "/private/profile"
                    ),
                    "logout" => array(
                        "logout_path" => "/private/logout",
                        "target" => "/",
                    ),
                    "users" => $app->share(function ($app) {
                                return $app["user_provider"];
                            })
                )
            ),
            "security.role_hierarchy" => array("ROLE_USER" => array()),
            "security.access_rules" => array(
                array("^/private", "ROLE_USER"),
            )
        ));

        /**
         * EN :registering the blog config 
         * FR : enregistrement de la configuration du blog
         */
        $app->register(new BlogConfig);
    }

    public function boot(Application $app) {
        
    }

}