<?php

/**
 * Description of DataLoaderClient
 *
 * @author pahhan
 */
class Ds_Detail_DataLoaderClient implements Ds_DataLoader_DataLoaderClientInterface
{
    /**
     *
     * @var Ds_Detail_DetailInterface
     */
    private $detail;

    /**
     *
     * @var array
     */
    protected $params = array();


    public function getLoaderInfo()
    {
        $info =  array(
            'key' => 'detail.data_loader_client',
            'data' => array(
                'detail' => array(
                    'params' => $this->params
                ),
            )
        );

        return $info;
    }

    public function onDataReceived(array $data)
    {
        if( isset($data['detail']) and is_array($data['detail']) )
            $this->detail->setData(current($data['detail']));
        else
            $this->detail->setData(array());
    }

    public function __construct(array $params = array())
    {
        $this->params = $params;
    }

    public function setDetail(Ds_Detail_DetailInterface $detail) {
        $this->detail = $detail;
    }

    public function setParams(array $params) {
        $this->params = $params;
    }




}

