<?php

/**
 * Description of SaleFurnitureBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_House_SaleFurnitureBlock extends Ds_Detail_Block_AbstractBlock
{
    protected $furniture;

    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

    public function furniture()
    {
        if( !$this->furniture ) {
            $data = $this->getData();
            $out = '';
            if( $data->get('with_furniture') ) $out.= 'С мебелью; ';
            if( $data->get('garden') ) $out.= 'Посадки, огород на участке; ';
            if( $data->get('landscape_design') ) $out.= 'Ландшафтный дизайн; ';
            if( $data->get('improvement_territory') ) $out.= 'Прилегающая территория благоустроена; ';
            $this->furniture = trim($out, '; ');
        }
		return $this->furniture;
    }

    public function roadsActive()
    {
        return $this->getData()->road_covering OR $this->getData()->road_state;
    }
    //put your code here
}

