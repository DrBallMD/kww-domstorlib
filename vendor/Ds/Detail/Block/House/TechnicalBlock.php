<?php

/**
 * Description of SaleTechnical
 *
 * @author pahhan
 */
class Ds_Detail_Block_House_Technicalblock extends Ds_Detail_Block_AbstractBlock
{
    protected $communication;


    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

    public function communication()
	{
		if( $this->communication ) return $this->communication;
        $data = $this->getData();
        $out = '';
		if( $data->get('phone') ) $out.= 'Телефон, ';
		if( $data->get('gas') ) $out.= 'Газопровод, ';
		if( $data->get('cable_tv') ) $out.= 'Кабельное ТВ, ';
		if( $data->get('door_phone') ) $out.= 'Домофон, ';
		if( $data->get('satellite_tv') ) $out.= 'Спутниковое ТВ, ';
		if( $data->get('signalizing') ) $out.= 'Охранная сигнализация, ';
		if( $data->get('internet') ) $out.= 'Интернет, ';
		if( $data->get('fire_prevention') ) $out.= 'Противопожарная сигнализация, ';
		$out = substr($out, 0, -2);
        $this->communication = $out;
		return $out;
	}

    public function active()
    {
        return  $this->communication() ||
                $this->santechToiletCount() ||
                $this->electroActive() ||
                $this->heat()          ||
                $this->water()         ||
                $this->sewerage()      ||
                $this->materialActive()||
                $this->windowsActive() ||
                $this->finishActive()  ||
                $this->stateActive();
    }

    // Количество санузлов
	public function santechToiletCount()
	{
		return (int) $this->getData()->get('toilet_count');
	}

    public function electroVoltage()
    {
        return (int) $this->getData()->electro_voltage;
    }

    public function electroPower()
    {
        return (float) $this->getData()->electro_power;
    }

    public function electroResrve()
    {
        return (bool) $this->getData()->electro_reserve;
    }

    public function electroNot()
    {
        return (bool) $this->getData()->electro_not;
    }

    public function electroActive()
    {
        return  $this->electroVoltage() or
                $this->electroPower()  or
                $this->electroResrve() or
                $this->electroNot();
    }

    public function heat()
    {
        return $this->getData()->heat;
    }

    public function water()
    {
        return $this->getData()->water;
    }

    public function sewerage()
    {
        return $this->getData()->sewerage;
    }

    public function materialActive()
    {
        return  $this->materialCarrying() ||
                $this->materialCeiling() ||
                $this->materialWall() ||
                $this->roofMaterial() ||
                $this->roofType() ||
                $this->foundation();
    }

    // Материал несущих конструкций
	public function materialCarrying()
	{
		return $this->getData()->get('material_carrying');
	}

	// Материал наружних стен
	public function materialWall()
	{
		return $this->getData()->get('material_wall');
	}

	// Материал перекрытий
	public function materialCeiling()
	{
		return $this->getData()->get('material_ceiling');
	}

    public function roofMaterial()
    {
        return $this->getData()->get('roof_material');
    }

    public function roofType()
    {
        return $this->getData()->get('roof_type');
    }

    public function foundation()
    {
        return $this->getData()->get('foundation');
    }

    public function windowsActive()
    {
        return  $this->windowsMaterial() ||
                $this->windowsGlasing() ||
                $this->windowsOpening();
    }

    // Материал рам
	public function windowsMaterial()
	{
		return $this->getData()->get('window_material');
	}

	// Тип остекления
	public function windowsGlasing()
	{
		return $this->getData()->get('window_glasing');
	}

	// Тип открывания окон
	public function windowsOpening()
	{
		return $this->getData()->get('window_opening');
	}

    public function finishActive()
    {
        return  $this->finishCeiling() ||
                $this->finishFloor() ||
                $this->finishPartition()||
                $this->facade();
    }

    // Потолки
	public function finishCeiling()
	{
		return $this->getData()->get('finish_ceiling');
	}

	// Полы
	public function finishFloor()
	{
		return $this->getData()->get('finish_paul');
	}

	// Перегородки
	public function finishPartition()
	{
		return $this->getData()->get('finish_partition');
	}

    public function facade()
	{
		return $this->getData()->get('facade');
	}

    public function stateActive()
    {
        return  $this->stateBuildYear() ||
                $this->stateWearout() ||
                $this->stateState();
    }

    // Год постройки
	public function stateBuildYear()
	{
		return $this->getData()->get('build_year');
	}

	// Процент износа
	public function stateWearout()
	{
		if( $v = $this->getData()->get('wearout') ) return $v.'%';
	}

	// Состояние
	public function stateState()
	{
		return $this->getData()->get('state');
	}


}

