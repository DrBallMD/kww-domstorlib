<?php

/**
 * Description of TableColumn
 *
 * @author pahhan
 */
class Ds_List_Column_TableColumn extends Ds_List_Column_BaseColumn
{
    protected $template = '@list/base_column.html.twig';

    protected $title;
    protected $url;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function init(array $params = array())
    {
        parent::init($params);
        $this->applyParams(array('title'), $params);
    }
}