<?php

/**
 * Configured to receive data from domstor server
 *
 * Client's loader info structure:
 *  array(
 *      'key' => 'some.key',
 *      'data' => array(
 *          '%name%' => array(
 *              'params' => array(...),
 *          )
 *      )
 *  )
 *
 * Key %name% must be defined in _getUrl() method
 *
 * @author pahhan
 */
class Ds_DataLoader_BaseDataLoader extends Ds_DataLoader_AbstractDataLoader
{
    protected $key;
    protected $server = 'http//domstor.ru';
    protected $loaded = array();

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

    /**
     * Returns data for clients. Implements Ds_DataLoader_AbstractDataLoader::getData().
     * @return array
     */
    protected function getData()
    {
        $out = array();

        /* @var $client Ds_DataLoader_DataLoaderClientInterface */
        foreach ($this->clients as $key => $client)
        {
            $out[$key] = $this->getDataByClient($key, $client);
        }

        return $out;
    }

    /**
     * Returns data for client
     * @param string $key Client key
     * @param Ds_DataLoader_DataLoaderClientInterface $client
     * @return array
     * @throws Ds_DataLoader_DataLoaderException
     */
    protected function getDataByClient($key, Ds_DataLoader_DataLoaderClientInterface $client)
    {
        $info = $client->getLoaderInfo();

        if( !(isset($info['data']) and is_array($info['data'])) )
            throw new Ds_DataLoader_DataLoaderException(sprintf('Client with key "%s" do not contains "data" or it is not an array', $key));

        $client_data = array();
        foreach( $info['data'] as $name => $data )
        {
            $temp_data = $this->readOneData($name, $data, $key);

            if( isset($temp_data['error']) )
            {
                $message = isset($temp_data['error']['message'])?
                    $temp_data['error']['message'] : '';

                throw new Ds_DataLoader_DataLoaderException(
                        sprintf('Data for "%s:%s" contain error: "%s"',
                                $key, $name, $message)
                    );
            }

            if( $temp_data )
                $client_data[$name] = $temp_data;
        }

        return $client_data;
    }

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
        if( !(isset($info_data['params']) and is_array($info_data['params'])) )
            throw new Ds_DataLoader_DataLoaderException(sprintf('Undefined params for data with name "%s" for client "%s"', $name, $client_key));

        $url_content = $this->readUrl($name, $info_data['params']);
        return $this->driver->read($url_content);
    }

    /**
     * Returns content from given url
     * @param string $url
     * @return string
     */
    protected function readUrl($name, array $params)
    {
        if( $this->key ) $params['key'] = $this->key;
        return $this->reader->read($this->_getUrl($name), $params);
    }

    /**
     * Returns api url for given name defined in getLoaderInfo
     * @param type $name
     * @return type
     */
    protected function _getUrl($name)
    {
        if( !$this->server )
            throw new Ds_DataLoader_DataLoaderException('Server not defined');

        switch ($name) {
            case 'types':
                return $this->server.'/api/type';
            case 'states':
                return $this->server.'/api/state';
            case 'districts':
                return $this->server.'/api/location/districts';
            case 'building_materials':
                return $this->server.'/api/reference/material';
            case 'cities':
                return $this->server.'/api/location/districts';
            case 'list':
                return $this->server.'/api/get';
            case 'detail':
                return $this->server.'/api/get';
            case 'count':
                return $this->server.'/api/count';

            default:
                throw new Ds_DataLoader_DataLoaderException(sprintf('Url for "%s" not defined', $name));
        }
    }
}

