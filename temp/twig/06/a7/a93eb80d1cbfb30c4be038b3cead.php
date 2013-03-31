<?php

/* blog.index */
class __TwigTemplate_06a7a93eb80d1cbfb30c4be038b3cead extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<html>
    <head>
    ";
        // line 3
        if (array_key_exists("title", $context)) {
            // line 4
            echo "    <title>";
            echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : $this->getContext($context, "title")), "html", null, true);
            echo " - microblog</title>
    ";
        } else {
            // line 6
            echo "    <title>Welcome to microblog</title>
    ";
        }
        // line 8
        echo "    </head>
    <body>
    <h1>Hello, ";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["user"]) ? $context["user"] : $this->getContext($context, "user")), "nickname"), "html", null, true);
        echo "</h1>
    ";
        // line 11
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["posts"]) ? $context["posts"] : $this->getContext($context, "posts")));
        foreach ($context['_seq'] as $context["_key"] => $context["post"]) {
            // line 12
            echo "    <p>";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["post"]) ? $context["post"] : $this->getContext($context, "post")), "author"), "nickname"), "html", null, true);
            echo " wrote : <b>";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["post"]) ? $context["post"] : $this->getContext($context, "post")), "body"), "html", null, true);
            echo "</b></p>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['post'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 14
        echo "    </body>
</html>";
    }

    public function getTemplateName()
    {
        return "blog.index";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  58 => 14,  47 => 12,  43 => 11,  39 => 10,  35 => 8,  31 => 6,  25 => 4,  23 => 3,  19 => 1,);
    }
}
