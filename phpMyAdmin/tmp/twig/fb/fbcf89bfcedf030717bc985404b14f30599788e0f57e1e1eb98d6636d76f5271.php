<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* database/structure/overhead.twig */
class __TwigTemplate_5119206ae1849e61a29d9bd120e95639e37d8c9e0047639e065946895a1cf038 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<a href=\"";
        echo PhpMyAdmin\Url::getFromRoute("/table/structure", ($context["table_url_params"] ?? null));
        echo "#showusage\" id=\"overhead\">
  <span>";
        // line 2
        echo twig_escape_filter($this->env, ($context["formatted_overhead"] ?? null), "html", null, true);
        echo "</span>&nbsp;
  <span class=\"unit\">";
        // line 3
        echo twig_escape_filter($this->env, ($context["overhead_unit"] ?? null), "html", null, true);
        echo "</span>
</a>
";
    }

    public function getTemplateName()
    {
        return "database/structure/overhead.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  46 => 3,  42 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/structure/overhead.twig", "/home/neotericschools/public_html/mgs.gptech.pk/phpMyAdmin/templates/database/structure/overhead.twig");
    }
}
