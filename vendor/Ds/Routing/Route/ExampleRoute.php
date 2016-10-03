<?php

/**
 * Description of ExampleRoute
 *
 * @author pahhan
 */
class Ds_Routing_Route_ExampleRoute extends Ds_Routing_Route_AbstractRoute
{
    private $vars;

    public function __construct($name, array $vars)
    {
        $this->name = $name;
        $this->vars = $vars;
    }

    public function getVar($name)
    {
        return $this->vars[$name];
    }


}

