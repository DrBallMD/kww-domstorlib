<?php

/**
 * Loads data for clients. After all clients are registered, you need call load()
 * method. Loader aggregates information (returned by getLoaderInfo() method)
 * about what every client is needed for. When data received, loaders calls
 * onDataReceived() method of each client and pass received data to it.
 * Loader devides data to clients relies on value of 'key', which returned by
 * getLoaderInfo() method, so every client should must return unique key.
 * 
 * @author pahhan
 */
interface Ds_DataLoader_DataLoaderInterface
{
    public function registerClient(Ds_DataLoader_DataLoaderClientInterface $client);
    public function load();
}