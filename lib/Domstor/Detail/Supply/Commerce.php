<?php
/**
 * Description of Commerce
 *
 * @author pahhan
 */
class Domstor_Detail_Supply_Commerce extends Domstor_Detail_Supply
{
	public function getPageTitle()
	{
		$a = &$this->object;
		$out = $this->getTitle();
		$out.= $this->getIf(strtolower($this->getPurpose()), ' (����������: ', ')');

		$out.= $this->getIf( $a['Agency']['name'], ' &mdash; ');
		return $out;
	}

	public function getTitle()
	{
		$a = &$this->object;
		$out = $this->getOfferType2().' ������� ��������� ';

		if( $a['city'] ) $out.= '� '.$this->getVar('city');

		$addr = $this->getStreetBuilding();
		$district = ($this->getVar('district_parent') == '��������' or $this->getVar('district') == '��������' )? ', '.$this->getVar('district') : '';
		$out.= $district.($addr? ', '.$addr : ($this->getVar('address_note')? ', '.$this->getVar('address_note') : '' ));

		return $out;
	}

	public function getSquareGround()
	{
		$a=&$this->object;
		return $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit'), '������� ���������� ������� ');
	}

	public function getSquareHouse()
	{
		$a=&$this->object;
		return $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max'), ' ��.�',  '������� ��������� ');
	}

	public function getSquare()
	{
		$a=&$this->object;
		if( $a['Purposes'][1009] )
		{
			if( count($a['Purposes'])==1 )
			{
				$out.=$this->getSquareGround();
			}
			else
			{
				$out=$this->getIf($this->getSquareHouse(), '', ', ');
				$out.=$this->getSquareGround();
			}
		}
		else
		{
			$out=$this->getSquareHouse();
		}
		return $out;
	}

	public function getFloor($min, $max)
	{
		if( isset($min) )
		{
			if( isset($max) )
			{
				if($min==$max)
				{
					if( $min!='0' )
					{
						$out=$min;
					}
				}
				else
				{
					$out=$min.' &ndash; '.$max;
				}
			}
			else
			{
				$out=$min;
			}
		}
		else
		{
			if( isset($max) )
			{
				if( $max!='0' )
				{
					$out=$max;
				}
			}
		}
		return $out;
	}

	public function getObjectFloor($min, $max)
	{
		$min_flag = FALSE;
		$max_flag = FALSE;
		$out = '';

		if( isset($min) and $min != '' ) $min_flag = TRUE;
		if( isset($max) and $max != '' ) $max_flag = TRUE;

		if( $min_flag and $max_flag )
		{
			if( $min == $max )
			{
				$out = ($min == '0')? '������' : $min;
			}
			else
			{
				$out = '��&nbsp;'.$min.' ��&nbsp;'.$max;
				$out = str_replace('0', '������', $out);
			}
		}
		elseif( $min_flag or $max_flag )
		{
			if( $min_flag )
			{
				$out = '��&nbsp;'.$min;
			}
			else
			{
				$out = '��&nbsp;'.$max;
			}
			$out = str_replace('0', '������', $out);
		}

		return $out;
	}

	public function getAnnotation()
	{
		$a = $this->object;
		$annotation=$this->getOfferType($this->action);
		$annotation.=$this->getIf( $this->getPurpose(), ', ');
		if( $this->getVar('complex_id') )$annotation.=', � ������� �������������� ���������';
		$annotation.=$this->getIf( $this->getSquare(), ', ');
		$annotation.=$this->getIf( $this->getAddress(), ', ');
		$annotation.=$this->getIf( $this->getPrice(), ', ');
		$annotation.=$this->getIf( $this->getVar('note_addition'), ', ');
		return $annotation;
	}

	public function getDelayBlock()
	{
		$a = &$this->object;
        $out = '';
		if( $this->getVar('delay_sale_dt') ) $out.='<p>����������� ������� � '.date('d.m.Y', strtotime($this->getVar('delay_sale_dt'))).'</p>';
		if( $this->getVar('delay_rent_dt') ) $out.='<p>����������� ������ � '.date('d.m.Y', strtotime($this->getVar('delay_rent_dt'))).'</p>';
		if( $out )
		{
			$out='<div class="domstor_object_delay">
					<h3>����������� �����������</h3>'.
					$out.
				'</div>';
		}
		return $out;
	}

