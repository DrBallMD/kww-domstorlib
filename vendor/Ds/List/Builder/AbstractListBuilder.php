<?php

/**
 * Description of BaseListBuilder
 *
 * @author pahhan
 */
abstract class Ds_List_Builder_AbstractListBuilder implements Ds_List_Builder_ListBuilderInterface, Ds_IoC_ContainerAwareInterface
{
    protected $action;
    protected $in_region;
    protected $detail_url;

    abstract protected function buildSale();
    abstract protected function buildRent();
    abstract protected function buildPurchase();
    abstract protected function buildRentuse();

    /**
     * Required params: ref_city, in_region, action
     * @param array $params
     */
    public function init(array $params)
    {
        $this->in_region = $params['in_region'];
        $this->action = $params['action'];
        if( isset($params['detail_url']) ) $this->detail_url = $params['detail_url'];
    }

    public function setDetailUrl($detail_url)
    {
        $this->detail_url = $detail_url;
    }


    /**
     * @return Ds_IoC_Container
     */
    public function getContainer()
    {
        return Ds_IoC_Container::instance();
    }

    /**
     * @param string $id
     * @param array $params
     * @return Ds_List_Column_ColumnInterface
     */
    public function createColumn($id, array $params = array())
    {
        $column = $this->getContainer()->get($id);

        if( isset($params['sort']) )
        {
            $sort = $params['sort'];
            $current_sort = $this->getContainer()->get('definer.sort')->define();
            $val = 'a';
            if( isset($current_sort[$sort]) )
            {
                $val = $current_sort[$sort]=='d'? 'a':'d';
            }

            $params['sort_url'] = $this->getContainer()->get('url_generator')->generateSort(array($sort => $val));
        }

        $column->init($params);

        return $column;
    }

    public function build()
    {
        if( $this->action == 'sale' )
            return $this->buildSale();
        if( $this->action == 'rent' )
            return $this->buildRent();
        if( $this->action == 'purchase' )
            return $this->buildPurchase();
        if( $this->action == 'renuse' )
            return $this->buildRentuse();

        throw  new Ds_List_Builder_ListBuilderException(sprintf('Unknown action "%s"', $this->action));
    }

    protected function addDistrictOrCityColumn(Ds_List_ListInterface $list)
    {
        if( $this->in_region ) {

        }
        else {
            $list->addColumn('district ', $this->createColumn('list.column', array(
                'template' => '@list/columns/district.html.twig',
                'title' => 'Район',
                'classes' => array('domstor_district'),
                'sort' => 'district',
            )));
        }
    }

    protected function addAddressColumn(Ds_List_ListInterface $list)
    {
        if( $this->in_region ) {

        }
        else {
            $list->addColumn('address ', $this->createColumn('list.column', array(
                'template' => '@list/columns/city_address.html.twig',
                'title' => 'Адрес',
                'classes' => array('domstor_address'),
                'sort' => 'address',
            )));
        }

    }


}

