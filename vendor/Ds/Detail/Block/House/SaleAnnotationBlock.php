<?php

/**
 * Description of SaleAnnotation
 *
 * @author pahhan
 */
class Ds_Detail_Block_House_SaleAnnotationBlock extends Ds_Detail_Block_House_SupplyAnnotationBlock
{
    protected function getCost()
    {
        $data = $this->getData();
        $out = '';

        if( $price = $data->price_full )
            $out = number_format ($price, 0, '', ' ');

        if( $price and $curr = $data->price_currency )
            $out.= ' '.$curr;

        return $out;
    }

    public function render(array $params = array())
    {
        return 'Продажа '.parent::render($params);
    }
}

