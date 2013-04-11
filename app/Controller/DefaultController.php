<?php

namespace Controller;

use Entity\Account;
use Entity\Post;
use Entity\Search;
use Entity\User;
use Form\AccountType;
use Form\LoginType;
use Form\PostSearchType;
use Form\PostType;
use LightOpenID;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * EN : Application controllers <br/>
 * FR : controleur de l'application
 */
class DefaultController implements ControllerProviderInterface {

    /**
     * EN : display post search resutls <br/>
     * FR : affiche  les résultats d'une recherche <br/>
     * @param \Symfony\Component\HttpFoundation\Request $req
     * @param \Silex\Application $app
     * @return string
     */
    function search(Request $req, Application $app) {
        $query = $req->query->get("q");
        $results = array();
        if ($query != null) {
            $search = new Search();
            $search->setExpression($query);
            $results = $app['post_service']->search($search);
        }
        return $app['twig']->render('common/search.result.html.twig', array(
                    "query" => $query,
                    "results" => $results,
        ));
    }

    function profileAddPost(Request $request, Application $app) {
        if ($app["security"]->isGranted("IS_AUTHENTICATED_FULLY")) {
            $redirect = $request->headers->get('referer');
            $post = new Post();
            $post->setAccount($app['current_account']);
            $form = $app["form.factory"]->create(new PostType, $post);
            /* @var $form Form */
            if ("POST" === $request->getMethod()) {
                $form->bind($request);
                if ($form->isValid()) {
                    $app["post_service"]->save($post);
                    $app["session"]->getFlashBag()->add("success", "Post created successfully");
                } else {
                    $app["session"]->getFlashBag()->add("error", $form->getErrorsAsString());
                }
                return $app->redirect($redirect);
            }
            return $app["twig"]->render("user/profile.addPost.html.twig", array(
                        "form" => $form->createView(),
            ));
        } else {
            return "";
        }
    }

    function index(Request $request, Application $app) {
        $limit = $request->query->get('limit', 10);
        $offset = $request->query->get('offset', 0);
        $posts = $app["post_service"]->findBy(array(), array(
            "created_at" => "DESC"), $limit, $offset * $limit);
        return $app["twig"]->render("blog/index.html.twig", array(
                    "posts" => $posts,
                    "offset" => $offset,
                    "limit" => $limit
        ));
    }

