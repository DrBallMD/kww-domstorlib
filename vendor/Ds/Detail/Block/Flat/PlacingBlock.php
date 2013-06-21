<?php

/**
 * Description of SalePlacingBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_Flat_PlacingBlock extends Ds_Detail_Block_AbstractBlock
{
    protected $windows;
    protected $parking;

    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

    public function active()
    {
        return  $this->windowsDirection() ||
                $this->inCorner() ||
                $this->furniture() ||
                $this->householdTech() ||
                $this->garbageChute() ||
                $this->liftCount() ||
                $this->liftCargo() ||
                $this->security() ||
                $this->parking();
    }

    // Направление окон
	public function windowsDirection()
	{
        if( $this->windows ) return $this->windows;
		$out = '';
        $data = $this->getData();
		if( $data->get('window_to_south') ) $out.= 'Юг, ';
		if( $data->get('window_to_north') ) $out.= 'Север, ';
		if( $data->get('window_to_east') ) $out.= 'Восток, ';
		if( $data->get('window_to_west') ) $out.= 'Запад, ';
		$out = substr($out, 0, -2);
        $this->windows = $out;
		return $out;
	}

	// Угловая
	public function inCorner()
	{
		return $this->getData()->get('in_corner');
	}

	// Мебель
	public function furniture()
	{
		return $this->getData()->get('furniture');
	}

    // Бытовая техника
	public function householdTech()
	{
		return $this->getData()->get('household_technique');
	}

	// Мусоропровод
	public function garbageChute()
	{
		return $this->getData()->get('garbage_chute');
	}

	// Количество лифтов
	public function liftCount()
	{
		return (int) $this->getData()->get('lift_count');
	}

	// Грузовой лифт
	public function liftCargo()
	{
		return $this->getData()->get('lift_cargo');
	}

	// Охрана
	public function security()
	{
		return $this->getData()->get('security');
	}

	// Парковка
	public function parking()
	{
		if( $this->parking ) return $this->parking;
        $data = $this->getData();
        $out = $space = '';
		if( $data->isSetAnd('parking') )
		{
			$out.= $data->get('parking');
			$space = ', ';
		}
		if( $data->get('sale_with_parking') ) $out.= $space.'Возможна продажа совместно с гаражом или паркоместом';

        $this->parking = $out;
		return $out;
	}
}

