<?php

/**
 * Description of BaseColumn
 *
 * @author pahhan
 */
class Ds_List_Column_BaseColumn extends Spv_Widget_HtmlWidget implements Ds_List_Column_ColumnInterface
{
    protected $list;
    protected $row_data;
    protected $data_key;
    protected $templating;
    protected $template = '@list/base_column.html.twig';
    protected $template_vars = array();
    protected $sort_url;

    public function __construct(Spv_Templating_TemplatingInterface $templating)
    {
        $this->templating = $templating;
    }

    public function setRowData($data)
    {
        $this->row_data = $data;
    }

    public function getDataKey()
    {
        return $this->data_key;
    }

    public function getSortUrl()
    {
        return $this->sort_url;
    }

        public function getList()
    {
        return $this->list;
    }

    public function render(array $vars = array())
    {
        $vars = array_merge($this->template_vars, $vars);

        if( !$this->template )
            throw new RuntimeException( sprintf('Template not defined in "%s" column', $this->name) );

        if( isset($vars['attrs']) )
        {
            if( is_array($vars['attrs']) )
            {
                $this->setAttrs($vars['attrs']);
            }
            unset($vars['attrs']);
        }

        $vars['column'] = $this;
        $vars['row_data'] = $this->row_data;

        return $this->templating->render($this->template, $vars);
    }

    public function init(array $params = array())
    {
        $this->applyParams(array('template', 'template_vars', 'data_key', 'attrs', 'classes', 'sort_url'), $params);
    }

    protected function applyParams(array $names, array $params)
    {
        foreach ($names as $name)
        {
            if( isset($params[$name]) )
                $this->$name = $params[$name];
        }
    }
}

