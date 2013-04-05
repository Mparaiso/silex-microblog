<?php

namespace Controller;


use Silex\ControllerProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Entity\User;
use Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;


class DefaultController implements ControllerProviderInterface
{

    function index(Application $app)
    {
        //$user  = array("nickname" => "John doe");
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
        return $app["twig"]->render("blog/index.html.twig", array(
            "posts" => $posts
        ));
    }

    function afterlogin(Request $req, Application $app)
    {
        /* @var $openid \LightOpenID */
        $openid = new \LightOpenID("localhost");
        //$openid->identity = $req->query->get('openid_identity');
        if ($openid->mode == "cancel") {
            $app->abort(500, 'User has canceled authentication!');
        } else {
            if ($openid->validate()) {
                #@note @openid FR : identité valide
                $attributes = $openid->getAttributes();
                if (!isset($attributes['contact/email']) || $attributes['contact/email'] == NULL) {
                    $app->abort("500", "the open id service must return at least an email");
                }
                $up = $app['user_provider'];
                try {
                    $user = $up->loadUserByUsername($attributes['contact/email']);
                } catch (UsernameNotFoundException $e) {
                    $app['logger']->info("creating user");
                    // create a new user , persist it manually
                    $user = new User();
                    $user->setEmail($attributes['contact/email']);
                    if (isset($attributes['namePerson/friendly'])) {
                        $user->setUsername($attributes['namePerson/friendly']);
                    } else {
                        $split = preg_split('/\@/', $user->getEmail());
                        $user->setUsername($split[0]);
                    }
                    $user->setSalt(uniqid("", TRUE));
                    $plainPassword = uniqid("", TRUE);
                    $user->setPassword(uniqid("", TRUE));
                    $encoder = $app["security.encoder_factory"];
                    $user->setPassword($encoder->getEncoder($user)->encodePassword($plainPassword, $user->getSalt()));
                    $app["user_service"]->register($user);
                }
                #@note @silex FR : puisque l'utilisateur a déja été authentifié via openid , configurer le token
                $token = new UsernamePasswordToken($user, $user->getPassword(), "protected", $user->getRoles());
                $app["security"]->setToken($token);
                $app["session"]->getFlashBag()->add("success", "You are successfully logged in !");
                return $app->redirect($app['url_generator']->generate("private_profile"));
            } else {
                $app->abort(500, 'opend id auth failed');
            }
        }
    }

    function login(Request $req, Application $app)
    {
        /* @var $form Form */
        $form = $app['form.factory']->create(new LoginType);
        if ("POST" == $req->getMethod()) {
            $form->bind($req);
            if ($form->isValid()) {
                $datas = $form->getData();
                /* @var $openid \LightOpenID */
                $openid = new \LightOpenID("localhost");
                // set the return url
                $openid->returnUrl = $app["url_generator"]->generate("afterlogin", array(), TRUE);
                if (!$openid->mode) {
                    $openid->identity = $datas["openid"];
                    # from the provider. Remove them if you don't need that data.
                    $openid->required = array('contact/email', /* "pref/language",*/
                        "contact/country/home", "pref/timezone");
                    $openid->optional = array('namePerson/friendly');
                    return $app->redirect($openid->authUrl());
                }
            }
        }
        return $app['twig']->render("user/login.html.twig", array(
            "form"      => $form->createView(),
            "providers" => $app["openid_providers"]
        ));
    }

    function profile(Request $req, Application $app)
    {
        return $app["twig"]->render("user/profile.html.twig");
    }

    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        /* @var $controllers \Silex\ControllerCollection */
        $controllers = $app['controllers_factory'];
        $controllers->match("/", array($this, "index"))
            ->bind("index");
        $controllers->match("/login", array($this, "login"))
            ->bind("login");
        $controllers->match("/afterlogin", array($this, "afterlogin"))
            ->bind("afterlogin");
        $controllers->match("/private/profile", array($this, "profile"))
            ->bind("private_profile");
        return $controllers;
    }
}


