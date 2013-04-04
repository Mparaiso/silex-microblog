<?php
use Silex\Provider\HttpCacheServiceProvider;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Mparaiso\Provider\LightOpenIdServiceProvider;
use Silex\Provider\SecurityServiceProvider;
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

class App extends Silex\Application
{
    public function __construct(array $values = array())
    {
        parent::__construct($values);

        /**
         * EN : service definitions
         * FR : définition des services et configuration de l'application
         */
        $this['root'] = dirname(__DIR__);
        $this['temp'] = __DIR__ . "/../temp/";
        if (getenv("environment") === "development") {
            error_reporting(E_ALL);
            $this["debug"] = TRUE;
        }

        $this['user_provider'] = $this->share(function (Application $app) {
            return new EntityUserProvider($app["orm.manager_registry"], '\Mparaiso\Blog\Entity\User');
        });
        $this->register(new HttpCacheServiceProvider, array(
            "http_cache.cache_dir" => $this['temp'] . "/cache/",
        ));
        $this->register(new TwigServiceProvider, array(
            "twig.options" => array(
                "cache" => $this["temp"] . "/twig/"
            ),
        ));
        $this->register(new MonologServiceProvider, array(
            "monolog.logfile" => $this['temp'] . "/" . date('Y-m-d') . ".text",
        ));
        $this->register(new ConsoleServiceProvider);
        $this->register(new BlogServiceProvider);
        $this->register(new FormServiceProvider);
        $this->register(new ValidatorServiceProvider);
        $this->register(new TranslationServiceProvider, array("locale_fallback" => "en"));
        $this->register(new SessionServiceProvider);
        $this->register(new UrlGeneratorServiceProvider);
        $this->register(new DoctrineServiceProvider, array(
            "db.options" => array(
                "driver" => "pdo_sqlite",
                "path"   => $this['root'] . "/db/blog.sqlite",
            )
        ));
        $this->register(new DoctrineORMServiceProvider);
        $this->register(new SecurityServiceProvider, array(
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
                        "logout_path" => "/admin/logout",
                        "target"      => "/",
                    ),
                    "users"     => $this->share(function ($app) {
                        return $app["user_provider"];
                    })
                )
            ),
            "security.role_hierarchy" => array("ROLE_USER" => array()),
            "security.access_rules"   => array(
                array("^/private", "ROLE_USER"),
            )
        ));
        $this->register(new LightOpenIdServiceProvider);

        /***
         * Controllers
         */
        $this->match("/index", function (Request $req, Application $app) {
            #@note @silex forward request
            $sub = $req::create("/");
            return $app->handle($sub, HttpKernelInterface::SUB_REQUEST);
        });
        $this->match("/private", function () {
            return "this is a private area";
        });
        $this->match("/private/profile", function () {
            return "your are logged in ! this is your private profile area";
        });

        $this->mount("/", $this["blog.default_controller"]);
    }

}


