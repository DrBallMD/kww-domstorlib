<?php

/**
 * Description of AbstractUrlGenerator
 *
 * @author pahhan
 */
abstract class Ds_UrlGenerator_AbstractUrlGenerator implements Ds_UrlGenerator_UrlGeneratorInterface
{
    protected $form_value;
    protected $onpage_value;
    protected $page_value;
    protected $sort_value;
    protected $url_pattern;


    public function setFormValue($v) {
        $this->form_value = $v;
    }

    public function setOnPageValue($v) {
        $this->onpage_value = $v;
    }

    public function setPageValue($v) {
        $this->page_value = $v;
    }

    public function setSortValue($v) {
        $this->sort_value = $v;
    }

    public function setUrlPattern($pattern) {
        $this->url_pattern = $pattern;
    }


}

