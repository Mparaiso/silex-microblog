<?php

/* blog.index */
class __TwigTemplate_50016652536e4511e5bc6cb563a6111a extends Twig_Template
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
    <title>";
        // line 3
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : $this->getContext($context, "title")), "html", null, true);
        echo " - microblog</title>
    </head>
    <body>
    <h1>Hello, ";
        // line 6
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
        return array (  29 => 6,  23 => 3,  19 => 1,);
    }
}
