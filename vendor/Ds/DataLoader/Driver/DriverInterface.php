<?php

/**
 * Driver transforms data source to array.
 * 
 * @author pahhan
 */
interface Ds_DataLoader_Driver_DriverInterface
{
    /**
     * @param string $source
     * @return FALSE|array
     */
    public function read($source);
}

