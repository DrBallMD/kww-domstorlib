<?php

/**
 * Description of SaleContactsBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_ContactsBlock extends Ds_Detail_Block_AbstractBlock
{
    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

    public function phone()
    {
        $data = $this->getData();
        return $data->isSetAndArray('Contact')? $data->Contact->phone : '';
    }

    public function email()
    {
        $data = $this->getData();
        return $data->isSetAndArray('Contact')? $data->Contact->email : '';
    }

    public function name()
    {
        $data = $this->getData();
        return $data->isSetAndArray('Contact')? $data->Contact->name : '';
    }

    public function agency()
    {
        $data = $this->getData();
        return $data->isSetAndArray('Agency')? $data->Agency->short_name : '';
    }

    public function agent()
    {
        $data = $this->getData();

        if( $data->isSetAndArray('Agency') and $data->Agency->hide_agent ) return;

        return $data->isSetAndArray('Agent')? $data->Agent->name_as : '';
    }

    public function lastUpdate()
    {
        return $this->getData()->edit_dt;
    }

    public function views()
    {
        return $this->getData()->view_count;
    }
}

