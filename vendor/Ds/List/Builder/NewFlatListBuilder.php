<?php
/**
 * Description of NewFlatListBuilder
 *
 * @author Dmitry Anikeev <da@kww.su>
 */
class Ds_List_Builder_NewFlatListBuilder extends Ds_List_Builder_AbstractListBuilder
{

    /**
     * @return Spv_Transformer_TransformerChainInterface
     */
    protected function buildChain()
    {
        $chain = new Spv_Transformer_TransformerChain();
        $chain->addTransformer('owner', new Ds_Transformer_OwnerTransformer());
        return $chain;
    }

    public function build()
    {
        return $this->buildSale();
    }

    protected function buildSale()
    {
        $list = $this->getContainer()->get('list.base');
        $list
            ->addColumn('thumb ', $this->createColumn('list.column', array(
                    'template' => '@list/columns/thumb.html.twig',
                    'template_vars' => array('url' => $this->detail_url),
                    'title' => 'Фото',
                    'classes' => array('domstor_thumb'),
            )))->addColumn('code', $this->createColumn('list.column', array(
                    'template' => '@list/columns/code.html.twig',
                    'template_vars' => array('url' => $this->detail_url),
                    'data_key' => 'code',
                    'title' => 'Код',
                    'classes' => array('domstor_code'),
                    'sort' => 'code',
            )))
            ->addColumn('rooms ', $this->createColumn('list.column', array(
                    'template' => '@list/table/table_column.html.twig',
                    'data_key' => 'room_count',
                    'title' => 'Число комнат',
                    'classes' => array('domstor_room_count'),
                    'sort' => 'rooms',
            )))
        ;

        $this->addDistrictOrCityColumn($list);
        $this->addAddressColumn($list);

        $list
            ->addColumn('floor', $this->createColumn('list.column', array(
                    'template' => '@list/columns/floor.html.twig',
                    'title' => 'Этаж',
                    'classes' => array('domstor_floor'),
            )))
            ->addColumn('square', $this->createColumn('list.column', array(
                    'template' => '@list/columns/square.html.twig',
                    'title' => 'Площадь<br/>общ./жил./кух.',
                    'classes' => array('domstor_square'),
                    'sort' => 'square',
            )))
            ->addColumn('price', $this->createColumn('list.column', array(
                    'template' => '@list/columns/price.html.twig',
                    'title' => 'Цена',
                    'classes' => array('domstor_price'),
                    'sort' => 'price',
            )))
            ->addColumn('delivery', $this->createColumn('list.column', array(
                    'template' => '@list/columns/delivery.html.twig',
                    'title' => 'Сдача',
                    'classes' => array('domstor_delivery')
            )))
            ->addColumn('agency', $this->createColumn('list.column', array(
                    'template' => '@list/columns/agency.html.twig',
                    'title' => 'Агентство',
                    'classes' => array('domstor_agency'),
            )))
            ->addColumn('contact', $this->createColumn('list.column', array(
                    'template' => '@list/columns/contact.html.twig',
                    'title' => 'Контактный телефон',
                    'classes' => array('domstor_contact'),
            )))

        ;

        $list->setTransformerChain($this->buildChain());
        return $list;
    }

    protected function buildPurchase()
    {
        
    }

    protected function buildRent()
    {
        
    }

    protected function buildRentuse()
    {
        
    }

    protected function buildExchange()
    {
        
    }
}
