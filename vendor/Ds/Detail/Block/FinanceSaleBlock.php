<?php

/**
 * Description of SaleFinanceBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_FinanceSaleBlock extends Ds_Detail_Block_AbstractBlock
{
    protected $price;
    protected $price_m2;

    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

    public function active()
    {
        return $this->price() || $this->priceM2();
    }

    public function price()
    {
        if( $this->price ) return $this->price;
        $price = $this->getData()->get('price_full');
        if( $price ) $price = number_format ($price, 0, '', ' ');
        $this->price = $price;
        return $price;
    }

    public function priceM2()
    {
        if( $this->price_m2 ) return $this->price_m2;
        $price = $this->getData()->get('price_m2');
        if( $price ) $price = number_format ($price, 0, '', ' ');
        $this->price_m2 = $price;
        return $price;
    }

    public function currency()
    {
        return $this->getData()->get('price_currency');
    }


}

