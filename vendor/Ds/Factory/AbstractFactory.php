<?php

/**
 * Description of AbstractFactory
 *
 * @author pahhan
 */
abstract class Ds_Factory_AbstractFactory implements Ds_IoC_ContainerAwareInterface
{
    public function getContainer()
    {
        return Ds_IoC_Container::instance();
    }
}

