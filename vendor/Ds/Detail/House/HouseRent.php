<?php

/**
 * Description of HouseSale
 *
 * @author pahhan
 */
class Ds_Detail_House_HouseRent extends Ds_Detail_AbstractDetail
{
    protected $template = '@detail/house_rent.html.twig';

    public function getHeadTitle()
    {
        return $this->renderTemplate('@detail/blocks/house/rent/head_title.twig');
    }

    public function getPageTitle()
    {
        return $this->renderTemplate('@detail/blocks/house/rent/page_title.twig');
    }
}

