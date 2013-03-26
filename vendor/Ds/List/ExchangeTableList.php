<?php

/**
 * Description of ExchangeTableList
 *
 * @author pahhan
 */
class Ds_List_ExchangeTableList extends Ds_List_TableList
{
    protected $exchange_row_template = '@list/table/exchange_row.html.twig';

    public function renderRow($row_data, array $vars = array())
    {
        if( $this->transformer_chain )
            $row_data = $this->transformer_chain->transform($row_data);

        foreach ($this->getColumns() as $column)
        {
            $column->setRowData($row_data);
        }
        $vars['list'] = $this;

        $exchange = false;
        $vars['flats'] = array();
        $vars['houses'] = array();
        
        if( isset($row_data['flat_demands']) )
        {
            $vars['flats'] = $row_data['flat_demands'];
            $exchange = TRUE;
            unset($row_data['flat_demands']);
        }

        if( isset($row_data['house_demands']) )
        {
            $vars['houses'] = $row_data['house_demands'];
            $exchange = TRUE;
            unset($row_data['house_demands']);
        }

        if( $exchange )
            return $this->templating->render($this->exchange_row_template, $vars);

        return $this->templating->render($this->row_template, $vars);
    }
}

