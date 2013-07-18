<?php
/**
 * Description of Land
 *
 * @author pahhan
 */
class Domstor_Detail_Supply_Land extends Domstor_Detail_Supply
{
	public function getPageTitle()
	{
		$a = $this->getData();

		$out = $this->getTitle();

		if( $a['Agency']['name'] ) $out.=' &mdash; '.$a['Agency']['name'];

		return $out;
	}

	public function getTitle()
	{
		$a = $this->getData();

		$out = $this->getOfferType2().' '.$this->getVar('land_type', '��������� �������');

        if( $a['city'] and !$this->in_region ) $out.= ' � '.$a['city'];

		$addr = $this->getTitleAddress();
        if( $addr ) $out.= ', '.$addr;

		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['land_type'] ) $annotation.=', '.$a['land_type'];
		if( $a['square_ground'] )
		{
			$annotation.=', '.$a['square_ground'];
			if( $a['square_ground_unit'] ) $annotation.=' '.$a['square_ground_unit'];
		}
		$address=$this->getAddress();
		if( $address ) $annotation.=', '.$address;
		$price=$this->getPrice();
		if( $price ) $annotation.=', '.$price;
		if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getSizeBlock()
	{
        $out = '';
		$out.= $this->getElementIf('�������:', $this->getVar('square_ground'), ' '.$this->getVar('square_ground_unit') );

        if( $this->getVar('size_ground_x') and $this->getVar('size_ground_y') )
            $out.=$this->getElement('�� ���������:', $this->getVar('size_ground_x').' x '.$this->getVar('size_ground_y').' �' );

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
		$a=&$this->object;
        $out = '';
		$out.= $this->getElementIf('��� ���������:', $this->getVar('living_building'));
		$out.= $this->getElementIf('����� ����� ������:', $this->getVar('room_count'));
		$out.= $this->getElementIf('���������� ������:', $this->getVar('building_floor'));
		$out.= $this->getElementIf('�������:', $this->getVar('square_house'));
		$out.= $this->getElementIf('���������:', $this->getVar('heat'));
		$out.= $this->getElementIf('��������� ��������:', $this->getVar('state'));

        $other = '';
		if( $this->getVar('bath_house') ) $other.= '����, ';
		if( $this->getVar('swimming_pool') ) $other.= '�������, ';
		if( $this->getVar('garage') ) $other.= '�����, ';
		if( $this->getVar('other_building') ) $other.= '������, ';
		if( $other )
		{
			$other= substr($other, 0, -2);
			$out.= $this->getElement('������������� ���������:', $other);
		}

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
        $out = '';

        $electro = '';
		$electro.= $this->getElementIf($this->nbsp(8).'����������', $this->getVar('electro_voltage'), ' �');
		$electro.= $this->getElementIf($this->nbsp(8).'��������', $this->getVar('electro_power'), ' ���');
		if( $this->getVar('electro_reserve') ) $electro.= $this->getElement('', '��������� ���������� ����������');
		if( $this->getVar('electro_not') ) $electro.= $this->getElement('', '��� �������������');
		if( $electro ) $electro = $this->getElement($this->nbsp(4).'����������������:', '').$electro;

        $show = false;
		$communications = $electro;
        $water = '';
		if( $this->getVar('water_basin') ) $water=' (����������� ���������� �� ���������� �������)';
		$communications.= $this->getElementIf($this->nbsp(4).'�������������:', $this->getVar('water').$water);
		$communications.= $this->getElementIf($this->nbsp(4).'��� ������������ ���������� ������������:', $this->getVar('communications_year'));
		if( $communications )
		{
			$communications = $this->getElement('������������:', '').$communications;
			$show = true;
		}

		//	���������
        $state = '';
		$state.= $this->getElementIf($this->nbsp(4).'������� �����:', $this->getVar('height_difference'), ' �');
		$state.= $this->getElementIf($this->nbsp(4).'������ ������:', $this->getVar('coat_structure'));
		$state.= $this->getElementIf($this->nbsp(4).'������ ��������� ���:', $this->getVar('ground_water_height'));
		$state.= $this->getElementIf($this->nbsp(4).'������� ��������� ������:', $this->getVar('karstic_hole'));

		if( $state )
		{
			$state = $this->getElement('��������� �������:', '').$state;
			$show = true;
		}

		if( $show )
		{
			$out='<div class="domstor_object_technic">
					<h3>����������� ��������������</h3>
					<table>'.
						$communications.
						$state.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getFurnitureBlock()
	{
        $out = '';

		$out.= $this->getElementIf('����������:', $this->getVar('fence'));
		$out.= $this->getElementIf('����������� �� �������:', $this->getVar('remote_water'), ' �');
		$out.= $this->getElementIf('������������ ����:', $this->getVar('water_conservation_zone'), ' �');
		$out.= $this->getElementIf('����������� �� ������� �������:', $this->getVar('remote_forest'), ' �');
		$out.= $this->getElementIf('������� ����������� �� �������:', $this->getVar('forest_cover'));

        $road = '';
		$road.= $this->getElementIf($this->nbsp(4).'����������� ������� �� ����������:', $this->getVar('remote_highway'), ' �');
		$road.= $this->getElementIf($this->nbsp(4).'�������� ���������� ������:', $this->getVar('road_covering'));
		$road.= $this->getElementIf($this->nbsp(4).'��������� �������� �����:', $this->getVar('road_state'));
		$road.= $this->getElementIf($this->nbsp(4).'����������� ������� �����:', $this->getVar('road_winter'));

		if( $road )
		{
			$out.= $this->getElement('�������� �������:','').$road;
		}
		$out.= $this->getElementIf('��� ���������� ���������:', $this->getVar('settlement_type'));

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