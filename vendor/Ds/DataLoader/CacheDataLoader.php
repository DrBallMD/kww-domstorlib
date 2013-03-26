<?php

/**
 * Example of DataLoader with some cache system
 *
 * @author pahhan
 */
class Ds_DataLoader_CacheDataLoader extends Ds_DataLoader_BaseDataLoader
{
    /**
     * Returns data for one piece client info data
     * @param string $name Name of client info data
     * @param array $info_data  Info data for request
     * @param string $client_key
     * @return array
     * @throws Ds_DataLoader_DataLoaderException
     */
    protected function readOneData($name, array $info_data, $client_key)
    {
        /*
         * $cache_key = calculate_key($name, array $info_data, $client_key);
         * if( $result = $cache_driver->get($cache_key) ) return $result;
         */
        $result = parent::readOneData($name, $info_data, $client_key);

        /* $cache_driver->add($cache_key, $time); */

        return $result;
    }
}

