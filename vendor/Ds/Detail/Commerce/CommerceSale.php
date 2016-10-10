<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CommerceSale
 *
 * @author Dmitry Anikeev <da@kww.su>
 */
class Ds_Detail_Commerce_CommerceSale extends Ds_Detail_AbstractDetail
{
    protected $template = '@detail/commerce_sale.html.twig';

    public function getHeadTitle()
    {
        return $this->renderTemplate('@detail/blocks/commerce/sale/head_title.twig');
    }

    public function getPageTitle()
    {
        return $this->renderTemplate('@detail/blocks/commerce/sale/page_title.twig');
    }
}
