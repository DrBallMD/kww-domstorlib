<?php

/**
 * Description of SaleFloorsBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_Flat_FloorsBlock extends Ds_Detail_Block_AbstractBlock
{

    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

    // Этаж
    public function floors()
    {
        if ($out = $this->getData()->get('object_floor'))
        {
            if ($b = $this->getData()->get('building_floor'))
            {
                $out.='/' . $b;
            }
        }
        return $out;
    }

    // Есть ли цокольный этаж
    public function hasGroundFloor()
    {
        return (bool) $this->getData()->get('ground_floor');
    }

    // Первые этажи не жилые
    public function firstFloorCommerce()
    {
        return (bool) $this->getData()->get('first_floor_commerce');
    }

}
