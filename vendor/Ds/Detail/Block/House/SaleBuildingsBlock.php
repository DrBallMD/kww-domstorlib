<?php

/**
 * Description of SaleBuildingsBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_House_SaleBuildingsBlock extends Ds_Detail_Block_AbstractBlock
{
    public function render(array $params = array())
    {
        $data = $this->getData();
        if( $data->bath_house
            or  $data->swimming_pool
            or $data->garage
            or $data->car_park_count
            or $data->other_building )
        {
             $vars = array('block' => $this);
            return $this->getTemplating()->render($this->getTemplate(), $vars);
        }
        return '';

    }
}

