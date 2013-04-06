<?php

namespace Controller;

use Silex\ControllerProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Entity\User;
use Entity\Account;
use Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

class DefaultController implements ControllerProviderInterface {

    function index(Application $app) {
        //$user  = array("nickname" => "John doe");
        $posts = array(
            array(
                "author" => array("nickname" => "Jesus Christ"),
                "body" => "The holy bible"
            ),
            array(
                "author" => array("nickname" => "Stan Lee"),
                "body" => "The amazing spiderman",
            )
        );
        return $app["twig"]->render("blog/index.html.twig", array(
                    "posts" => $posts
        ));
    }

    function afterlogin(Request $req, Application $app) {
        /* @var $openid \LightOpenID */
        $openid = new \LightOpenID("localhost");
        if ($openid->mode == "cancel") {
            $app->abort(500, 'User has canceled authentication!');
        } else {
            if ($openid->validate()) {
                #@note @openid FR : identité valide
                $attributes = $openid->getAttributes();
                $up = $app['user_provider'];
                try {
                    $user = $up->loadUserByUsername($req->query->get("openid_identity"));
                    $account = $app["account_service"]->findOneBy(array("user" => $user));
                } catch (UsernameNotFoundException $e) {
                    $user = new User();
                    $user->setUsername($req->query->get("openid_identity"));
                    $user->setSalt(uniqid());
                    $plainPassword = uniqid();
                    $user->setPassword(uniqid());
                    $encoder = $app["security.encoder_factory"];
                    $user->setPassword($encoder->getEncoder($user)->encodePassword($plainPassword, $user->getSalt()));
                    $account = new Account();
                    $account->setUser($user);
                    if (isset($attributes['contact/email'])) {
                        $account->setEmail($attributes['contact/email']);
                    }
                    if (isset($attributes['namePerson/friendly'])) {
                        $account->setUsername($attributes['namePerson/friendly']);
                    }
                    $app["logger"]->info("account is equal to :" . print_r($account, true));
                    $app['account_service']->register($account);
                }
                #@note @silex FR : puisque l'utilisateur a déja été authentifié via openid , configurer le token
                $app["account_service"]->save($account);
                $token = new UsernamePasswordToken($user, $user->getPassword(), "protected", $user->getRoles());
                $app["security"]->setToken($token);
                $app["session"]->getFlashBag()->add("success", "You are successfully logged in !");
                return $app->redirect($app['url_generator']->generate("public_profile", array("username" => $account->getUsername())));
            } else {
                $app->abort(500, 'opend id auth failed');
            }
        }
    }

    function login(Request $req, Application $app) {
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
                    $openid->required = array('contact/email', /* "pref/language", */
                        "contact/country/home", "pref/timezone");
                    $openid->optional = array('namePerson/friendly');
                    return $app->redirect($openid->authUrl());
                }
            }
        }
        return $app['twig']->render("user/login.html.twig", array(
                    "form" => $form->createView(),
                    "providers" => $app["openid_providers"]
        ));
    }

    function profileIndex(Request $req, Application $app, $username = NULL) {
        $account = $app["account_service"]->findOneBy(array("username" => $username));
        if ($account == NULL) {
            $app["session"]->getFlashBag()->add("error", "Account with username $username not found !");
            return $app->redirect($app["url_generator"]->generate("index"));
        } else {
            $posts = $app["post_service"]->findBy(array("user" => $account->getUser()));
            return $app["twig"]->render("user/profile.index.html.twig", 
                    array("account" => $account, "posts" => $posts));
        }
    }

    function profileEdit(Request $req, Application $app) {
        $user = $app["security"]->getToken()->getUser();
        $account = $app["account_service"]->findOneBy(array("user" => $user));
        if ($account == null) {
            return $app->redirect("/");
        }
        $form = $app["form.factory"]->create(new \Form\AccountType(), $account);
        if ("POST" === $req->getMethod()) {
            $form->bind($req);
            if ($form->isValid()) {
                $app["account_service"]->save($account);
                $app["session"]->getFlashBag()->add("success", "Account info updated!");
                return $app->redirect(
                                $app["url_generator"]->generate('public_profile', 
                                        array("username" => $account->getUsername())));
            }
        }
        return $app["twig"]->render("user/profile.edit.html.twig", 
                array("account" => $account, "form" => $form->createView()));
    }

    /**
     * {@inheritdoc}
     */
    public function connect(Application $app) {
        /* @var $controllers \Silex\ControllerCollection */
        $controllers = $app['controllers_factory'];
        $controllers->match("/", array($this, "index"))
                ->bind("index");
        $controllers->match("/login", array($this, "login"))
                ->bind("login");
        $controllers->match("/afterlogin", array($this, "afterlogin"))
                ->bind("afterlogin");
        $controllers->match("/private/profile/edit", array($this, "profileEdit"))
                ->bind("profile_edit");
        $controllers->match("/user/{username}", array($this, "profileIndex"))
                ->bind("public_profile");

        return $controllers;
    }

}

