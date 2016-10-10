<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewFlatSale
 *
 * @author Dmitry Anikeev <da@kww.su>
 */
class Ds_Detail_Flat_NewFlatSale extends Ds_Detail_AbstractDetail
{
    protected $template = '@detail/newflat_sale.html.twig';

    public function getHeadTitle()
    {
        return $this->renderTemplate('@detail/blocks/flat/sale/head_title.twig');
    }

    public function getPageTitle()
    {
        return $this->renderTemplate('@detail/blocks/flat/sale/page_title.twig');
    }
}
