<?php

/**
 * Description of SaleAnnotation
 *
 * @author pahhan
 */
class Ds_Detail_Block_Flat_RentAnnotationBlock extends Ds_Detail_Block_Flat_SupplyAnnotationBlock
{

    public function render(array $params = array())
    {
        return 'Аренда ' . parent::render($params);
    }

    public function getCost()
    {
        $data = $this->getData();
        $out = '';

        if ($data->rent_full)
            $out = number_format($data->rent_full, 0, '', ' ');

        if ($out and $data->rent_currency)
            $out.= ' ' . $data->rent_currency;

        if ($out and $data->rent_period)
            $out.= ' ' . $data->rent_period;

        return $out;
    }

}
