<?php

/**
 * Adapter for Twig template engine
 *
 * @author pahhan
 */
class Ds_Templating extends Spv_Templating_AdapterTwig
{
    public static function repeatString($str, $times = 1)
    {
        $out = '';
        for($i = 0; $i < $times; $i++)
        {
            $out.= $str;
        }
        return $out;
    }


    public function __construct(array $twig_params)
    {
        $dir = dirname(__FILE__);
        $loader = new Twig_Loader_Filesystem( array() ); // Templates will be loaded from filesystem

        $loader->addPath(Spv_Form_Environment::getViewsPath().'/fields/twig', 'spv'); // Add path to Spv form templates
        $loader->addPath($dir.'/Form/view', 'form'); // Add path to Ds form templates
        $loader->addPath($dir.'/List/view', 'list'); // Add path to Ds list templates
        $loader->addPath($dir.'/Detail/view', 'detail'); // Add path to Ds detail templates

        $twig = new Twig_Environment($loader, $twig_params); // Create twig environment

        $test = new Twig_SimpleTest('numeric', 'is_numeric');
        $filter = new Twig_SimpleFilter('repeat', array('Ds_Templating', 'repeatString'));

        $twig->addTest($test);
        $twig->addFilter($filter);

        parent::__construct($twig);
    }
}