<?php

/**
 * Description of AbstractColumn
 *
 * @author pahhan
 */
class Ds_List_BaseList extends Spv_Widget_HtmlWidget implements Ds_List_ListInterface
{
    private $columns = array();
    protected $data;

    /**
     *
     * @var Spv_Templating_TemplatingInterface
     */
    protected $templating;
    protected $template = '@list/base_list.html.twig';
    protected $row_template = '@list/base_row.html.twig';

    public function __construct(Spv_Templating_TemplatingInterface $templating)
    {
        $this->templating = $templating;
    }

    public function addColumn($name, Ds_List_Column_ColumnInterface $column)
    {
        $this->columns[$name] = $column;
        return $this;
    }

    public function getColumn($name)
    {
        if( !$this->hasColumn($name) )
            throw new RuntimeException(sprintf ('Undefined column with name "%s"', $name));

        return $this->columns[$name];
    }

    public function hasColumn($name)
    {
        return isset( $this->columns[$name] );
    }

    public function deleteColumn($name)
    {
        if( $this->hasColumn($name) )
            unset($this->columns[$name]);
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function count()
    {
        return count($this->columns);
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function render(array $vars = array())
    {
        if( !$this->template )
            throw new RuntimeException('Template not defined');

        if( isset($vars['attrs']) )
        {
            if( is_array($vars['attrs']) )
            {
                $this->setAttrs($vars['attrs']);
            }
            unset($vars['attrs']);
        }

        $vars['list'] = $this;

        return $this->templating->render($this->template, $vars);
    }

    public function renderRow($row_data, array $vars = array())
    {
        foreach ($this->getColumns() as $column)
        {
            $column->setRowData($row_data);
        }
        $vars['list'] = $this;
        return $this->templating->render($this->row_template, $vars);
    }

}

