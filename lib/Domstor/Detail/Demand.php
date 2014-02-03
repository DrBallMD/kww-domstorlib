<?php

/**
 * Description of Demand
 *
 * @author pahhan
 */
abstract class Domstor_Detail_Demand extends Domstor_Detail_Common
{
	public function getObjectCode()
	{
		return '������ '.$this->object['code'];
	}

	public function getOfferType($action)
	{
		if( $action=='rent' )
		{
			$out='������';
		}
		else
		{
			$out='�����';
		}
		return $out;
	}

    public function getOfferType2()
	{
		if( $this->action == 'rent' )
		{
			$out='�����';
		}
		else
		{
			$out='�����';
		}
		return $out;
	}

	public function getAddress()
	{
		$a = &$this->object;
        $out = '';
		if( $this->in_region )
		{
			$out.= $this->getIf($this->getVar('address_note'), '', ', ');
			$out.= $this->getIf($this->getVar('city'), '', ', ');
			$out= substr($out, 0, -2);
		}
		else
		{
			if( $this->getVar('district') ) $out='������: '.$a['district'].', ';
			if( $this->getVar('street') ) $out.='�����: '.$a['street'].', ';
			$out.= $a['city'];
		}

		return $out;
	}

	public function getFormatedPrice()
	{
        $min = (float) $this->getVar('price_full_min');
        $max = (float) $this->getVar('price_full_max');
		$out = $this->getPriceFromTo($min, $max, $this->getVar('price_currency'));
		return $out;
	}

	public function getFormatedRent()
	{
		$min = (float) $this->getVar('rent_full_min');
        $max = (float) $this->getVar('rent_full_max');
		$out = $this->getPriceFromTo($min, $max, $this->getVar('rent_currency'), $this->getVar('rent_period'));
		return $out;
	}

	public function getPrice()
	{
		if( $this->action=='rent' )
		{
			$out=$this->getFormatedRent();
		}
		else
		{
			$out=$this->getFormatedPrice();
		}
		return $out;
	}

	public function getLocationBlock()
	{
		$a = &$this->object;
		$location = $this->getAddress();

		if( $location )
		{
			if( $this->getVar('address_note') ) $location.=', '.$a['address_note'];
			$location='<p>'.$location.'</p>';
			if( $this->getVar('first_line_want') ) $location.='<p>������ �����: '.$a['first_line_want'].'</p>';
			if( $this->getVar('metro') ) $location.='<p>'.$a['metro'].'</p>';
			if( $this->getVar('available_bus') ) $location.='<p>�� ��������� �� ����� '.$a['available_bus'].' ���.</p>';
			if( $this->getVar('available_metro') ) $location.='<p>�� ����� �� ����� '.$a['available_metro'].' ���.</p>';
			if( $this->getVar('available_bus_to_metro') ) $location.='<p>����������� �� ����� �� ����� '.$a['available_bus_to_metro'].' ���.</p>';
			$location='<div class="domstor_object_place">
						<h3>��������������</h3>'.$location.'
					</div>';
		}

		return $location;
	}

	public function getFinanceBlock()
	{
        $out = '';

        $price = $this->getFormatedPrice();
        if( $this->getVar('active_sale') and $price )
            $out.= $this->getElementIf('������:', $price);

        $rent = $this->getFormatedRent();
        if( $this->getVar('active_rent') and $rent )
            $out.= $this->getElementIf('������:', $rent);

        if( $out )
		{
			$out = '<div class="domstor_object_finance">
					<h3>���������� �������:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

    public function getEntityType()
    {
        return '������';
    }

    public function getSecondHead()
    {
        if( !$this->show_second_head ) return '';

        $tmpl = '<h3>��� ������: %s</h3>';
        return sprintf($tmpl, $this->getCode());
    }
}