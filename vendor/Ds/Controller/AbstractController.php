<?php

/**
 * Description of BaseController
 *
 * @author pahhan
 */
class Ds_Controller_BaseController implements Ds_IoC_ContainerAwareInterface
{
    protected $params = array();
    protected $container;

    protected $form;

    public function __construct(array $params = array())
    {
        $this->params = $params;
    }

    /**
     * @return Ds_IoC_Container
     */
    public function getContainer()
    {
        return Ds_IoC_Container::instance();
    }

    public function init()
    {
        $this->createForm();
        $data_loader = $this->getContainer()->get('data_loader');
        $data_loader->load();
    }

    protected function createForm(array $params = array())
    {
        $params = array_merge($this->params, $params);

        $data_loader = $this->getContainer()->get('data_loader');

        $builder = $this->getContainer()->get('form.builder.garage');
        $builder->init($params);

        $data_loader->registerClient($builder);

        $this->form = $builder->build();

    }

    public function testList()
    {
        $data_loader = $this->getContainer()->get('data_loader');
        $list_client = new Ds_List_DataLoaderClient(array(
            'entity' => 'flat',
            'ref_city' => '2004',
            'sale' => true,
            'limit' => 20,
        ));

        $list_builder = $this->getContainer()->get('list.builder.flat');
        $list_builder->init(array('action' => 'sale', 'in_region' => FALSE));
        $list = $list_builder->build();
        $list_client->setList($list);
        $data_loader->registerClient($list_client);

        $data_loader->load();

        echo $list->render();
    }

    public function getForm()
    {
        return $this->form;
    }
}

