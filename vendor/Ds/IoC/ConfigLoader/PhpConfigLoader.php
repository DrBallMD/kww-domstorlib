<?php

/**
 * Simple PHP configuration loader
 *
 * @author pahhan
 */
class Ds_IoC_ConfigLoader_PhpConfigLoader implements Ds_IoC_ConfigLoader_ConfigLoaderInterface
{
    private $services = array();

    public function __construct(array $services) {
        $this->services = $services;
    }

    public function load()
    {
        return $this->services;
    }
}

