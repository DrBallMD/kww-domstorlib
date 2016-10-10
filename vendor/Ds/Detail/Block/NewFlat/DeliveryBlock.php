<?php
/**
 * Description of DeliveryBlock
 *
 * @author Dmitry Anikeev <da@kww.su>
 */
class Ds_Detail_Block_NewFlat_DeliveryBlock extends Ds_Detail_Block_AbstractBlock
{
    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

    public function deliveryQuarter()
    {
        return $this->getData()->get('delivery_quarter');
    }
    
    public function deliveryYear()
    {
        return $this->getData()->get('delivery_year');
    }
    
    public function delivered()
    {
        return $this->getData()->get('delivered');
    }
}