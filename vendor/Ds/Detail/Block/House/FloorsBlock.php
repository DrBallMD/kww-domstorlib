<?php

/**
 * Description of SaleFloorsBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_House_FloorsBlock extends Ds_Detail_Block_AbstractBlock
{
    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

    // Этаж
	public function floor()
	{
		if( $this->getData()->isSetAnd('building_floor') )
            return $this->getData()->get('building_floor');
	}

    public function options()
    {
        $data = $this->getData();
        $out = '';
        if( $data->isSetAnd('mansard') )
            $out.= 'мансарда, ';
        if( $data->isSetAnd('ground_floor') )
            $out.= 'цокольный этаж, ';
        if( $data->isSetAnd('cellar') )
            $out.= 'подвал, ';

        return substr($out, 0, -2);
    }
}