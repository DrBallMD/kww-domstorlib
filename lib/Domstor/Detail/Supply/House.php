<?php

/**
 * Description of House
 *
 * @author pahhan
 */
class Domstor_Detail_Supply_House extends Domstor_Detail_Supply
{
	public function getPageTitle()
	{
		$a = &$this->object;

		$out = $this->getTitle();

		if( $a['Agency']['name'] ) $out.=' &mdash; '.$a['Agency']['name'];

		return $out;
	}

	public function getTitle()
	{
		$a = &$this->object;

		$out = $this->getOfferType2().' '.$this->getVar('house_type', '���');

		if( $a['city'] and !$this->in_region ) $out.= ' � '.$a['city'];

		$addr = $this->getTitleAddress();
        if( $addr ) $out.= ', '.$addr;

		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['house_type'] ) $annotation.=', '.$a['house_type'];
		if( $a['room_count'] )  $annotation.=', '.$this->getRoomCount($a['room_count']);
		if( $a['square_house'] )  $annotation.=', '.$a['square_house'].' ��.�.';
		$address=$this->getAddress();
		if( $address ) $annotation.=', '.$address;
		$price=$this->getPrice($a['price_full'], $a['price_currency'], $a['rent_full'], $a['rent_period'], $a['rent_currency']);
		if( $price ) $annotation.=', '.$price;
		if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getRoomsBlock()
	{
		$a = &$this->object;
        $room = '';
		if( $this->getVar('room_count') )
		{
			$room='<div class="domstor_object_rooms">
					<h3>����� ������</h3><p>'.
					$this->getRoomCount($a['room_count']).
				'</p></div>';
		}
		return $room;
	}

	public function getFloorsBlock()
	{
		$a = &$this->object;
        $floor = '';
		if(  $this->getVar('building_floor') )
		{
			$floor = $a['building_floor'].' ��.';
			if(  $this->getVar('ground_floor') ) $floor.=', ��������� ����';
			if(  $this->getVar('mansard') ) $floor.=', ��������';
			if(  $this->getVar('cellar') ) $floor.=', ������';
			$floor='<div class="domstor_object_floor">
					<h3>�����</h3><p>'.
					$floor.
				'</p></div>';
		}
		return $floor;
	}

