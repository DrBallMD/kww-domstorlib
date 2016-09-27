<?php

/**
 *
 * @author pahhan
 */
interface Ds_Routing_RouterInterface
{
    public function setRoute(Ds_Routing_RouteInterface $route);

    /**
     * 
     * @param string $url
     * @return Ds_Routing_RouteInterface Description
     */
    public function defineRoute($url);
}
