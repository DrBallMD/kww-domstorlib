<?php

/**
 * Description of SaleSizesBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_Flat_SaleSizesBlock extends Ds_Detail_Block_AbstractBlock
{
    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

    // Высота потолков
	public function height()
	{
		if( $val = $this->getData()->get('height') )
			return $val.' м';
	}

	// Количество уровней
	public function floorCount()
	{
		$fc = (int) $this->getData()->get('floor_count');
        if( $fc > 1 ) return $fc;
	}

	// Общая площадь
	public function squareHouse()
	{
		return $this->getData()->get('square_house');
	}

	// Жилая площадь
	public function squareLiving()
	{
		return (float) $this->getData()->get('square_living');
	}

	// Площадь кухни
	public function squareKitchen()
	{
		return (float) $this->getData()->get('square_kitchen');
	}

	// Площадь кармана
	public function squarePocket()
	{
		return (float) $this->getData()->get('square_pocket');
	}
}

