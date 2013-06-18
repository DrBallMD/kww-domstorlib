<?php

/**
 * Description of SaleSizesBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_House_SaleSizesBlock extends Ds_Detail_Block_AbstractBlock
{
    public function render(array $params = array())
    {
        $vars = array('block' => $this);
        if( $this->fullHeight()
                or  $this->height()
                or $this->width()
                or $this->long()
                or $this->groundWidth()
                or $this->groundLong()
                or $this->squareHouse()
                or $this->squareGround()
                or $this->squareKitchen()
                or $this->squareLiving()
                or $this->squareUtility()
                )
        {
            return $this->getTemplating()->render($this->getTemplate(), $vars);
        }
        return '';

    }

    // Высота потолков
	public function fullHeight()
	{
		if( $val = $this->getData()->get('size_house_z_full') )
			return $val.' м';
	}

    // Высота потолков
	public function height()
	{
		if( $val = $this->getData()->get('size_house_z') )
			return $val.' м';
	}

	public function width()
	{
		return (float) $this->getData()->get('size_house_x');
	}

    public function long()
	{
		return (float) $this->getData()->get('size_house_y');
	}

    public function groundWidth()
	{
		return (float) $this->getData()->get('size_ground_x');
	}

    public function groundLong()
	{
		return (float) $this->getData()->get('size_ground_y');
	}

	// Общая площадь
	public function squareHouse()
	{
		return (float) $this->getData()->get('square_house');
	}
    // Общая площадь
	public function squareGround()
	{
		if( $val = $this->getData()->get('square_ground') )
			return $val.' '.$this->getData()->get('square_ground_unit');
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
	public function squareUtility()
	{
		return (float) $this->getData()->get('square_utility');
	}
}

