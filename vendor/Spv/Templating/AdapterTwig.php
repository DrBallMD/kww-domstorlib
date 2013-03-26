<?php

/**
 * Description of Adapter
 *
 * @author pahhan
 */
class Spv_Templating_AdapterTwig implements Spv_Templating_TemplatingInterface
{
    private $twig;

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render($template, array $vars = array())
    {
        return $this->twig->render($template, $vars);
    }
}

