<?php

/**
 * Description of SaleRoomsBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_Flat_SaleRoomsBlock extends Ds_Detail_Block_AbstractBlock
{
    // Returns room count text
	public function rooms()
	{
		return $this->getData()->room_count;
	}

	// Returns in communal
	public function inCommunal()
	{
		return $this->getData()->get('in_communal');
	}

	public function hasPocket()
	{
		return $this->getData()->get('in_pocket');
	}

    public function together()
	{
		return $this->getData()->get('Together');
    }

	public function render(array $params = array())
	{
		$vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
	}
}

