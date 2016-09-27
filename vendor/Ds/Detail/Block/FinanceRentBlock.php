<?php

/**
 * Description of SaleFinanceBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_FinanceRentBlock extends Ds_Detail_Block_AbstractBlock
{
    protected $rent;
    protected $rent_m2;

    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

    public function active()
    {
        return $this->rent() || $this->rentM2();
    }

    public function rent()
    {
        if( is_null($this->rent) ) {
            $this->rent = '';
            $rent = $this->getData()->get('rent_full');
            if( $rent ) {
                $rent = number_format ($rent, 0, '', ' ');
            }
            $this->rent = $rent;
        }
        return $this->rent;
    }

    public function rentM2()
    {
        if( is_null($this->rent_m2) ) {
            $this->rent_m2 = '';
            $rent = $this->getData()->get('rent_m2');
            if( $rent ) {
                $rent = number_format ($rent, 0, '', ' ');
            }
            $this->rent_m2 = $rent;
        }
        return $this->rent_m2;
    }

    public function currency()
    {
        return $this->getData()->get('rent_currency');
    }

    public function period()
    {
        return $this->getData()->get('rent_period');
    }

}