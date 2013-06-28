<?php
/**
 * Description of House
 *
 * @author pahhan
 */
class Domstor_Detail_Demand_House extends Domstor_Detail_Demand
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
        $out.= $this->getVar('house_type', '������� ���, �������');
        $city = $this->getVar('city');
        if( $city ) $out.= ' � '.$city;
		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['house_type'] ) $annotation.=', '.$a['house_type'];
		$rooms=$this->getFromTo($a['room_count_min'], $a['room_count_max'], ' ����.');
		if( $rooms ) $annotation.=', '.$rooms;
		$location=$this->getAddress();
		if( $location ) $annotation.=', '.$location;
		$price=$this->getPrice();
		if( $price ) $annotation.=', '.$price;
		if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getRoomsBlock()
	{
		$a=&$this->object;
		$rooms=$this->getFromTo($a['room_count_min'], $a['room_count_max'], ' ����.');
		if( $rooms )
		{
			$rooms='<div class="domstor_object_rooms">
					<h3>����� ������</h3><p>'.
					$rooms.
				'</p></div>';
		}
		return $rooms;
	}

	public function getSizeBlock()
	{
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('����� ������� ����:', $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max')), ' ��.�');
		$out.=$this->getElementIf('������� ����� ������:', $this->getFromTo($this->getVar('square_living_min'), $this->getVar('square_living_max')), ' ��.�');
		$out.=$this->getElementIF('������� ���������� �������:',  $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max')), ' '.$this->getVar('square_ground_unit'));
		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>������� ���� � �������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getBuildingsBlock()
	{
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('����:', $this->getVar('bath_house_want'));
		$out.=$this->getElementIf('�������:', $this->getVar('swimming_pool_want'));
		$out.=$this->getElementIf('�����:', $this->getVar('garage_want'));
		$out.=$this->getElementIf('���������� ���� ��� ���������� �� �����:', $this->getVar('car_park_count'));

		if( $out )
		{
			$out='<div class="domstor_object_buildings">
					<h3>���������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getTechnicBlock()
	{
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('�������:', $this->getVar('phone_want'));
		$out.=$this->getElementIf('����������:', $this->getVar('gas_want'));
		$out.=$this->getElementIf('��������� ��:', $this->getVar('cable_tv_want'));
		$out.=$this->getElementIf('����������� ��:', $this->getVar('satellite_tv_want'));
		$out.=$this->getElementIf('��������:', $this->getVar('internet_want'));
		$out.=$this->getElementIf('�������� ������������:', $this->getVar('signalizing_want'));
		$out.=$this->getElementIf('��������������� ������������:', $this->getVar('fire_prevention_want'));
		$out.=$this->getElementIf('������� � ����:', $this->getVar('toilet_want'));

		$electro = '';
        $electro.=$this->getElementIf($this->nbsp(4).'����������:', $this->getVar('electro_voltage'));
		$electro.=$this->getElementIf($this->nbsp(4).'�������� �� �����:', $this->getVar('electro_power'));
		if( $electro ) $out.=$this->getElement('����������������:', '').$electro;

		$out.=$this->getElementIf('��������������:', $this->getVar('heat'));
		$out.=$this->getElementIf('�������������:', $this->getVar('water'));
		$out.=$this->getElementIf('�����������:', $this->getVar('sewerage'));

		$out.=$this->getElementIf('��������� ������� �� ����� ���:', $this->getVar('state'));


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
					$this->getRoomsBlock().
					//$this->getFloorsBlock().
				'</div>';
		$out.=$this->getSizeBlock();
		$out.=$this->getBuildingsBlock();
		$out.=$this->getTechnicBlock();
		$out.=$this->getFinanceBlock();
		$out.=$this->getCommentBlock();
		$out.=$this->getContactBlock();
		$out.=$this->getDateBlock();
		$out.=$this->getNavigationHtml();
		return $out;
	}
}