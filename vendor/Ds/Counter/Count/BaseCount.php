<?php

/**
 * Description of BaseCount
 *
 * @author pahhan
 */
class Ds_Counter_Count_BaseCount implements Ds_Counter_Count_CountInterface, Ds_DataLoader_DataLoaderClientInterface
{
    protected $key;
    protected $params;
    protected $value;

    public function getLoaderInfo()
    {
        $info =  array(
            'key' => 'count.'.$this->key,
            'data' => array(
                'count' => array(
                    'params' => $this->params
                ),
            )
        );

        return $info;
    }

    public function onDataReceived(array $data)
    {
        if( isset($data['count']) and isset($data['count']['count']) ) $this->value = (int) $data['count']['count'];
    }

    public function __construct($key, array $params)
    {
        $this->key = $key;
        $this->params = $params;
    }

    public function get()
    {
        return $this->value;
    }
}

