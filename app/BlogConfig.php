<?php

#use Symfony\Component\Console\Application;

use Controller\DefaultController;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Service\AccountService;
use Service\PostService;
use Service\UserService;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;

/*
 * EN : Specific blog configurations so that config can be used
 * in another application
 * FR : configuration spécifique à l'application microblog , cette configuration 
 * peut ensuite être utilisée dans une autre application 
 */

class BlogConfig implements ServiceProviderInterface {

    public function boot(Application $app) {

        /* @note @silex FR : ajouter des filtres à Twig
         * EN : add filters to twig
         */
        if (!isset($app['twig']))
            throw new \Exception("You need to register TwigServiceProvider");

        $app["twig"] = $app->share($app->extend("twig", function ($twig) {
                            $twig->addFilter("md5", new Twig_Filter_Function("md5"));
                            $twig->addFilter("gravatar", new Twig_Filter_Function(function ($email, $size = 128) {
                                        return "http://www.gravatar.com/avatar/" . md5($email) . "?d=mm&s=$size";
                                    }));
                            return $twig;
                        })
        );

        if (!isset($app['translator']))
            throw new \Exception("You need to register TranslationServiceProvider");
        $domains = $app['translator.domains'];
        $domains['messages'] = array(
            "en" => array(
                "_description" => "Microblog is a microbloggin plateform built with Silex the PHP framework.
Share messages , follow other users , and have fun!"
            ),
            "fr" => array(
                "Welcome" => "Bienvenue",
                "Welcome to microblog" => "Bienvenue sur Microblog",
                "this is you" => "C'est vous",
                "Nickname" => "Surnom",
                "Last Seen" => "Dernière visite",
                "edit account" => "Configurer le profil",
                "Followers" => "Abonnés",
                "People i follow" => "Mes abonnements",
                "Logout" => "Déconnexion",
                "Login" => "Connexion",
                "Sign in" => "Connectez vous",
                "Search posts" => "Rechercher un message",
                "User profile" => "Profil",
                "Post message" => "Ecrire un message",
                "First" => "Début",
                "Prev" => "Précédent",
                "Next" => "Suivant",
                "Please enter your OpenID, or select one of the providers below:" => "Entrez l'url de votre openid , ou choisissez un fournisseur ci-dessous :",
                "_description"=>"Microblog est une plateforme de microblogging ! Partagez des messages , Abonnez vous aux messages d'autres utilisateurs, amusez vous !"
            ),
        );
        $app["translator.domains"] = $domains;

        $app->match("/private", function () {
                    return "this is a private area";
                });
        $app->mount("/", $app["default_controller"]);
    }

    public function register(Application $app) {
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
                }
        );
        /* default controller */
        $app["default_controller"] = $app->share(function () {
                    return new DefaultController();
                }
        );


        /* manage Entities described by yaml datas */
        if (!isset($app['orm.chain_driver']))
            throw new \Exception("You need to register DoctrineORMServiceProvider");

        $app['orm.chain_driver'] = $app->share($app->extend('orm.chain_driver', function($driver, $app) {
                            /* @var $driver  MappingDriverChain */
                            $driver->addDriver(new YamlDriver(array(__DIR__ . "/Resources/doctrine")), "Entity");
                            return $driver;
                        })
        );
    }

}

