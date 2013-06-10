<?php

class Custom_FlatSaleFactory extends Ds_Factory_AbstractFactory
{
    protected $ref_city = 2004;
    protected $in_region = false;
    protected $action = 'sale';

    /**
     * @return Ds_DataLoader_DataLoaderInterface
     */
    public function getDataLoader()
    {
        return $this->getContainer()->get('data_loader');
    }

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
            'master_city' => $this->ref_city,
            'sale' => true,
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
            'master_city' => $this->ref_city,
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
     * Ds_Counter_CounterInterface
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

    /**
     *
     * @return Ds_Pagination_Pagination
     */
    public function createPagination()
    {
        return $this->getContainer()->get('pagination');
    }

    /**
     *
     * @return Ds_Definer_SortDefiner
     */
    public function getSortDefiner()
    {
        return $this->getContainer()->get('definer.sort');
    }

    /**
     *
     * @return Ds_Definer_PageDefiner
     */
    public function getPageDefiner()
    {
        return $this->getContainer()->get('definer.page');
    }

    /**
     *
     * @return Ds_Definer_OnPageDefiner
     */
    public function getOnPageDefiner()
    {
        return $this->getContainer()->get('definer.onpage');
    }

    /**
     * @return Ds_UrlGenerator_UrlGeneratorInterface
     */
    public function getUrlGenerator()
    {
        return $this->getContainer()->get('url_generator');
    }
}