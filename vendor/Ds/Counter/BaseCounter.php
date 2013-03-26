<?php

/**
 * Collects requests for count information.
 *
 * @author pahhan
 */
class Ds_Counter_BaseCounter implements Ds_Counter_CounterInterface
{
    /**
     * @var type Ds_Counter_Count_BaseCount[]
     */
    protected $counts = array();

    /**
     * Params common for all counts
     * @var type
     */
    protected $params = array();

    /**
     * @var Ds_DataLoader_DataLoaderInterface
     */
    protected $data_loader;

    public function __construct(Ds_DataLoader_DataLoaderInterface $loader)
    {
        $this->data_loader = $loader;
    }

    /**
     * Sets common params
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * Registers request for count with key and params
     * @param string $key
     * @param array $params Individual params, merged with common
     * @return \Ds_Counter_BaseCounter
     * @throws Ds_Counter_CounterException If key already exists
     */
    public function need($key, array $params = array())
    {
        if( isset($this->counts[$key]) )
            throw new Ds_Counter_CounterException(sprintf ('Count with key "%s" already exists', $key));

        $params = array_merge($this->params, $params);
        $count = new Ds_Counter_Count_BaseCount($key, $params);
        $this->data_loader->registerClient($count);
        $this->counts[$key] = $count;
        return $this;
    }

    /**
     * Returns count for given key
     * @param string $key
     * @return integer
     */
    public function get($key)
    {
        if( isset($this->counts[$key]) )
            return $this->counts[$key]->get();
    }
}

