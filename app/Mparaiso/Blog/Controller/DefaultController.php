<?php

namespace Mparaiso\Blog\Controller;

use Silex\ControllerProviderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
        $app["logger"]->info("logger test");
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

    function openIdVerify(Request $req, Application $app)
    {
        /* @var $openid \LightOpenID */
        $openid = new \LightOpenID("localhost");
        //$openid->identity = $req->query->get('openid_identity');
        if ($openid->mode == "cancel") {
            $app->abort(500, 'User has canceled authentication!');
        } else {
            if ($openid->validate()) {
            #@note @openid FR : identité valide
                $app['logger']->info("valid openid auth with attributes : " . json_encode($openid->getAttributes()) . $req->query->get('openid_identity'));

            } else {
                $app->abort(500, 'opend id auth failed');
            }
        }
    }

    function login(Request $req, Application $app)
    {
        $n = $app['blog.ns'];
        /* @var $form Form */
        $form = $app['form.factory']->create(new LoginType);
        if ("POST" == $req->getMethod()) {
            $form->bind($req);
            if ($form->isValid()) {
                $datas = $form->getData();
                /* @var $openid \LightOpenID */
                $openid = new \LightOpenID("localhost");
                // set the return url
                $openid->returnUrl = $app["url_generator"]->generate("$n.openidverify", array(), TRUE);
                if (!$openid->mode) {
                    $openid->identity = $datas["openid"];
                    # from the provider. Remove them if you don't need that data.
                    $openid->required = array('contact/email', 'namePerson/friendly', "pref/language", "contact/country/home", "pref/timezone");
                    //$openid->optional = array('namePerson' );
                    return $app->redirect($openid->authUrl());
                }
//                /* @var $session Session */
//                $session = $app['session'];
//                $session->getFlashBag()->add("success", "form is valid and your openid url is $data[openid]");
//                return $app->redirect($app['url_generator']->generate("$n.index"));
            }
        }
        return $app['twig']->render("$n.login", array(
            "form"      => $form->createView(),
            "providers" => $app["$n.openid_providers"]
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
        $controllers->match("/openidconnect", array($this, "openIdVerify"))
            ->bind("$n.openidverify");
        return $controllers;
    }
}
