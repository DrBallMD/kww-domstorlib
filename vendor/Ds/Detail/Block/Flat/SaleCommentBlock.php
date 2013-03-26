<?php

/**
 * Description of SaleCommentBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_Flat_SaleCommentBlock extends Ds_Detail_Block_AbstractBlock
{
    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

    public function comment()
    {
        return $this->getData()->get('note_web');
    }
}

