<?php

/**
 * DataLoaderClient can be registered to DataLoader. Client must returns information
 * by getLoaderInfo() method. Format of this information is defined by concrete
 * loader. When loader receive data it calls client onDataReceived() method and
 * pass data to it.
 *
 * @author pahhan
 */
interface Ds_DataLoader_DataLoaderClientInterface
{
    /**
     * Calls by loader when it receive data
     */
    public function onDataReceived(array $data);

    /**
     * Returns information for loader
     * @return array
     */
    public function getLoaderInfo();
}

