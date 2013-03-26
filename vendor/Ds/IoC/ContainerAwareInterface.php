<?php

/**
 * Realisations of this interface should returns ioc conatiner object
 * @author pahhan
 */
interface Ds_IoC_ContainerAwareInterface
{
    /**
     * @return Ds_IoC_Container
     */
    public function getContainer();
}
