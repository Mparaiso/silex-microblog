<?php
use Silex\ServiceProviderInterface;
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


class Config implements ServiceProviderInterface
{


    public function register(Application $app)
    {
        /**
         * EN : service definitions
         * FR : définition des services et configuration de l'application
         */
        $app['root'] = dirname(__DIR__);
        $app['temp'] = __DIR__ . "/../temp/";

        // local services
        $app["openid_providers"] = array(
            array('name' => 'Google', 'url' => 'https://www.google.com/accounts/o8/id'),
            array('name' => 'Yahoo', 'url' => 'https://me.yahoo.com'),
            array('name' => 'AOL', 'url' => 'http://openid.aol.com/<add your aol username here>'),
            array('name' => 'Flickr', 'url' => 'http://www.flickr.com/<add your flickr username here>'),
            array('name' => 'MyOpenID', 'url' => 'https://www.myopenid.com'),
            array("name" => "Wordpress", 'url' => "http://<add your wordpress username here>.wordpress.com/"),
        );
        /* user provider for security */
        $app['user_provider'] = $app->share(function (Application $app) {
            return new EntityUserProvider($app["orm.manager_registry"], '\Entity\User', "email");
        });
        /* user service */
        $app["user_service"] = $app->share(function ($app) {
            return new UserService($app["orm.em"]);
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
            "twig.path"    => array(__DIR__ . "/Resources/views/"),
            "twig.options" => array(
                "cache" =>  $app["temp"] . "/twig/",
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
                "driver" => "pdo_sqlite",
                "path"   => $app['root'] . "/db/blog.sqlite",
            )
        ));
        $app->register(new DoctrineORMServiceProvider, array(
            "orm.proxy_dir"      => __DIR__ . "/Proxy/",
            "orm.driver.configs" => array(
                "default" => array(
                    "type"      => "yaml",
                    "paths"     => array(__DIR__ . "/Resources/doctrine"),
                    "namespace" => 'Entity',
                )
            )
        ));
        $app->register(new SecurityServiceProvider, array(
            "security.firewalls"      => array(
                "protected" => array(
                    "anonymous" => TRUE,
                    "pattern"   => "^/",
                    "form"      => array(
                        "login_path"          => "/login",
                        "check_path"          => "/private/authenticate",
                        "default_target_path" => "/private/profile"
                    ),
                    "logout"    => array(
                        "logout_path" => "/private/logout",
                        "target"      => "/",
                    ),
                    "users"     => $app->share(function ($app) {
                        return $app["user_provider"];
                    })
                )
            ),
            "security.role_hierarchy" => array("ROLE_USER" => array()),
            "security.access_rules"   => array(
                array("^/private", "ROLE_USER"),
            )
        ));

        $app->match("/private", function () {
            return "this is a private area";
        });


        $app->mount("/", $app["default_controller"]);
    }

    public function boot(Application $app)
    {
        $app["twig"] = $app->share($app->extend("twig", function ($twig, $app) {
            $twig->addFilter("md5", new Twig_Filter_Function("md5"));
            return $twig;
        }));
    }
}