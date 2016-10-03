<?php

/**
 * Description of HouseSale
 *
 * @author pahhan
 */
class Ds_Detail_House_HouseSale extends Ds_Detail_AbstractDetail
{
    protected $template = '@detail/house_sale.html.twig';

    public function getHeadTitle()
    {
        return $this->renderTemplate('@detail/blocks/house/sale/head_title.twig');
    }

    public function getPageTitle()
    {
        return $this->renderTemplate('@detail/blocks/house/sale/page_title.twig');
    }
}

