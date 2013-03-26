<?php

/**
 * Description of CounterInterface
 *
 * @author pahhan
 */
interface Ds_Counter_CounterInterface
{
    /**
     * @param string $key Unique key to identify count
     * @param array $params For count request
     * @return Ds_Counter_CounterInterface Description
     */
    public function need($key, array $params);

    /**
     *
     * @param type $key
     * @return integer Count value
     */
    public function get($key);
}

