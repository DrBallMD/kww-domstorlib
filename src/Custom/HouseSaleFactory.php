<?php

/**
 * Description of HouseSaleFactory
 *
 * @author pahhan
 */
class Custom_HouseSaleFactory extends Ds_Factory_AbstractFactory
{
    protected $location = 2004;
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
     * @return Ds_UrlGenerator_UrlGeneratorInterface
     */
    public function getUrlGenerator()
    {
        return $this->getContainer()->get('url_generator');
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
     *
     * @return Ds_Definer_SortDefiner
     */
    public function getSortDefiner()
    {
        return $this->getContainer()->get('definer.sort');
    }

    /**
     *
     * @return Ds_Form_BaseForm
     */
    public function createForm()
    {
        $builder = $this->getContainer()->get('form.builder.house');
        $builder->init(array(
            'ref_city' => $this->location,
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
        $list_builder = $this->getContainer()->get('list.builder.house');
        $list_builder->init(array(
            'action' => $this->action,
            'in_region' => $this->in_region,
            'detail_sale_url' => '/house_detail.php?id=:id',
        ));
        $list = $list_builder->build();
        $data_loader = $this->getDataLoader();

        $params = array_merge($params, array(
            'entity' => 'house',
            'master_city' => $this->location,
            'sale' => true,
        ));

        $list_client = new Ds_List_DataLoaderClient($params);
        $list_client->setList($list);
        $data_loader->registerClient($list_client);

        return $list;
    }

    public function getDefaultSort()
    {
        return array('district' => 'a', 'city' => 'a', 'address' => 'a');
    }

    /**
     *
     * @param array $params
     * @return Ds_Detail_DetailInterface
     */
    public function createDetail(array $params)
    {
        $params = array_merge($params, array(
            'entity' => 'house',
            'master_city' => $this->location,
            'sale' => true,
        ));

        /* @var $detail Ds_Detail_AbstractDetail */
        $detail = $this->getContainer()->get('detail.house.sale');
        $chain = new Spv_Transformer_TransformerChain();
        $chain->addTransformer('owner', new Ds_Transformer_OwnerTransformer());
        $detail->setTransformerChain($chain);
        $client = new Ds_Detail_DataLoaderClient($params);
        $client->setDetail($detail);

        $data_loader = $this->getDataLoader();
        $data_loader->registerClient($client);

        return $detail;
    }
}

