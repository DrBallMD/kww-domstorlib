<?php

/**
 *
 * @author pahhan
 */
interface Ds_DataLoader_Reader_ReaderInterface
{
    /**
     * Read URL with params
     * @param string $url
     * @param array $params
     * @return string Url content
     */
    public function read($url, array $params);
}