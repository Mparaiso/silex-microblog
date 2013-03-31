<?php

/* blog.base */
class __TwigTemplate_ba7758052b4ee73bb214bf186598c4b6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
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
    ";
        // line 10
        $this->displayBlock('content', $context, $blocks);
        // line 12
        echo "    </body>
</html>";
    }

    // line 10
    public function block_content($context, array $blocks = array())
    {
        // line 11
        echo "    ";
    }

    public function getTemplateName()
    {
        return "blog.base";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  50 => 11,  47 => 10,  42 => 12,  40 => 10,  36 => 8,  32 => 6,  24 => 3,  20 => 1,  38 => 5,  34 => 4,  29 => 3,  26 => 4,);
    }
}
