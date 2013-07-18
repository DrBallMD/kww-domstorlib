<?php

/**
 * Description of Flat
 *
 * @author pahhan
 */
class Domstor_Detail_Supply_Flat extends Domstor_Detail_Supply
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

		$rooms = array(
			1 => '����',
			2 => '����',
			3 => '����',
			4 => '�������',
			5 => '����',
			6 => '�����',
			7 => '����',
		);

		$out = $this->getOfferType2().' ';

		if( isset($rooms[$a['room_count']]) ) $out.= $rooms[$a['room_count']].($this->_action=='exchange'? '��������� ' : '��������� ');

		$out.= ($this->_action=='exchange'? '��������' : '��������');

		if( $a['city'] and !$this->in_region ) $out.= ' � '.$a['city'];

		$addr = $this->getTitleAddress();
        if( $addr ) $out.= ', '.$addr;

		return $out;
	}

    public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['new_building'] ) $annotation.=', �����������';
		if( $a['room_count'] )  $annotation.=', '.$this->getRoomCount($a['room_count']);
		if( $a['in_communal'] ) $annotation.=' (� ����������)';
		if( $a['flat_type'] ) $annotation.=', '.$a['flat_type'];
		$squares=$this->getSquares($a['square_house'],$a['square_living'],$a['square_kitchen']);
		if( $squares )  $annotation.=', '.$squares;
		$floors=$this->getFloors($a['object_floor'], $a['building_floor']);
		if( $floors )  $annotation.=', '.$floors;
		$address=$this->getAddress();
		if( $address ) $annotation.=', '.$address;
		$price=$this->getPrice();
		if( $price ) $annotation.=', '.$price;
		if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getRoomsBlock()
	{
		$a=&$this->object;
		$room=$this->getRoomCount($a['room_count']);
		if( $room ) $room='<p>'.$room.'</p>';
		if( $a['in_communal'] ) $room.='<p>������� � ����������</p>';
		if( $a['in_pocket'] ) $room.='<p>���� ������</p>';
		if( $a['Together']['id'] )
		{
			$tgh=$a['Together'];
			$together='<a href="'.$this->getObjectUrl($tgh['id']).'" class="domstro_link">'.$tgh['code'].'</a>';
			if( $tgh['room_count'] ) $together.=', '.$this->getRoomCount($a['room_count']);
			$squares=$this->getSquares($tgh['square_house'], $tgh['square_living'], $tgh['square_kitchen']);
			if( $squares )  $together.=', '.$squares;
			$price=$this->getFormatedPrice($tgh['price_full'], $tgh['price_currency']);
			if( $price ) $together.=', '.$price;
			if( $a['Together'] ) $room.='<p>���������� ������� � �������� ���������:<br />'.$this->nbsp(4).$together.'</p>';
		}
		if( $room )
		{
			$room='<div class="domstor_object_rooms">
					<h3>����� ������</h3>'.
					$room.
				'</div>';
		}
		return $room;
	}

	public function getFloorsBlock()
	{
		$a=&$this->object;
		$out=$this->getFloors();
		if( $out ) $out='<p>'.$out.'</p>';
		if( $a['ground_floor'] ) $out.='<p>� ������ ������� ��������� ����</p>';
		if( $a['first_floor_commerce'] ) $out.='<p>������ ����� �������</p>';
		if( $out )
		{
			$out='<div class="domstor_object_floor">
					<h3>����</h3>'.
					$out.
				'</div>';
		}
		return $out;
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
        $square = $out = '';
		if( isset($a['height']) and $a['height'] ) $out=$this->getElement('������ ��������:', $a['height'], ' �.');
		if( isset($a['floor_count']) and $a['floor_count'] > 1 ) $out.=$this->getElement('���������� �������:', $a['floor_count']);
		if( isset($a['square_house']) and $a['square_house'] )  $square.=$this->getElement($this->nbsp(4).'�����:', $a['square_house']);
		if( isset($a['square_living']) and $a['square_living'] ) $square.=$this->getElement($this->nbsp(4).'�����:', $a['square_living']);
		if( isset($a['square_kitchen']) and $a['square_kitchen'] ) $square.=$this->getElement($this->nbsp(4).'�����:', $a['square_kitchen']);
		if( isset($a['square_pocket']) and $a['square_pocket'] ) $square.=$this->getElement($this->nbsp(4).'������:', $a['square_pocket']);
		if( $square ) $square=$this->getElement('�������, ��.�.:', '').$square;
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
		$a = &$this->object;
        $out = '';
        $communications='';
        $show = false;

		if( $this->getVar('phone') ) $communications.='�������, ';
		if( $this->getVar('internet') ) $communications.='��������, ';
		if( $this->getVar('cable_tv') ) $communications.='��������� ��, ';
		if( $this->getVar('door_phone') ) $communications.='�������, ';
		if( $this->getVar('gas') ) $communications.='����������, ';
		if( $this->getVar('satellite_tv') ) $communications.='����������� ��, ';
		if( $this->getVar('signalizing') ) $communications.='�������� ������������, ';
		if( $this->getVar('fire_prevention') ) $communications.='��������������� ������������, ';
		if( $communications )
		{
			$communications=substr($communications, 0, -2);
			$communications = $this->getElement('������������:', $communications);
			$show=true;
		}

        $san_tech = '';
		if( $this->getVar('toilet') ) $san_tech.=$this->getElement($this->nbsp(4).'C������:', $a['toilet']);
		if( $this->getVar('toilet_count') ) $san_tech.=$this->getElement($this->nbsp(4).'���������� ��������:', $a['toilet_count']);
		if( $this->getVar('santech_year') ) $san_tech.=$this->getElement($this->nbsp(4).'��� ������ (���������) ����������:', $a['santech_year']);
		if( $this->getVar('santech_material') ) $san_tech.=$this->getElement($this->nbsp(4).'������. �����:', $a['santech_material']);
		if( $this->getVar('sewerage_material') ) $san_tech.=$this->getElement($this->nbsp(4).'����� �����������:', $a['sewerage_material']);
		if( $this->getVar('heat_battery') ) $san_tech.=$this->getElement($this->nbsp(4).'������� ���������:', $a['heat_battery']);
		if( $san_tech )
		{
			$san_tech = $this->getElement('�������, ����������, ��������:', '').$san_tech;
			$show=true;
		}

        $construction = '';
		if( $this->getVar('material_wall') ) $construction.=$this->getElement($this->nbsp(4).'�������� �������� ����:', $a['material_wall']);
		if( $this->getVar('material_ceiling') ) $construction.=$this->getElement($this->nbsp(4).'�������� ����������:', $a['material_ceiling']);
		if( $this->getVar('material_carying') ) $construction.=$this->getElement($this->nbsp(4).'�������� ������� �����������:', $a['material_carying']);
		if( $construction )
		{
			$construction = $this->getElement('����������� ������:', '').$construction;
			$show=true;
		}

		if( $this->getVar('balcony_count') == 1 )
			$balcony=' ������';
		elseif( $this->getVar('balcony_count')<5 )
			$balcony=' �������';
		else
			$balcony=' ��������';

		if( $this->getVar('loggia_count') == 1 )
			$loggia=' ������';
		elseif( $this->getVar('loggia_count') < 5 )
			$loggia=' ������';
		else
			$loggia=' ������';

        $balc_log = '';
		if(  $this->getVar('balcony_count') ) $balc_log.=$this->getElement($this->nbsp(4).'���������� ��������:', $a['balcony_count']);
		if(  $this->getVar('loggia_count') ) $balc_log.=$this->getElement($this->nbsp(4).'���������� ������:', $a['loggia_count']);
		if(  $this->getVar('balcony_arrangement') ) $balc_log.=$this->getElement($this->nbsp(4).'������������:', $a['balcony_arrangement']);
		if( $balc_log )
		{
			$balc_log = $this->getElement('������, ������:', '').$balc_log;
			$show=true;
		}

        $windows = '';
		if( $this->getVar('window_material') ) $windows.=$this->getElement($this->nbsp(4).'�������� ���:', $a['window_material']);
		if( $this->getVar('window_glasing') ) $windows.=$this->getElement($this->nbsp(4).'��� ����������:', $a['window_glasing']);
		if( $this->getVar('window_opening') ) $windows.=$this->getElement($this->nbsp(4).'��� ����������:', $a['window_opening']);
		if( $windows )
		{
			$windows = $this->getElement('����:', '').$windows;
			$show=true;
		}

        $doors = '';
		if( $this->getVar('door_room') ) $doors.=$this->getElement($this->nbsp(4).'����� ������������:', $a['door_room']);
		$door_front_material = $this->getVar('door_front_material')? ', '.$this->getVar('door_front_material') : '';
		if( $this->getVar('door_front') ) $doors.=$this->getElement($this->nbsp(4).'������� �����:', $a['door_front'].$door_front_material);
		if( $this->getVar('door_pocket_material') ) $doors.=$this->getElement($this->nbsp(4).'����� � ������:', $a['door_pocket_material']);
		if( $doors )
		{
			$doors = $this->getElement('�����:', '').$doors;
			$show=true;
		}

		//	�������
        $finish = '';
		if( $this->getVar('finish_ceiling') ) $finish.=$this->getElement($this->nbsp(4).'�������:', $a['finish_ceiling']);
		if( $this->getVar('finish_paul') ) $finish.=$this->getElement($this->nbsp(4).'����: ', $a['finish_paul']);
		if( $this->getVar('finish_partition') ) $finish.=$this->getElement($this->nbsp(4).'�����������: ', $a['finish_partition']);
		if( $finish )
		{
			$finish = $this->getElement('�������:', '').$finish;
			$show=true;
		}

		//	���������
        $state = '';

		if( $this->getVar('build_year') ) $state.=$this->getElement($this->nbsp(4).'��� ���������:', $a['build_year']);
		if( $this->getVar('wearout') ) $state.=$this->getElement($this->nbsp(4).'������� ������: ', $a['wearout'].'%');
		if( $this->getVar('state') ) $state.=$this->getElement($this->nbsp(4).'���������:', $a['state']);
		if( $state )
		{
			$state = $this->getElement('��������� �������:', '').$state;
			$show=true;
		}

		if( $show )
		{
			$out='<div class="domstor_object_technic">
					<h3>����������� ��������������</h3>
					<table>'.
						$communications.
						$san_tech.
						$construction.
						$balc_log.
						$windows.
						$doors.
						$finish.
						$state.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getFurnitureBlock()
	{
		$a = &$this->object;
        $out = '';
		if( $this->getVar('furniture') ) $out.= $this->getElement('������:', $this->getVar('furniture'));
        if( $this->getVar('household_technique') ) $out.= $this->getElement('������� �������:', $this->getVar('household_technique'));
		if( $this->getVar('in_corner') ) $out.=$this->getElement('������������:', '������� ��������');

        $window_direction = '';
		if( $this->getVar('window_to_south') ) $window_direction='��, ';
		if( $this->getVar('window_to_north') ) $window_direction.='�����, ';
		if( $this->getVar('window_to_west') ) $window_direction.='�����, ';
		if( $this->getVar('window_to_east') ) $window_direction.='������, ';
		if( $window_direction )
		{
			$window_direction=substr($window_direction, 0, -2);
			$out.=$this->getElement('������������ ����:', $window_direction);
		}

        $parking = '';
		if( $this->getVar('garbage_chute') ) $out.=$this->getElement('������������:', '�������');
		if( $this->getVar('security') ) $out.=$this->getElement('������:', $a['security']);
		if( $this->getVar('sale_with_parking') ) $parking=', �������� ������� ��������� � ������� ��� �����������';
		if( $this->getVar('parking') ) $out.=$this->getElement('��������:', $a['parking'].$parking);

		$lifts = '';
        if( $this->getVar('lift_count') == 1 )
			$lift=' ����';
		elseif( $this->getVar('lift_count') < 5 )
			$lift=' �����';
		else
			$lift=' ������';
		if( $this->getVar('lift_count') ) $lifts=$a['lift_count'].$lift.', ';
		if( $this->getVar('lift_cargo') ) $lifts.='���� ��������, ';
		if( $lifts )
		{
			$lifts=substr($lifts, 0, -2);
			$lifts = $this->getElement('�����', $lifts);
			$out.=$lifts;
		}
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
		if( $this->isEmpty() ) return '������ �� ������';
		$out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
                    $this->getSecondHead().
                    $this->getDemandsBlock().
				'</div>'.
				$this->getImagesHtml($this->object).
				'<div class="domstor_object_common">'.
					$this->getLocationBlock().
					$this->getRoomsBlock().
					$this->getFloorsBlock().
				'</div>';
		$out.=$this->getRealizationBlock();
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