<?php

/**
 * Description of Garage
 *
 * @author pahhan
 */
class Domstor_Detail_Supply_Garage extends Domstor_Detail_Supply
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

		$out = $this->getOfferType2().' ';

		$type = $a['garage_type']? strtolower($a['garage_type']) : '�����';
		$out.= $type.' ';

		$out.= $this->getTitleAddress();

		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['garage_type'] ) $annotation.=', '.$a['garage_type'];
		if( $a['size_x'] and $a['size_y'] ) $annotation.=', '.$a['size_x'].' x '.$a['size_y'].' �';
		$address=parent::getAddress();
		if( $address ) $annotation.=', '.$address;
		$price=$this->getPrice($a['price_full'], $a['price_currency'], $a['rent_full'], $a['rent_period'], $a['rent_currency']);
		if( $price ) $annotation.=', '.$price;
		if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getAddress()
	{
		$out = parent::getAddress();
		$space = $out? ', ' : '';
		if( $this->getVar('cooperative_name') ) $out.= $space.$this->getVar('cooperative_name');
		return $out;
	}

	public function getAllocationBlock()
	{
        $floor = '';
		$floor.=$this->getElementIf('��� ����������:', $this->getVar('placing_type'));
		$floor.=$this->getElementIf('���� ������������ �������:', $this->getVar('object_floor'), ' ����');
		$floor.=$this->getElementIf('������ � ��������:', $this->getVar('building_floor'));
		if( $floor )
		{
			$floor='<div class="domstor_object_allocation">
					<h3>������������</h3><table>'.
					$floor.
				'</table></div>';
		}
		return $floor;
	}

	public function getSizeBlock()
	{
		$out = '';
        $out.= $this->getElementIf('������:', $this->getVar('size_x'), ' �' );
		$out.= $this->getElementIf('�����:', $this->getVar('size_y'), ' �' );
		$out.= $this->getElementIf('������:', $this->getVar('size_z'), ' �' );
		$out.= $this->getElementIf('�������:', $this->getVar('square'), ' ��.�.' );

        $gate_size = '';
		$gate_size.= $this->getElementIf($this->nbsp(4).'������:', $this->getVar('gate_height'), ' �' );
		$gate_size.= $this->getElementIf($this->nbsp(4).'������:', $this->getVar('gate_width'), ' �' );
		if( $gate_size ) $out.= $this->getElement('������ �����:','').$gate_size;

		$out.= $this->getElementIf('���������� ����������:', $this->getVar('car_count'));
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

		$cellar = $this->getElementIf('������:', $this->getVar('cellar'));
		$repair_pit =$this->getElementIf('�������������� ��������� ���:', $this->getVar('repair_pit'));
		$gate_type = $this->getElementIf('��� �����:', $this->getVar('gate_type'));

        $electro = '';
		$electro.= $this->getElementIf($this->nbsp(8).'����������', $this->getVar('electro_voltage'), ' �');
		$electro.= $this->getElementIf($this->nbsp(8).'��������', $this->getVar('electro_power'), ' ���');
		if( $this->getVar('electro_reserve') ) $electro.= $this->getElement('', '��������� ���������� ����������');
		if( $this->getVar('electro_not') ) $electro.= $this->getElement('', '��� �������������');
		if( $electro ) $electro = $this->getElement($this->nbsp(4).'����������������:', '').$electro;

		$communications = '';
		$communications.= $this->getElementIf($this->nbsp(4).'��������������:', $this->getVar('heat'));
		$communications.= $this->getElementIf($this->nbsp(4).'����������:', $this->getVar('ventilation'));
		$communications.= $this->getElementIf($this->nbsp(4).'�������� ������������:', $this->getVar('signalizing'));
		$communications.= $this->getElementIf($this->nbsp(4).'���������������:', $this->getVar('video_tracking'));
		$communications.= $this->getElementIf($this->nbsp(4).'��������������� ������������:', $this->getVar('fire_signalizing'));
		$communications.= $this->getElementIf($this->nbsp(4).'������� �������������:', $this->getVar('fire_prevention'));

		$show = false;
        if( $communications )
		{
			$communications = $this->getElement('������������:', '').$communications;
			$show = true;
		}

        $construction = '';
		$construction.=$this->getElementIf($this->nbsp(4).'�������� �������� ����:', $this->getVar('material_wall'));
		$construction.=$this->getElementIf($this->nbsp(4).'�������� ����������:', $this->getVar('material_ceiling'));


		if( $construction )
		{
			$construction = $this->getElement('����������� ��������:', '').$construction;
			$show=true;
		}

		//	���������
        $state = '';
		if( $this->getVar('build_year') ) $state.= $this->getElement($this->nbsp(4).'��� ���������:', $this->getVar('build_year'));
		if( $this->getVar('wearout') ) $state.= $this->getElement($this->nbsp(4).'������� ������: ', $this->getVar('wearout').'%');
		if( $this->getVar('state') ) $state.= $this->getElement($this->nbsp(4).'���������:', $this->getVar('state'));
		if( $state )
		{
			$state = $this->getElement('��������� �������:', '').$state;
			$show = true;
		}

		if( $show )
		{
			$out = '<div class="domstor_object_technic">
					<h3>����������� ��������������</h3>
					<table>'.
						$cellar.
						$repair_pit.
						$gate_type.
                        $electro.
						$communications.
						$construction.
						$state.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getFurnitureBlock()
	{
		$a=&$this->object;
        $out = '';

		$out.= $this->getElementIf('������ ����������:', $this->getVar('territory_security'));
		$out.= $this->getElementIf('����� ��������� ���:', $this->getVar('public_repair_pit'));
		$out.= $this->getElementIf('���������� � �����������:', $this->getVar('auto_service'));
		$out.= $this->getElementIf('��������� � �����������:', $this->getVar('auto_washing'));

        $road = '';
		$road.= $this->getElementIf($this->nbsp(4).'�������� �������� � �����������:', $this->getVar('road_covering_inside'));
		$road.= $this->getElementIf($this->nbsp(4).'�������� ����� �� �������� � �����������:', $this->getVar('road_covering'));
		$road.= $this->getElementIf($this->nbsp(4).'��������� �������� �����:', $this->getVar('road_state'));

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
				'</div>'.
				$this->getImagesHtml($this->object).
				'<div class="domstor_object_common">'.
					$this->getLocationBlock().
				'</div>';
		$out.=$this->getAllocationBlock();
		$out.=$this->getRealizationBlock();
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