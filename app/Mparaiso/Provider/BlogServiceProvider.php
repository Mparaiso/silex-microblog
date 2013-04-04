<?php

namespace Mparaiso\Provider;


use Silex\ServiceProviderInterface;
use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Mparaiso\Blog\Controller\DefaultController;
use Silex\Application;

class BlogServiceProvider implements ServiceProviderInterface
{
    protected $ns;

    function __construct($namespace = "blog")
    {
        $this->ns = $namespace;
    }

    public function register(Application $app)
    {
        $n                            = $this->ns;
        $app["blog.ns"]               = $n;
        $app["$n.resource.path"]      = __DIR__ . "/../Blog/Resource";
        $app["$n.default_controller"] = $app->share(function ($app) {
            return new DefaultController;
        });
        $app["$n.openid_providers"]   = array(
            array('name' => 'Google', 'url' => 'https://www.google.com/accounts/o8/id'),
            array('name' => 'Yahoo', 'url' => 'https://me.yahoo.com'),
            array('name' => 'AOL', 'url' => 'http://openid.aol.com/<username>'),
            array('name' => 'Flickr', 'url' => 'http://www.flickr.com/<username>'),
            array('name' => 'MyOpenID', 'url' => 'https://www.myopenid.com')
        );

    }


    public function boot(Application $app)
    {
        $n = $this->ns;
        $t = require $app["$n.resource.path"] . "/views/templates.php";
        foreach ($t as $name => $value) {
            $templates["$n.$name"] = $value;
        }
        $twigTemplates         = $app["twig.templates"];
        $app['twig.templates'] = array_merge($twigTemplates, $templates);
        $app['orm.chain_driver']->addDriver(new YamlDriver(array($app["$n.resource.path"]."/doctrine")),'Mparaiso');

    }
}