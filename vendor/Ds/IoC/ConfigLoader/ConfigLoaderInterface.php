<?php

/**
 * Loads configuration for ioc container
 * 
 * @author pahhan
 */
interface Ds_IoC_ConfigLoader_ConfigLoaderInterface
{
    /**
     * @return array
     */
    public function load();
}
