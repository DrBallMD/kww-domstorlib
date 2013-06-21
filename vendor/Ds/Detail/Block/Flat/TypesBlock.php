<?php

/**
 * Description of SaleTypesBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_Flat_TypesBlock extends Ds_Detail_Block_AbstractBlock
{
    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

    // Тип квартиры
	public function type()
	{
		return $this->getData()->get('flat_type');
	}

	// Планировка
	public function planning()
	{
		return $this->getData()->get('planning');
	}

	// Материал строения
	public function buildingMaterial()
	{
		return $this->getData()->get('building_material');
	}
}