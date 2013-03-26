<?php

/**
 * Receives data for list object
 *
 * @author pahhan
 */
class Ds_List_DataLoaderClient implements Ds_DataLoader_DataLoaderClientInterface
{
    protected $params = array();

    /**
     * @var Ds_List_ListInterface
     */
    protected $list;

    public function getLoaderInfo()
    {
        $info =  array(
            'key' => 'list.data_loader_client',
            'data' => array(
                'list' => array(
                    'params' => $this->params
                ),
            )
        );

        return $info;
    }

    public function onDataReceived(array $data)
    {
        if( isset($data['list']) )
            $this->list->setData($data['list']);
    }

    public function __construct(array $params = array())
    {
        $this->params = $params;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function setList(Ds_List_ListInterface $list)
    {
        $this->list = $list;
    }
}