    function afterlogin(Request $req, Application $app) {
        /* @var $openid LightOpenID */
        $openid = new LightOpenID("localhost");
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
        /* @var $form Form2 */
        $form = $app['form.factory']->create(new LoginType);
        if ("POST" == $req->getMethod()) {
            $form->bind($req);
            if ($form->isValid()) {
                $datas = $form->getData();
                /* @var $openid LightOpenID */
                $openid = new LightOpenID("localhost");
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

    function profileFollow(Request $req, Application $app) {
        $username = $req->get('username');
        $submit = $req->get('follow');
        if ("POST" === $req->getMethod() && $submit != null) {
            $user_account = $app['current_account'];
            $friend = $app['account_service']->findOneBy(array('username' => $username));
            if ($friend == null)
                $app->abort(500);

            $result = $app['account_service']->follow($user_account, $friend);
            if ($result) {
                $app['session']->getFlashBag()->add("success", "You are now following $username");
            } else {
                $app['session']->getFlashBag()->add("error", "You are already following $username");
            }
            return $app->redirect($req->headers->get('referer'));
        } else {
            $app->abort(500);
        }
    }

    function profileUnfollow(Request $req, Application $app) {
        $username = $req->get('username');
        $submit = $req->get('unfollow');
        if ("POST" === $req->getMethod() && $submit != null) {
            $user_account = $app['current_account'];
            $friend = $app['account_service']->findOneBy(array('username' => $username));
            if ($friend == null)
                $app->abort(500);
            $result = $app['account_service']->unfollow($user_account, $friend);
            if ($result) {
                $app['session']->getFlashBag()->add("success", "You are not following $username anymore");
            } else {
                $app['session']->getFlashBag()->add("error", "You are already following $username");
            }
            return $app->redirect($req->headers->get('referer'));
        } else {
            $app->abort(500);
        }
    }

    function profileIndex(Request $req, Application $app, $username = NULL) {
        $offset = intval($req->query->get('offset', 0));
        $limit = intval($req->query->get('limit', 10));
        $account = $app["account_service"]->findOneBy(array(
            "username" => $username));
        if ($account == NULL) {
            $app["session"]->getFlashBag()->add("error", "Account with username $username not found !");
            return $app->redirect($app["url_generator"]->generate("index"));
        }
        if ($account === $app['current_account']) {
            $posts = $app["post_service"]->findFollowedAccountPosts($app['current_account'],$limit,$offset*$limit);
        } else {
            $posts = $app["post_service"]->findBy(array("account" => $account), array(
                "created_at" => "DESC"), $limit, $offset * $limit);
        }

        return $app["twig"]->render("user/profile.index.html.twig", array(
                    "offset" => $offset,
                    "limit" => $limit,
                    "account" => $account, "posts" => $posts));
    }

    function profileEdit(Request $req, Application $app) {
        $user = $app["security"]->getToken()->getUser();
        $account = $app["account_service"]->findOneBy(array("user" => $user));
        $_account = clone($account);
        if ($account == null) {
            return $app->redirect("/");
        }
        $form = $app["form.factory"]->create(new AccountType(), $account);
        if ("POST" === $req->getMethod()) {
            #$app['orm.em']->detach($account); /* fixing a bug @TODO fix is properly */
            $form->bind($req);
            if ($form->isValid()) {
                $app["account_service"]->save($account);
                $app["session"]->getFlashBag()->add("success", "Account info updated!");
                return $app->redirect(
                                $app["url_generator"]->generate('public_profile', array(
                                    "username" => $account->getUsername())));
            }
        }
        return $app["twig"]->render("user/profile.edit.html.twig", array(
                    "account" => $_account, "form" => $form->createView()));
    }

    function profileMenu(Application $app) {
        $user = $app["security"]->getToken()->getUser();
        $account = $app["account_service"]->findOneBy(array("user" => $user));
        return $app['twig']->render('user/profile.menu.htmh.twig', array(
                    'account' => clone($account)
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function connect(Application $app) {
        /* @var $controllers ControllerCollection */
        $controllers = $app['controllers_factory'];
        $controllers->match("/", array($this, "index"))
                ->bind("index");
        $controllers->match("/login", array($this, "login"))
                ->before(array($this, "mustBeAnonymous"))
                ->bind("login");
        $controllers->match("/afterlogin", array($this, "afterlogin"))
                ->before(array($this, "mustBeAnonymous"))
                ->bind("afterlogin");
        $controllers->match("/private/profile/edit", array($this, "profileEdit"))
                ->bind("profile_edit");
        $controllers->match("/private/profile/menu", array($this, "profileMenu"))
                ->bind("profile_menu");
        $controllers->match("/user/{username}", array($this, "profileIndex"))
                ->bind("public_profile");
        $controllers->match("/private/profile/addpost", array($this, "profileAddPost"))
                ->bind("profile_addpost");
        $controllers->match('/search', array($this, "search"))
                ->bind("search");
        $controllers->post("/private/profile/follow", array($this, 'profileFollow'))
                ->bind("profile_follow");
        $controllers->post('/private/profile/unfollow', array($this, 'profileUnfollow'))
                ->bind('profile_unfollow');
        return $controllers;
    }

    /**
     * FR: si l'utilisateur est autentifié , rediriger vers son profile
     * @param Request $req
     * @param Application $app
     * @return type
     */
    function mustBeAnonymous(Request $req, Application $app) {
        $ctx = $app["security"];
        /* @var $ctx SecurityContext */
        if ($ctx->isGranted("IS_AUTHENTICATED_FULLY")) {
            $account = $app["account_service"]->findOneBy(array("user" => $ctx->getToken()->getUser()));
            return $app->redirect($app["url_generator"]->generate("public_profile", array("username" => $account->getUsername())));
        }
    }

}

