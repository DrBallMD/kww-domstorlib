<?php

/**
 * Description of Garage
 *
 * @author pahhan
 */
class Domstor_Detail_Demand_Garage extends Domstor_Detail_Demand
{
	public function getPageTitle()
	{
		$a = &$this->object;

		$out = $this->getTitle();

		if( isset($a['Agency']) and isset($a['Agency']['name']) ) $out.=' &mdash; '.$a['Agency']['name'];

		return $out;
	}

	public function getTitle()
	{
		$out = $this->getOfferType2().' ';
        $out.= $this->getVar('garage_type', '�����');
        $city = $this->getVar('city');
        if( $city ) $out.= ' � '.$city;
		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['garage_type'] ) $annotation.=', '.$a['garage_type'];
		if( $a['size_x'] and $a['size_y'] ) $annotation.=', �� ����� '.$a['size_x'].' x '.$a['size_y'].' �';
		$location=$this->getAddress();
		if( $location ) $annotation.=', '.$location;
		$price=$this->getPrice();
		if( $price ) $annotation.=', '.$price;
		if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getAllocationBlock()
	{
		$a=&$this->object;
		if( $a['placing_type'] ) $out='<p>'.$a['placing_type'].'</p>';
		if( $out )
		{
			$out='<div class="domstor_object_rooms">
					<h3>������������</h3>'.
					$out.
				'</div>';
		}
		return $out;
	}

	public function getSizeBlock()
	{
        $out = '';
		$out.= $this->getElementIf('������ �� �����:', $this->getVar('size_x'), ' �' );
		$out.= $this->getElementIf('����� �� �����:', $this->getVar('size_y'), ' �' );
		$out.= $this->getElementIf('������ �� �����:', $this->getVar('size_z'), ' �' );
		$out.= $this->getElementIf('������� �� �����:', $this->getVar('square'), ' ��.�.' );

        $gate_size = '';
		$gate_size.= $this->getElementIf($this->nbsp(4).'������ �� �����:', $this->getVar('gate_height'), ' �' );
		$gate_size.= $this->getElementIf($this->nbsp(4).'������ �� �����:', $this->getVar('gate_width'), ' �' );

        if( $gate_size ) $out.= $this->getElement('������ �����:','').$gate_size;

        $out.= $this->getElementIf('���������� ����������:', $this->getFromTo($this->getVar('car_count_min'), $this->getVar('car_count_max')));
		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>�������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getTechnicBlock()
	{
        $out = '';

		$out.= $this->getElementIf('������� �������:', $this->getVar('cellar_want'));
		$out.= $this->getElementIf('������� �������������� ��������� ���:', $this->getVar('repair_pit_want'));

        $electro = '';
		$electro.= $this->getElementIf($this->nbsp(8).'����������', $this->getVar('electro_voltage'), ' �');
		$electro.= $this->getElementIf($this->nbsp(8).'�������� �� �����', $this->getVar('electro_power'), ' ���');
		if( $electro ) $electro = $this->getElement($this->nbsp(4).'����������������:', '').$electro;

		$communications = $electro;

		$communications.= $this->getElementIf($this->nbsp(4).'��������������:', $this->getVar('heat_want'));
		$communications.= $this->getElementIf($this->nbsp(4).'����������:', $this->getVar('ventilation_want'));
		$communications.= $this->getElementIf($this->nbsp(4).'�������� ������������:', $this->getVar('signalizing_want'));
		$communications.= $this->getElementIf($this->nbsp(4).'���������������:', $this->getVar('video_tracking_want'));
		$communications.= $this->getElementIf($this->nbsp(4).'��������������� ������������:', $this->getVar('fire_signalizing_want'));
		$communications.= $this->getElementIf($this->nbsp(4).'������� �������������:', $this->getVar('fire_prevention_want'));

		if( $communications )
		{
			$communications = $this->getElement('������������:', '').$communications;
			$out.= $communications;
		}

		if( $this->getVar('state') ) $out.=$this->getElement('��������� ������� �� ����� ���:', $this->getVar('state'));


		if( $out )
		{
			$out='<div class="domstor_object_technic">
					<h3>����������� ��������������</h3>
					<table>'.
						$out.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getFurnitureBlock()
	{
        $out = '';

		$out.= $this->getElementIf('������ ����������:', $this->getVar('territory_security_want'));
		$out.= $this->getElementIf('����� ��������� ���:', $this->getVar('public_repair_pit_want'));
		$out.= $this->getElementIf('���������� � �����������:', $this->getVar('auto_service_want'));
		$out.= $this->getElementIf('��������� � �����������:', $this->getVar('auto_washing_want'));

		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>����������, ������������:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return '������ �� �������';
		$out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
                     $this->getSecondHead().
					//'<p>'.$this->getAnnotation().'</p>'.
				'</div>
				<div class="domstor_object_common">'.
					$this->getLocationBlock().
					$this->getAllocationBlock().
					//$this->getFloorsBlock().
				'</div>';
		$out.=$this->getSizeBlock();
		$out.=$this->getTechnicBlock();
		$out.=$this->getFurnitureBlock();
		$out.=$this->getFinanceBlock();
		$out.=$this->getCommentBlock();
		$out.=$this->getContactBlock();
		$out.=$this->getDateBlock();
		$out.=$this->getNavigationHtml();
		return $out;
	}

}