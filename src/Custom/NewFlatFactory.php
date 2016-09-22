<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewFlatFactory
 *
 * @author Dmitry Anikeev <da@kww.su>
 */
class Custom_NewFlatFactory extends Custom_AbstractFactory
{
    protected $location = 2004;
    protected $in_region = false;
    
    /**
     *
     * @return Ds_Form_BaseForm
     */
    public function createForm()
    {
        $builder = $this->getContainer()->get('form.builder.newflat');
        $builder->init(array(
            'ref_city' => $this->location,
            'in_region' => $this->in_region,
            'action' => $this->action,
        ));

        $data_loader = $this->getDataLoader();
        $data_loader->registerClient($builder);

        $form =  $builder->build();
        $form->getForm('expo')->setValue(900);
        return $form;
    }

    /**
     *
     * @return Ds_List_ListInterface
     */
    public function createList($detail_url, array $params)
    {
        $list_builder = $this->getContainer()->get('list.builder.newflat');
        $list_builder->init(array(
            'action' => $this->action,
            'in_region' => $this->in_region,
            'detail_url' => $detail_url,
        ));
        $list = $list_builder->build();
        $data_loader = $this->getDataLoader();

        $params = array_merge($params, array(
            'entity' => 'newflat',
            'master_city' => $this->location,
            $this->action => true,
        ));

        $list_client = new Ds_List_DataLoaderClient($params);
        $list_client->setList($list);
        $data_loader->registerClient($list_client);

        return $list;
    }

    /**
     *
     * @param array $params
     * @return Ds_Detail_DetailInterface
     */
    public function createDetail(array $params)
    {
        $params = array_merge($params, array(
            'entity' => 'newflat',
            'master_city' => $this->location,
            $this->action => true,
        ));

        /* @var $detail Ds_Detail_AbstractDetail */
        $detail = $this->getContainer()->get('detail.newflat.'.$this->action);
        $chain = new Spv_Transformer_TransformerChain();
        $chain->addTransformer('owner', new Ds_Transformer_OwnerTransformer());
        $detail->setTransformerChain($chain);
        $client = new Ds_Detail_DataLoaderClient($params);
        $client->setDetail($detail);
        $data_loader = $this->getDataLoader();
        $data_loader->registerClient($client);
        return $detail;
    }

    public function getAdditionalApiParams()
    {
        return array('room_no_empty' => 1);
    }

    public function getDefaultSort()
    {
        return array('rooms' => 'a', 'district' => 'a', 'city' => 'a','address' => 'a');
    }

    /**
     * Ds_Counter_CounterInterface
     */
    public function createCounter()
    {
        $counter = $this->getContainer()->get('counter');
        $counter->setParams(array(
            'entity' => 'newflat',
            'ref_city' => $this->location,
            $this->action => true,
        ));
        return $counter;
    }
}
