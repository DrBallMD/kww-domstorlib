<?php

/**
 * Description of FlatSale
 *
 * @author pahhan
 */
class Ds_Detail_Flat_FlatSale extends Ds_Detail_AbstractDetail
{
    protected $template = '@detail/flat.html.twig';

    public function getHeadTitle()
    {
        return $this->renderTemplate('@detail/blocks/flat/sale/head_title.twig');
    }

    public function getPageTitle()
    {
        return $this->renderTemplate('@detail/blocks/flat/sale/page_title.twig');
    }
}