	public function getSizeBlock()
	{
		$a = &$this->object;
        $out = '';

        $square = '';
		if( $this->getVar('square_house') )  $square.=$this->getElement($this->nbsp(4).'�����:', $a['square_house']);
		if( $this->getVar('square_living') ) $square.=$this->getElement($this->nbsp(4).'�����:', $a['square_living']);
		if( $this->getVar('square_kitchen') ) $square.=$this->getElement($this->nbsp(4).'�����:', $a['square_kitchen']);
		if( $this->getVar('square_utility') ) $square.=$this->getElement($this->nbsp(4).'��������� ���������:', $a['square_utility']);
		if( $square ) $sqaure=$this->getElement('�������, ��.�.:', '').$square;
		$out.=$sqaure;

        $size = '';
		if( $this->getVar('size_house_x') and $this->getVar('size_house_y') ) $size.=$this->getElement($this->nbsp(4).'��������:', $a['size_house_x'].' x '.$a['size_house_y'].' �');
		if( $this->getVar('size_house_z') ) $size.=$this->getElement($this->nbsp(4).'������ ��� �����:', $a['size_house_z'].' �');
		if( $this->getVar('size_house_z_full') ) $size.=$this->getElement($this->nbsp(4).'������ � ������:', $a['size_house_z_full'].' �');
		if( $size ) $size=$this->getElement('�������:', '').$size;
		$out.=$size;

        $square_ground = '';
        $ground = '';
		if( $this->getVar('square_ground') )
		{
			$square_ground = $a['square_ground'].' '.strtolower($a['square_ground_unit']);
		}
		else
		{
			if( $this->getVar('square_ground_m2') )$square_ground = $a['square_ground_m2'].' ��.�.';
		}
		if( $square_ground ) $ground.=$this->getElement($this->nbsp(4).'�������:', $square_ground);
		if( $this->getVar('size_ground_x') and $this->getVar('size_ground_y') ) $ground.=$this->getElement($this->nbsp(4).'��������, �.:', $a['size_ground_x'].' x '.$a['size_ground_y'].' �');
		if( $ground ) $ground=$this->getElement('��������� �������:', '').$ground;
		$out.=$ground;

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

	public function getBuildingsBlock()
	{
		$a = &$this->object;
        $out = '';

		if( $this->getVar('bath_house') ) $out.=$this->getElement($this->nbsp(4).'����:', $a['bath_house']);
		if( $this->getVar('swimming_pool') ) $out.=$this->getElement($this->nbsp(4).'�������:', $a['swimming_pool']);
		if( $this->getVar('garage') ) $out.=$this->getElement($this->nbsp(4).'�����:', $a['garage']);
		if( $this->getVar('car_park_count') ) $out.=$this->getElement($this->nbsp(4).'���������� ���� ��� ����������:', $a['car_park_count']);
		if( $this->getVar('other_building') ) $out.=$this->getElement($this->nbsp(4).'������ ���������:', $a['other_building']);

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
		$a = &$this->object;
        $out = '';
        $show = false;

        $communications = '';
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
		if( $this->getVar('toilet_count') ) $san_tech.=$this->getElement($this->nbsp(4).'���������� �������� � ����:', $a['toilet_count']);

		//�������������
        $electro = '';
		if( $this->getVar('electro_voltage') ) $electro=$this->getElement($this->nbsp(4).'����������', $a['electro_voltage'].' �');
		if( $this->getVar('electro_power') ) $electro=$this->getElement($this->nbsp(4).'��������', $a['electro_power'].' ���');
		if( $this->getVar('electro_reserve') ) $electro=$this->getElement('', '��������� ���������� ����������');
		if( $this->getVar('electro_not') ) $electro=$this->getElement('', '��� �������������');
		if( $electro )
		{
			$electro = $this->getElement('����������������:', '').$electro;
			$show=true;
		}

		$heat=$this->getElementIf('��������������:', $a['heat']);
		$water=$this->getElementIf('�������������:', $a['water']);
		$sewerage=$this->getElementIf('�����������:', $a['sewerage']);

        $construction = '';
		$construction.=$this->getElementIf($this->nbsp(4).'�������� �������� ����:', $this->getVar('material_wall'));
		$construction.=$this->getElementIf($this->nbsp(4).'�������� ����������:', $this->getVar('material_ceiling'));
		$construction.=$this->getElementIf($this->nbsp(4).'�������� ������� �����������:', $this->getVar('material_carying'));
		$construction.=$this->getElementIf($this->nbsp(4).'�������� ������:', $this->getVar('roof_material'));
		$construction.=$this->getElementIf($this->nbsp(4).'��� ������:', $this->getVar('roof_type'));
		$construction.=$this->getElementIf($this->nbsp(4).'���������:', $this->getVar('foundation'));


		if( $construction )
		{
			$construction = $this->getElement('����������� ������:', '').$construction;
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

		//	�������
        $finish = '';
		if( $this->getVar('finish_ceiling') ) $finish.=$this->getElement($this->nbsp(4).'�������:', $a['finish_ceiling']);
		if( $this->getVar('finish_paul') ) $finish.=$this->getElement($this->nbsp(4).'����: ', $a['finish_paul']);
		if( $this->getVar('finish_partition') ) $finish.=$this->getElement($this->nbsp(4).'�����������: ', $a['finish_partition']);
		if( $this->getVar('facade') ) $finish.=$this->getElement($this->nbsp(4).'�����: ', $a['facade']);
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
						$electro.
						$heat.
						$water.
						$sewerage.
						$construction.
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

        $obstanovka = '';
		if( $this->getVar('with_furniture') ) $obstanovka.='� �������, ';
		if( $this->getVar('garden') ) $obstanovka.='�������, ������ �� �������, ';
		if( $this->getVar('landscape_design') ) $obstanovka.='����������� ������, ';
		if( $this->getVar('improvement_territory') ) $obstanovka.='����������� ���������� �������������, ';
		if( $obstanovka )
		{
			$obstanovka=substr($obstanovka, 0, -2);
			$out.=$this->getElement('����������:', $obstanovka);
		}

		$out.=$this->getElementIf('������:', $this->getVar('fence'));

		if( $this->getVar('garbage_chute') ) $out.=$this->getElement('������������:', '�������');
		if( $this->getVar('security') ) $out.=$this->getElement('������:', $a['security']);
		if( $this->getVar('sale_with_parking') ) $parking=', �������� ������� ��������� � ������� ��� �����������';
		if( $this->getVar('parking') ) $out.=$this->getElement('��������:', $a['parking'].$parking);

		$road = '';
        $road.=$this->getElementIf($this->nbsp(4).'�������� �����:', $this->getVar('road_covering'));
		$road.=$this->getElementIf($this->nbsp(4).'��������� �������� �����:', $this->getVar('road_state'));
		if( $road )
		{
			$out.=$this->getElement('�������� �������:','').$road;
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
					/* '<p>'.
						$this->getAnnotation()
					.'</p> */
				'</div>'.
				$this->getImagesHtml($this->object).
				'<div class="domstor_object_common">'.
					$this->getLocationBlock().
					$this->getRoomsBlock().
					$this->getFloorsBlock().
				'</div>';
		$out.=$this->getRealizationBlock();
		$out.=$this->getSizeBlock();
		$out.=$this->getBuildingsBlock();
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