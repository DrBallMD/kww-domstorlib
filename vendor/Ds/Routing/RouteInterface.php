<?php

/**
 *
 * @author pahhan
 */
interface Ds_Routing_RouteInterface
{
    public function getName();
    public function getVar($name);
    public function generateUri(array $params);
}