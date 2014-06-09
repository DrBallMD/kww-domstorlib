<?php

/**
 * Description of SaleAnnotation
 *
 * @author pahhan
 */
abstract class Ds_Detail_Block_House_SupplyAnnotationBlock extends Ds_Detail_Block_AbstractBlock
{
    protected function getAddress()
    {
        $data = $this->getData();
        $out = '';
        if( !($data->isSetAndArray('Street') && $data->Street->name) ) return;

        $out.= ($data->Street->isSetAnd('abbr')? $data->Street->abbr.' ' : '').$data->Street->name;
        if( $data->isSetAnd('building_num') ) $out.= ', '.$data->building_num;
        if( $data->isSetAnd('corpus') ) $out.= (is_numeric($data->corpus)? '/' : '').$data->corpus;

        return $out;
    }

    protected function getDistrict()
    {
        $data = $this->getData();
    }

    protected function getPrice()
    {
        $data = $this->getData();
        $out = '';

        if( $price = $data->price_full )
            $out = number_format ($price, 0, '', ' ');

        if( $price and $curr = $data->price_currency )
            $out.= ' '.$curr;

        return $out;
    }

    abstract protected function getCost();

    public function render(array $params = array())
    {
        $data = $this->getData();
        $out = '';
        if( $data->isSetAnd('room_count') ) $out.= sprintf('%d комн.', $data->room_count);
        if( $data->isSetAnd('house_type') ) $out.= ', '.$data->house_type;
        if( $address = $this->getAddress() ) $out.= ', '.$address;
        if( $data->isSetAndArray('District') and $dist = $data->District->name ) $out.= ', '.$dist;
        if( $price = $this->getCost() ) $out.= ', '.$price;
        if( $data->isSetAnd('note_addition') ) $out.= ', '.$data->note_addition;
        return $out;
    }
}

