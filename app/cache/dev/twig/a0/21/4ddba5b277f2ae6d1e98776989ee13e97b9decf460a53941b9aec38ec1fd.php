<?php

/* SdzBlogBundle:Blog:ajouter.html.twig */
class __TwigTemplate_a0214ddba5b277f2ae6d1e98776989ee13e97b9decf460a53941b9aec38ec1fd extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SdzBlogBundle::layout.html.twig");

        $this->blocks = array(
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "SdzBlogBundle::layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "
  <h2>Ajouter un article</h2>

  ";
        // line 9
        $this->env->loadTemplate("SdzBlogBundle:Blog:formulaire.html.twig")->display($context);
        // line 10
        echo "
  <p>
     Attention : cet article sera ajouté directement
     sur la page d'accueil après validation du formulaire.
  </p>

";
    }

    public function getTemplateName()
    {
        return "SdzBlogBundle:Blog:ajouter.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  38 => 10,  36 => 9,  31 => 6,  28 => 5,);
    }
}
