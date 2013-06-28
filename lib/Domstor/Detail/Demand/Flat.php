<?php
/**
 * Description of Flat
 *
 * @author pahhan
 */
class Domstor_Detail_Demand_Flat extends Domstor_Detail_Demand
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
		$a = &$this->object;

		$out = $this->getOfferType2().' ';

		$out.= str_replace('����.', '���������', $this->getRooms()).' ';
        $out.= '�������� � '.$a['city'];
        return $out;
	}

	public function getRooms()
	{
		$rooms = '';
        for($room=1; $room<6; $room++)
		{
			if( $this->getVar('room_count_'.$room) ) $rooms.=$room.', ';
		}
		$rooms=substr($rooms, 0, -2);
		if( $rooms )  $rooms.=' ����.';
		return $rooms;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $this->getVar('new_building') ) $annotation.=', �����������';
		$rooms=$this->getRooms();
		if( $rooms )  $annotation.=', '.$rooms;
		if( $this->getVar('in_communal') ) $annotation.=' (� ����������)';
		if( $this->getVar('object_floor') )  $annotation.=', '.$a['object_floor'].' ��.';
		$location=$this->getAddress();
		if( $location ) $annotation.=', '.$location;
		$price=$this->getPrice($this->getVar('price_full'), $this->getVar('price_currency'), $this->getVar('rent_full'), $this->getVar('rent_period'), $this->getVar('rent_currency'));
		if( $price ) $annotation.=', '.$price;
		if( $this->getVar('note_addition') ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getRoomsBlock()
	{
		$a = &$this->object;

        $rooms = '';
		for($room=1; $room<6; $room++)
		{
			if( $this->getVar('room_count_'.$room) ) $rooms.=$room.' ����., ';
		}
		$rooms=substr($rooms, 0, -2);

		if( $rooms ) $rooms='<p>'.$rooms.'</p>';
		if( $this->getVar('in_communal') ) $rooms.='<p>������� � ����������</p>';
		if( $rooms )
		{
			$rooms='<div class="domstor_object_rooms">
					<h3>����� ������</h3>'.
					$rooms.
				'</div>';
		}
		return $rooms;
	}

	public function getFloorsBlock()
	{
		$a = &$this->object;
        $floor = '';
		if( $this->getVar('object_floor') ) $floor='<p>'.$a['object_floor'].' ��.</p>';
		if( $this->getVar('object_floor_limit') ) $floor.='<p>'.$a['object_floor_limit'].'</p>';
		if( $floor )
		{
			$floor='<div class="domstor_object_floor">
					<h3>����</h3>'.
					$floor.
				'</div>';
		}
		return $floor;
	}

	public function getTypeBlock()
	{
		$a = &$this->object;
        $type = '';
		if( $this->getVar('flat_type') ) $type=$this->getElement('��� ��������:', $a['flat_type']);
		if( $this->getVar('planning') ) $type.=$this->getElement('����������:', $a['planning']);
		if( $this->getVar('building_material') ) $type.=$this->getElement('�������� ��������:', $a['building_material']);
		if( $type )
		{
			$type='<div class="domstor_object_type">
					<h3>��� �������� (������)</h3>
					<table>'.$type.'</table>
				</div>';
		}
		return $type;
	}

	public function getSizeBlock()
	{
		$a = &$this->object;
		$out = $this->getElementIf('������ ��������:', $this->getFromTo($this->getVar('height_min'), $this->getVar('height_max'), ' �'));

        $square = '';
		$square.=$this->getElementIf($this->nbsp(4).'�����:', $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max')));
		$square.=$this->getElementIf($this->nbsp(4).'�����:', $this->getFromTo($this->getVar('square_living_min'), $this->getVar('square_living_max')));
		$square.=$this->getElementIF($this->nbsp(4).'�����:',  $this->getFromTo($this->getVar('square_kitchen_min'), $this->getVar('square_kitchen_max')));
		if( $square ) $sqaure=$this->getElement('�������, ��.�.:', '').$square;

		$out.= $square;
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
		$a=&$this->object;
        $out = '';
        $communications = '';
		$communications.=$this->getElementIf($this->nbsp(4).'�������:', $this->getVar('phone_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'��������:', $this->getVar('internet_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'��������� ��:', $this->getVar('cable_tv_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'�������:', $this->getVar('door_phone_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'����������:', $this->getVar('gas_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'����������� ��:', $this->getVar('satellite_tv_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'�������� ������������:', $this->getVar('signalizing_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'��������������� ������������:', $this->getVar('fire_prevention_want'));

		if( $communications )
		{
			$out = $this->getElement('������������:', '').$communications;
		}

		$out.=$this->getElementIf($this->nbsp(4).'��������� ������� �� ����� ���:', $this->getVar('state'));


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
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('������� ������:', $this->getVar('with_furniture_want'));
		$out.=$this->getElementIf('�������� ����:', $this->getVar('lift_cargo_want'));
		$out.=$this->getElementIf('��������:', $this->getVar('parking'));
		$out.=$this->getElementIf('�������� ��������� � ������� ��� �����������:', $this->getVar('sale_with_parking'));

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
					$this->getRoomsBlock().
					$this->getFloorsBlock().
				'</div>';
		$out.=$this->getTypeBlock();
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