	public function getComplexBlock()
	{
		$a = $this->object;
        $out ='';
		if( isset($a['complex']) and $a['complex'] )
		{
			$this->object = $a['complex'];
			$out = '������ <a href="'.$this->getCommerceUrl($this->object['id']).'" class="domstor_link">'.$this->object['code'].'</a>, '.$this->getAnnotation();
			$this->object = $a;
		}

		if( $out )
		{
			$out='<div class="domstor_object_complex">
					<h3>� ������� �������������� ���������</h3>'.
					$out.
				'</div>';
		}

		return $out;
	}

	public function getComplexObjectsBlock()
	{
		$a = $this->object;
        $out = '';
		if( isset($a['ComplexObjects']) and is_array($a['ComplexObjects']) )
		{
			foreach( $a['ComplexObjects'] as $object )
			{
				$this->object=$object;
				$out.='<p><a href="'.$this->getCommerceUrl($this->object['id']).'" class="domstor_link">������ '.$object['code'].'</a>, '.$this->getAnnotation().'</p>';
			}
			$this->object=$a;
		}

		if( $out )
		{
			$out='<div class="domstor_object_complexobj">
					<h3>������� �������������� ���������</h3>'.
					$out.
				'</div>';
		}

		return $out;
	}

	public function getPurposeBlock()
	{
		$a = &$this->object;
        $out = '';

		if( isset($a['Purposes'][1009]) or isset($a['class']) )
		{
			$purp=array();
			for($i=1013; $i<1022; $i++)
			{
				if( $a['Purposes'][$i] ) $purp[]=$a['Purposes'][$i];
			}
			$out=implode(', ', $purp);
			$out=$this->getElementIf('��������� �������:', $out);
			$out.=$this->getElementIf('����� �������:', $this->getVar('class'));
			if( $out )
			{
				$out='<div class="domstor_object_purpose">
						<h3>����������</h3><table>'.
						$out.
					'</table></div>';
			}
		}
		return $out;
	}

