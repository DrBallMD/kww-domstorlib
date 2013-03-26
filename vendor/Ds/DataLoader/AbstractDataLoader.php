<?php

/**
 * AbstractDataLoader uses Driver to transform data source to array.
 *
 * @author pahhan
 */
abstract class Ds_DataLoader_AbstractDataLoader implements Ds_DataLoader_DataLoaderInterface
{
    /**
     * @var Ds_DataLoader_DataLoaderClientInterface[]
     */
    protected $clients = array();

    /**
     * @var Ds_DataLoader_Driver_DriverInterface
     */
    protected $driver;

    /**
     *
     * @var Ds_DataLoader_Reader_ReaderInterface
     */
    protected $reader;

    /**
     *
     * @var Doctrine_Cache_Interface
     */
    protected $cache_driver;

    /**
     * @param Ds_DataLoader_Driver_DriverInterface $driver
     */
    public function __construct(Ds_DataLoader_Driver_DriverInterface $driver,
            Ds_DataLoader_Reader_ReaderInterface $reader)
    {
        $this->driver = $driver;
        $this->reader = $reader;
    }

    /**
     *
     * @param Ds_DataLoader_DataLoaderClientInterface $client
     * @throws Ds_DataLoader_DataLoaderException If getLoaderInfo() don't contain 'key' value
     */
    public function registerClient(Ds_DataLoader_DataLoaderClientInterface $client)
    {
        $info = $client->getLoaderInfo();

        if( !isset($info['key']) )
            throw new Ds_DataLoader_DataLoaderException('Undefined key in loader info');

        $key = $info['key'];
        $this->clients[$key] = $client;
    }

    /**
     * Loads data by getData() and calls client's onDataReceived() method
     * @throws Ds_DataLoader_DataLoaderException If getData() don't returns an array
     */
    public function load()
    {
        $data = $this->getData();

        if( !is_array($data) )
            throw new Ds_DataLoader_DataLoaderException('getData() method must returns an array');

        /* @var $client Ds_DataLoader_DataLoaderClientInterface */
        foreach ($this->clients as $key => $client)
        {
            if( isset($data[$key]) )
                $client->onDataReceived($data[$key]);
        }
    }

    /**
     * Sets cache driver
     * @param Doctrine_Cache_Interface $driver
     */
    public function setCacheDriver(Doctrine_Cache_Interface $driver)
    {
        $this->cache_driver = $driver;
    }

    /**
     * @return array
     */
    abstract protected function getData();
}

