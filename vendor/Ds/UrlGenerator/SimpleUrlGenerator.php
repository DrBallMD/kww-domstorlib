<?php

/**
 * Description of SimpleUrlGenerator
 *
 * @author pahhan
 */
class Ds_UrlGenerator_SimpleUrlGenerator extends Ds_UrlGenerator_AbstractUrlGenerator
{
    public function generateForm($form_values)
    {

    }

    public function generateOnPage($onpage)
    {
        $url = $this->url_pattern;

        if( count($this->form_value) or count($this->sort_value) )
        {
            $url.= '?';

            if( count($this->form_value) )
                $url.= http_build_query($this->form_value).'&';

            if( count($this->sort_value) )
                $url.= http_build_query($this->sort_value).'&';

            if( $this->page_value ) $url.= 'page='.$this->page_value.'&';
            $url.= 'onpage='.$onpage.'&';

            $url = substr($url, 0, -1);
        }

        return $url;
    }

    public function generatePage($page)
    {
        $url = $this->url_pattern;

        if( count($this->form_value) or count($this->sort_value) )
        {
            $url.= '?';

            if( count($this->form_value) )
                $url.= http_build_query($this->form_value).'&';

            if( count($this->sort_value) )
                $url.= http_build_query($this->sort_value).'&';

            $url.= 'page='.$page.'&';
            if( $this->onpage_value ) $url.= 'onpage='.$this->onpage_value.'&';

            $url = substr($url, 0, -1);
        }

        return $url;
    }


    public function generateSort($sort_values)
    {
        $url = $this->url_pattern.'?';

        if( count($this->form_value) )
            $url.= http_build_query($this->form_value).'&';

        $url.= http_build_query(array('s' => $sort_values)).'&';

        if( $this->page_value ) $url.= 'page='.$this->page_value.'&';
        if( $this->onpage_value ) $url.= 'onpage='.$this->onpage_value.'&';

        $url = substr($url, 0, -1);

        return $url;
    }


}

