<?php
/**
 * Description of SaleNavigationBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_NavigationBlock extends Ds_Detail_Block_AbstractBlock
{
    protected $prev;
    protected $next;

    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

    public function prev()
    {
        if( !$this->prev )
        {
            if( !$id = $this->getData()->prev_id ) return;
            if( !$url = $this->getParam('url') ) return;
            $this->prev = str_replace(':id', $id, $url);
        }

        return $this->prev;
    }

    public function next()
    {
        if( !$this->next )
        {
            if( !$id = $this->getData()->next_id ) return;
            if( !$url = $this->getParam('url') ) return;
            $this->next = str_replace(':id', $id, $url);
        }

        return $this->next;
    }

    public function code()
    {
        return $this->getData()->code;
    }
}

