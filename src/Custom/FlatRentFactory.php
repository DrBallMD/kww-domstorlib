<?php

class Custom_FlatRentFactory extends Custom_AbstractFactory
{
    protected $ref_city = 2004;
    protected $in_region = false;
    protected $action = 'rent';

    /**
     *
     * @return Ds_Form_BaseForm
     */
    public function createForm()
    {
        $builder = $this->getContainer()->get('form.builder.flat');
        $builder->init(array(
            'ref_city' => $this->ref_city,
            'in_region' => $this->in_region,
            'action' => $this->action,
        ));

        $data_loader = $this->getDataLoader();
        $data_loader->registerClient($builder);

        $form =  $builder->build();
        $form->getForm('expo')->setValue(90);
        return $form;
    }

    /**
     *
     * @return Ds_List_ListInterface
     */
    public function createList(array $params)
    {
        $list_builder = $this->getContainer()->get('list.builder.flat');
        $list_builder->init(array(
            'action' => $this->action,
            'in_region' => $this->in_region,
            'detail_sale_url' => '/detail.php?id=:id',
        ));
        $list = $list_builder->build();
        $data_loader = $this->getDataLoader();

        $params = array_merge($params, array(
            'entity' => 'flat',
            'ref_city' => $this->ref_city,
            'rent' => true,
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
            'entity' => 'flat',
            'ref_city' => $this->ref_city,
            'sale' => true,
        ));

        /* @var $detail Ds_Detail_AbstractDetail */
        $detail = $this->getContainer()->get('detail.flat.sale');
        $chain = new Spv_Transformer_TransformerChain();
        $chain->addTransformer('owner', new Ds_Transformer_OwnerTransformer());
        $detail->setTransformerChain($chain);
        $client = new Ds_Detail_DataLoaderClient($params);
        $client->setDetail($detail);

        $data_loader = $this->getDataLoader();
        $data_loader->registerClient($client);

        return $detail;
    }

    public function getDefaultSort()
    {
        return array('rooms' => 'a', 'district' => 'a', 'city' => 'a','address' => 'a');
    }

    /**
     * @return Ds_Counter_CounterInterface
     */
    public function createCounter()
    {
        $counter = $this->getContainer()->get('counter');
        $counter->setParams(array(
            'entity' => 'flat',
            'ref_city' => $this->ref_city,
        ));

        return $counter;
    }


}