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
        return 'Аренда '.parent::render($params);
    }
}

