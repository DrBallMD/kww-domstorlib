<?php

/**
 * Description of AdvancedDataLoader
 *
 * @author pahhan
 */
class Ds_DataLoader_AggregateDataLoader extends Ds_DataLoader_AbstractDataLoader
{
    protected $key;
    protected $server = 'http//domstor.ru';

    protected $hash_keys;

    /**
     * Sets api key to use domstor api
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Sets server's url
     * @param string $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    protected function getData()
    {
        $out = array();

        $request = $this->createRequestArray($this->clients);
        $source = $this->reader->read($this->server.'/api/aggregator', $request);
        $result = $this->driver->read($source);

        unset($request, $source);

        foreach($result as $client_data)
        {
            if( !isset($client_data['key']) )
                throw new Ds_DataLoader_DataLoaderException('Client data key not exists');

            $hash_key = $client_data['key'];

            if( !isset($this->hash_keys[$hash_key]) )
                throw new Ds_DataLoader_DataLoaderException('Unknown hash key');

            if( !isset($client_data['result']) )
                throw new Ds_DataLoader_DataLoaderException('Client data result not exists');

            $result = $client_data['result'];

            if( !is_array($result) )
                throw new Exception('Client data result is not array');

            $out[ $this->hash_keys[$hash_key] ] = $result;
        }

        return $out;
    }

    protected function createRequestArray(array $clients)
    {
        $request = array();

        /* @var $client Ds_DataLoader_DataLoaderClientInterface */
        foreach ($clients as $key => $client)
        {
            $hash = md5($key);
            $this->hash_keys[$hash] = $key;
            $request[$hash] = $this->getClientInfo($key, $client);
        }

        $request['key'] = $this->key;

        return $request;
    }

    protected function getClientInfo($key, Ds_DataLoader_DataLoaderClientInterface $client)
    {
        $info = $client->getLoaderInfo();

            if( !(isset($info['data']) and is_array($info['data'])) )
                throw new Ds_DataLoader_DataLoaderException(sprintf('Client with key "%s" do not contains "data" or it is not an array', $key));

        return $info['data'];
    }


}

