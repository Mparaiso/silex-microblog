<?php

namespace Mparaiso\Provider;


use Silex\ServiceProviderInterface;
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
        $n                       = $this->ns;
        $app["blog.ns"]          = $n;
        $app["$n.resource.path"] = __DIR__ . "/../Blog/Resource";

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


    }
}