	public function getSizeBlock()
	{
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('������� ���������:', $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max'), ' ��.�') );
		$out.=$this->getElementIf('������� ���������� �������:', $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit')) );
		$out.=$this->getElementIf('������ ���������:', $this->getFromTo($this->getVar('height_min'), $this->getVar('height_max'), ' �') );
		$out.=$this->getElementIf('���������� �����:', $this->getVar('gate_count'));
		$out.=$this->getElementIf('������������ ������ �����:', $this->getVar('gate_height'), ' �');
		$out.=$this->getElementIf('������������ ������ �����:', $this->getVar('gate_width'), ' �');
		$out.=$this->getElementIf('���������� ������:', $this->getVar('door_count'));
		$out.=$this->getElementIf('���������� ����������� ����:', $this->getVar('load_window_count'));

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

	public function getAllocationSubBlock()
	{
		$a=&$this->object;
		if( $this->getVar('placing_features_id')==-1001 ) return;
		if( $this->getVar('placing_features_id')==1116 )
		{
			$name='� ��������� ������';
		}
		else
		{
			$name=$this->getVar('placing_features');
		}
		$pt=array();
        $inside = $out = '';
		if( $this->getVar('placing_type') ) $pt[]=$this->getVar('placing_type');
		if( $this->getVar('placing_type2') ) $pt[]=$this->getVar('placing_type2');
		$pt=implode(', ', $pt);
		if( $pt ) $pt=' ('.$pt.')';
		if( $this->getVar('inside_building') ) $inside=', ��������� ������ ������ ('.$this->getVar('inside_building').')';
		$out=$this->getElementIf('����������� ����������:', $name.$pt.$inside);
		return $out;
	}

	public function getAllocationBlock()
	{
        $out = '';
		$out.=$this->getElementIf('���� �������:',$this->getObjectFloor($this->getVar('object_floor_min'), $this->getVar('object_floor_max')));
		$out.=$this->getElementIf('��������� ������:', $this->getFromTo($this->getVar('building_floor_min'), $this->getVar('building_floor_max')) );
		if( $this->getVar('ground_floor') ) $out.=$this->getElement('', '������� ��������� ����');
		$out.=$this->getAllocationSubBlock();
		if( $out )
		{
			$out='<div class="domstor_object_allocation">
					<h3>���������� �������</h3>
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
		$out.=$this->getElementIf('���������� �����:', $this->getVar('phone_count'));
		$out.=$this->getElementIf('��������-�����������:', $this->getVar('internet_count'));

        $electro = '';
		$electro.=$this->getElementIf($this->nbsp(4).'����������', $this->getVar('electro_voltage'), ' �');
		$electro.=$this->getElementIf($this->nbsp(4).'��������', $this->getVar('electro_power'), ' ���');
		$electro.=$this->getElementIf($this->nbsp(4).'����������� ���������� �������� ��', $this->getVar('electro_power_up'), ' ���');
		if( $this->getVar('electro_reserve') ) $electro.=$this->getElement('', '���� ��������� ���������� ����������');
		if( $this->getVar('electro_not') ) $electro.=$this->getElement('', '��� �������������');
		if( $this->getVar('electro_allow') ) $electro.=$this->getElement('', '�������� ������������ ��� �����������');
		if( $electro ) $electro = $this->getElement('����������������:', '').$electro;
		$out.=$electro;


		$out.=$this->getElementIf('��������������:', $this->getVar('heat'));
		if( $this->getVar('heat_control') ) $out.=$this->getElementIf('', '������������ ������������� �����');
		$out.=$this->getElementIf('�������������:', $this->getVar('water'));
		if( $this->getVar('water_reserve') ) $out.=$this->getElementIf('', '��������� ��������');
		$out.=$this->getElementIf('�����������:', $this->getVar('sewerage'));
		$out.=$this->getElementIf('����������:', $this->getVar('ventilation'));
		$out.=$this->getElementIf('�������������:', $this->getVar('gas'));

        $construction = '';
		$construction.=$this->getElementIf($this->nbsp(4).'�������� �������� ����:', $this->getVar('material_wall'));
		$construction.=$this->getElementIf($this->nbsp(4).'�������� ����������:', $this->getVar('material_ceiling'));
		$construction.=$this->getElementIf($this->nbsp(4).'�������� ������� �����������:', $this->getVar('material_carrying'));
		$construction.=$this->getElementIf($this->nbsp(4).'����������� ��� ������:', $this->getVar('pillar_step'), ' �');
		$construction.=$this->getElementIf($this->nbsp(4).'�������� �����:', $this->getVar('paul_coating'));
		$construction.=$this->getElementIf($this->nbsp(4).'����� �����:', $this->getVar('paul_bias'));
		$construction.=$this->getElementIf($this->nbsp(4).'�������� �� ���:', $this->getVar('paul_loading'), ' ��/��.�');
		if( $construction )	$out.=$this->getElement('����������� ��������:', '').$construction;

		//	���������
        $state = '';
		$state.=$this->getElementIf($this->nbsp(4).'��� ���������:', $this->getVar('build_year'));
		$state.=$this->getElementIf($this->nbsp(4).'������� ������: ', $this->getVar('wearout'), '%');
		$state.=$this->getElementIf($this->nbsp(4).'���������:', $this->getVar('state'));
		if( $state ) $out.=$this->getElement('��������� �������:', '').$state;

        $ice = '';
		$ice.=$this->getElementIf($this->nbsp(4).'����������� ������������:', $this->getVar('refrigerator'));
		$ice.=$this->getElementIf($this->nbsp(4).'������������� �����:', $this->getFromTo($this->getVar('refrigerator_temperature_min'), $this->getVar('refrigerator_temperature_max'), ' &deg;C'));
		$ice.=$this->getElementIf($this->nbsp(4).'����� �����:', $this->getVar('refrigerator_capacity'), ' ���.�');
		if( $ice ) $out.=$this->getElement('����������� ������������:', '').$ice;

		$lifts = '';
        $lifts.=$this->getElementIf($this->nbsp(8).'������������ ����:', $this->getVar('lift_passenger'), $this->getIf($this->getVar('lift_passenger_weight'),', �� ', ' ��'));
		$lifts.=$this->getElementIf($this->nbsp(8).'�������� ����:', $this->getVar('lift_cargo'), $this->getIf($this->getVar('lift_cargo_weight'),', �� ', ' ��'));
		$lifts.=$this->getElementIf($this->nbsp(8).'���������:', $this->getVar('escalator'));
		$lifts.=$this->getElementIf($this->nbsp(8).'����������:', $this->getVar('travelator'));
		$lifts.=$this->getElementIf($this->nbsp(8).'�������:', $this->getVar('telpher'), $this->getIf($this->getVar('telpher_weight'),', �� ', ' ��'));
		$lifts.=$this->getElementIf($this->nbsp(8).'����-�����:', $this->getVar('crane_beam'), $this->getIf($this->getVar('crane_beam_weight'),', �� ', ' �'));
		$lifts.=$this->getElementIf($this->nbsp(8).'�������� ����:', $this->getVar('crane_trestle'), $this->getIf($this->getVar('crane_trestle_weight'),', �� ', ' �'));
		if( $lifts ) $infra.=$this->getElement($this->nbsp(4).'�������������� ����������:', '').$lifts;

		$infra = '';
        $infra.=$this->getElementIf($this->nbsp(4).'������:', $this->getVar('security'));
		$infra.=$this->getElementIf($this->nbsp(4).'������������:', $this->getVar('signalizing'));
		$infra.=$this->getElementIf($this->nbsp(4).'������� �������������:', $this->getVar('fire_prevention'));
		$infra.=$this->getElementIf($this->nbsp(4).'��������:', $this->getVar('dinning'));
		$infra.=$this->getElementIf($this->nbsp(4).'���������� ��������:', $this->getVar('toilet_count'));
		$infra.=$this->getElementIf($this->nbsp(4).'����������� �����������:', $this->getVar('technical_note'));
		if( $infra ) $out.=$this->getElement('��������������:', '').$infra;


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

	public function getTransportBlock()
	{
		$a=&$this->object;
        $transp = $out = '';
		$transp.=$this->getElementIf($this->nbsp(4).'�/� ����:', $this->getVar('realroad'));
		if( $this->getVar('realroad_not_active') ) $transp.=$this->getElementIf('', '�� �����������');
		$transp.=$this->getElementIf($this->nbsp(4).'������������� �����:', $this->getVar('realroad_length'), ' �');
		$transp.=$this->getElementIf($this->nbsp(4).'����� ��������:', $this->getVar('realroad_load_length'), ' �');
		if( $this->getVar('pandus') ) $transp.=$this->getElement('', '������');
		$transp.=$this->getElementIf($this->nbsp(4).'�������, �������� ����:', $this->getVar('road'));
		$transp.=$this->getElementIf($this->nbsp(4).'��������:', $this->getVar('parking'));
		if( $this->getVar('parking_underground') ) $transp.=$this->getElement($this->nbsp(4).'', '���������');
		if( $this->getVar('parking_many_floor') ) $transp.=$this->getElement($this->nbsp(4).'', '������������');
		if( $transp ) $out.=$this->getElement('��������, ��������, ��������:','').$transp;

        $road = '';
		$road.=$this->getElementIf($this->nbsp(4).'������������� ������������� ������:', $this->getVar('transport_stream'));
		$road.=$this->getElementIf($this->nbsp(4).'������������� ����������� ������:', $this->getVar('people_stream'));
		$road.=$this->getElementIf($this->nbsp(4).'�������� �����:', $this->getVar('road_covering'));
		$road.=$this->getElementIf($this->nbsp(4).'��������� �������� �����:', $this->getVar('road_state'));
		$road.=$this->getElementIf($this->nbsp(4).'���������� �����������, �����:', $this->getVar('lanes_count'));
		if( $this->getVar('one_way_traffic') ) $road.=$this->getElement($this->nbsp(4).'������������� ��������', '');
		if( $road ) $out.=$this->getElement('�������� �������:','').$road;

		if( $out )
		{
			$out='<div class="domstor_object_transport">
					<h3>������������ �������</h3>
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
		$out.=$this->getElementIf('����������� �� ����������:', $this->getVar('remote_highway'));
		$out.=$this->getElementIf('����������� �� �/� ����:', $this->getVar('remote_realroad'));
		$out.=$this->getElementIf('������:', $this->getVar('relief'));
		$out.=$this->getElementIf('������� ����:', $this->getVar('forest'));
		$out.=$this->getElementIf('������� �� �������:', $this->getVar('objects'));
		$out.=$this->getElementIf('���������������� ���������:', $this->getVar('territory'));

		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>����������, ������������:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getFinanceBlock()
	{
		$a = &$this->object;
		$out ='';

		$a['rent_m2_min'] = (float) $this->getVar('rent_m2_min');
		$a['rent_m2_max'] = (float) $this->getVar('rent_m2_max');
		$a['rent_full'] = (float) $this->getVar('rent_full');

		$a['price_m2_min'] = (float) $this->getVar('price_m2_min');
		$a['price_m2_max'] = (float) $this->getVar('price_m2_max');
		$a['price_full'] = (float) $this->getVar('price_full');

		$price_ground_unit = $this->getVar('price_m2_unit')? $a['price_m2_unit'] : '��.�';

		$price = '';
		$price.= $this->getIf($this->getFormatedPrice($a['price_full'], $this->getVar('price_currency')));

		if( $this->getVar('offer_parts') ) $price.= $this->getIf($this->getPriceFromTo($a['price_m2_min'], $a['price_m2_max'], $this->getVar('price_currency')), ' (', '/ '.$price_ground_unit.')' );

        if( $this->getVar('active_sale') )
            $out.=$this->getElementIf('����:', $price);

		$rent ='';
		$rent_ground_unit = $this->getVar('rent_m2_unit')? $a['rent_m2_unit'] : '��.�';
		$rent.= $this->getIf($this->getFormatedPrice($a['rent_full'], $this->getVar('rent_currency'), $this->getVar('rent_period')));

		if( $this->getVar('active_rent') )
        {
            $out.= $this->getElementIf('�������� ������:', $this->getPriceFromTo($a['rent_m2_min'], $a['rent_m2_max'], $this->getVar('rent_currency'), $this->getVar('rent_period')), '/ '.$rent_ground_unit );
            $out.= $this->getElementIf($this->nbsp(4).'�� ���� ������:', $this->getFormatedPrice($a['rent_full'], $this->getVar('rent_currency'), $this->getVar('rent_period')));
            $out.= $this->getElementIf('������������ �������:', $this->getVar('rent_communal_payment'));
        }

		if( $out )
		{
			$out = '<div class="domstor_object_finance">
					<h3>���������� �������:</h3>
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
					$this->getIf(strtolower($this->getPurpose()), '<h2>����������: ', '</h2>').
                    $this->getSecondHead().
				'</div>'.
				$this->getImagesHtml($this->object).
				'<div class="domstor_object_common">'.
					$this->getLocationBlock().
					$this->getDelayBlock().
				'</div>';
		$out.=$this->getRealizationBlock();
		$out.=$this->getComplexBlock();
		$out.=$this->getComplexObjectsBlock();
		$out.=$this->getPurposeBlock();
		$out.=$this->getSizeBlock();
		$out.=$this->getAllocationBlock();
		$out.=$this->getTechnicBlock();
		$out.=$this->getTransportBlock();
		$out.=$this->getFurnitureBlock();
		$out.=$this->getFinanceBlock();
		$out.=$this->getCommentBlock();
		$out.=$this->getContactBlock();
		$out.=$this->getDateBlock();
		$out.=$this->getNavigationHtml();
		return $out;
	}

}