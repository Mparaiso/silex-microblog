<?php

namespace Mparaiso\Blog\Controller;

use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Mparaiso\Blog\Form\LoginType;
use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Form;

class DefaultController implements ControllerProviderInterface
{
    function index(Application $app)
    {
        $n     = $app['blog.ns'];
        $user  = array("nickname" => "John doe");
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
        return $app["twig"]->render("$n.index", array(
            "user" => $user, "posts" => $posts
        ));
    }

    function login(Request $req, Application $app)
    {
        $n = $app['blog.ns'];
        /* @var $form Form */
        $form = $app['form.factory']->create(new LoginType);
        if ("POST" == $req->getMethod()) {
            $form->bind($req);
            if ($form->isValid()) {
                /* @var $session Session */
                $session = $app['session'];
                $data    = $form->getData();
                $session->getFlashBag()->add("success", "form is valid and your openid url is $data[openid]");
                return $app->redirect($app['url_generator']->generate("$n.index"));
            }
        }
        return $app['twig']->render("$n.login", array(
            "form" => $form->createView(),
            "providers"=>$app["$n.openid_providers"]
        ));
    }

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $n = $app['blog.ns'];
        /* @var $controllers \Silex\ControllerCollection */
        $controllers = $app['controllers_factory'];
        $controllers->match("/", array($this, "index"))
            ->bind("$n.index");
        $controllers->match("/login", array($this, "login"))
            ->bind("$n.login");
        return $controllers;
    }
}