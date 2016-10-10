<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CommerceListBuilder
 *
 * @author Dmitry Anikeev <da@kww.su>
 */
class Ds_List_Builder_CommerceListBuilder extends Ds_List_Builder_AbstractListBuilder
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
//            ->addColumn('rooms ', $this->createColumn('list.column', array(
//                    'template' => '@list/table/table_column.html.twig',
//                    'data_key' => 'room_count',
//                    'title' => 'Число комнат',
//                    'classes' => array('domstor_room_count'),
//                    'sort' => 'rooms',
//            )))
        ;

        $this->addDistrictOrCityColumn($list);
        $this->addAddressColumn($list);

        $list
            ->addColumn('price', $this->createColumn('list.column', array(
                    'template' => '@list/columns/price.html.twig',
                    'title' => 'Цена',
                    'classes' => array('domstor_price'),
                    'sort' => 'price',
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
