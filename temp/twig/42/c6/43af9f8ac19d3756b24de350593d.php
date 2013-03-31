<?php

/* blog.index */
class __TwigTemplate_42c643af9f8ac19d3756b24de350593d extends Twig_Template
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
    </body>
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
        return array (  39 => 10,  35 => 8,  31 => 6,  25 => 4,  23 => 3,  19 => 1,);
    }
}
