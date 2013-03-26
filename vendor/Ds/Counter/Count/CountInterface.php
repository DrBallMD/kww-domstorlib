<?php

/**
 * Used in Ds_Counter_BaseCounter
 * @author pahhan
 */
interface Ds_Counter_Count_CountInterface
{
    /**
     * @param string $key Unique key
     * @param array $params Params for request
     */
    public function __construct($key, array $params);

    /**
     * @return integer Returns count value
     */
    public function get();
}