<?php

/**
 * Description of TableList
 *
 * @author pahhan
 */
class Ds_List_TableList extends Ds_List_BaseList
{
    protected $template = '@list/table/table_list.html.twig';
    protected $row_template = '@list/table/table_row.html.twig';

    /**
     * @var Spv_Transformer_TransformerChainInterface
     */
    protected $transformer_chain;

    public function setTransformerChain(Spv_Transformer_TransformerChainInterface $chain)
    {
        $this->transformer_chain = $chain;
    }

    public function renderRow($row_data, array $vars = array())
    {
        if( $this->transformer_chain )
            $row_data = $this->transformer_chain->transform($row_data);

        foreach ($this->getColumns() as $column)
        {
            $column->setRowData($row_data);
        }
        $vars['list'] = $this;
        return $this->templating->render($this->row_template, $vars);
    }
}

