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
use Controller\DefaultController;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Silex\Application;
use Service\AccountService;
use Doctrine\Common\Cache\FileSystemCache;

/**
 * FR : Configuration de l'application
 */
class Config implements ServiceProviderInterface {

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
        $app['config'] = array(
            "MAX_SEARCH_RESULTS" => 50,
        );
        // local services
        $app["openid_providers"] = array(
            array('name' => 'Google', 'url' => 'https://www.google.com/accounts/o8/id'),
            array('name' => 'Yahoo', 'url' => 'https://me.yahoo.com'),
            array('name' => 'AOL', 'url' => 'http://openid.aol.com/<add your aol username here>'),
            array('name' => 'Flickr', 'url' => 'http://www.flickr.com/<add your flickr username here>'),
            array('name' => 'MyOpenID', 'url' => 'https://www.myopenid.com'),
            array("name" => "Wordpress", 'url' => "http://<add your wordpress username here>.wordpress.com/"),
            array("name" => "Blogger", 'url' => "http://<add your blog name here>.blogspot.com/"),
        );
        /* user provider for security */
        $app['user_provider'] = $app->share(function (Application $app) {
                    return new EntityUserProvider($app["orm.manager_registry"], '\Entity\User', "username");
                });
        /* user service */
        $app["user_service"] = $app->share(function ($app) {
                    return new UserService($app["orm.em"]);
                });
        $app["post_service"] = $app->share(function ($app) {
                    return new PostService($app["orm.em"]);
                });
        $app["account_service"] = $app->share(function($app) {
                    return new AccountService($app['orm.em']);
                });
        $app['current_account'] = $app->share(function($app) {
                    if ($app['security']->isGranted("IS_AUTHENTICATED_FULLY")) {
                        $account = $app["account_service"]->findOneBy(array(
                            "user" => $app['security']->getToken()->getUser()));
                        //$app['logger']->info(print_r($account, true));
                        return $account;
                    }
                });
        /* default controller */
        $app["default_controller"] = $app->share(function (Application $app) {
                    return new DefaultController();
                });
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
                "driver" => "pdo_mysql",
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
            "orm.driver.configs" => array(
                "default" => array(
                    "type" => "yaml",
                    "paths" => array(__DIR__ . "/Resources/doctrine"),
                    "namespace" => 'Entity',
                )
            )
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
    }

    public function boot(Application $app) {
        /* @note @silex FR : ajouter des filtres à Twig
         * EN : add filters to twig
         */
        $app["twig"] = $app->share($app->extend("twig", function ($twig, $app) {
                            $twig->addFilter("md5", new Twig_Filter_Function("md5"));
                            $twig->addFilter("gravatar", new Twig_Filter_Function(function ($email, $size = 128) {
                                        return "http://www.gravatar.com/avatar/" . md5($email) . "?d=mm&s=$size";
                                    }));
                            return $twig;
                        }));

        $app->match("/private", function () {
                    return "this is a private area";
                });
        $app->mount("/", $app["default_controller"]);
    }

}