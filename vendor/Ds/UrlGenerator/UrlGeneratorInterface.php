<?php

/**
 *
 * @author pahhan
 */
interface Ds_UrlGenerator_UrlGeneratorInterface
{
    public function setUrlPattern($pattern);
    public function setFormValue($v);
    public function setPageValue($v);
    public function setOnPageValue($v);
    public function setSortValue($v);
    public function generateSort($sort_values);
    public function generateOnPage($onpage_value);
    public function generatePage($page_value);
    public function generateForm($form_values);
}
