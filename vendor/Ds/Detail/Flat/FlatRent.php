<?php

/**
 * Description of FlatSale
 *
 * @author pahhan
 */
class Ds_Detail_Flat_FlatRent extends Ds_Detail_AbstractDetail
{
    protected $template = '@detail/flat.html.twig';

    public function getHeadTitle()
    {
        return $this->renderTemplate('@detail/blocks/flat/rent/head_title.twig');
    }

    public function getPageTitle()
    {
        return $this->renderTemplate('@detail/blocks/flat/rent/page_title.twig');
    }
}

