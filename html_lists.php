<?php
class DomstorCommonField extends HtmlTableField
{
	protected $in_region;

	public function isInRegion()
	{
		return $this->getTable()->isInRegion();
	}

	public function getIf($value, $before = NULL, $after = NULL, $not = NULL)
	{

		if( is_null($not) )
		{
			if( $value )
				return $before.$value.$after;
		}
		elseif( $value !== $not )
		{
			return $before.$value.$after;
		}
	}

	public function getFromTo($from, $to, $after=null, $before=null, $not_prefixed_one=false, $not_show='0')
	{
		$out = $space = '';
        $from_string='от&nbsp;';
		$to_string='до&nbsp;';
		if( ($from!==$not_show and isset($from)) or ($to!==$not_show and isset($to)) )
		{
			if( $from===$to )
			{

				$out=$from;

			}
			else
			{
				if( $from and $to )
				{
					$both=true;
					$space=' ';
					$not_prefixed_one=false;
				}
				if( $from!==$not_show and isset($from) )
				{
					$prefix = $not_prefixed_one? '' : $from_string;
					$out.=$prefix.$from;

				}
				if( $to!==$not_show and isset($to) )
				{
					$prefix = $not_prefixed_one? '' : $to_string;
					$out.=$space.$prefix.$to;
				}

			}
			$out=$before.$out.$after;
		}
		return $out;
	}

	public function getPriceFromTo($from, $to, $currency, $period=null)
	{
		$out = $space = '';
        $from_string='от&nbsp;';
		$to_string='до&nbsp;';
		if( $from!==null or $to!==null )
		if( $from != '0' or  $to != '0')
		{
			if( $from == $to )
			{

				$price=number_format($from, 0, ',', ' ');
				$price=str_replace(' ', '&nbsp;', $price);
				$period = $period? '&nbsp;'.$period : '';
				$out=$price.'&nbsp;'.$currency.$period;
			}
			else
			{
				if( $from != '0' )
				{
					$price=number_format($from, 0, ',', ' ');
					$price=str_replace(' ', '&nbsp;', $price);
					$out.=$from_string.$price;
					$space=' ';
				}

				if( $to != '0' )
				{
					$price=number_format($to, 0, ',', ' ');
					$price=str_replace(' ', '&nbsp;', $price);
					$out.=$space.$to_string.$price;
				}
				$out.='&nbsp;'.$currency.'&nbsp;'.$period;
			}

		}
		return $out;
	}
}

class DomstorContactField extends DomstorCommonField
{
	public function getValue()
	{
		$a = $this->getTable()->getRow();
        $out = $space = '';
		if( $a['agent_tel_work'] and $a['agent_tel_sot'] )
		{
			$space = ', ';
		}
		switch ($a['agency_tipcont'])
		{
			case '1':
				$out = $a['agency_tel_cont'];
			break;
			case '2':
				$out = $a['filial_phone'];
			break;
			case '3':
				$out = ( isset($a['agent_phone']) && !empty($a['agent_phone']) )? $a['agent_phone'] : $a['agent_tel_work'].$space.$a['agent_tel_sot'];
			break;
			default:
				$out='';
			break;
		}
		$out = str_replace(',', ', ', $out);
		return $out;
	}
}

class DomstorCommentField extends DomstorCommonField
{
	public function getValue()
	{
		$a = $this->getTable()->getRow();
		$out = str_replace(',', ', ', $a['note_web']);
		return $out;
	}
}

class DomstorCodeField extends DomstorCommonField
{
	protected $object_href;

	public function getValue()
	{
		$a=$this->getTable()->getRow();
		$href=str_replace('%id', $a['id'], $this->object_href);
		$out='<a href="'.$href.'" title="Перейти на страницу объекта '.$a['code'].'" class="domstor_link">'.$a['code'].'</a>';
		return $out;
	}
}

class DomstorThumbField extends DomstorCommonField
{
	protected $object_href;

	public function getValue()
	{
		$a = $this->getTable()->getRow();
		$out = '';
		$href=str_replace('%id', $a['id'], $this->object_href);
		if( isset($a['thumb']) )
		{
			$out = '<img src="http://'.$this->getTable()->getServerName().'/'.$a['thumb'].'" alt="" />';
			$out = '<a href="'.$href.'" title="Перейти на страницу объекта '.$a['code'].'" class="domstor_link">'.$out.'</a>';
		}
		return $out;
	}
}

class DomstorPriceField extends DomstorCommonField
{
	protected $action;

	public function __construct($attr)
	{
		parent::__construct($attr);
		if( $this->action=='rent' )
		{
			$this->title='Арендная ставка';
		}
		else
		{
			$this->title='Цена';
		}
	}

	public function getValue()
	{
		$a = $this->table->getRow();
        $out ='';
		if( $this->action=='rent' )
		{
			if( (float) $a['rent_full'] )
			{
				$out=number_format($a['rent_full'], 0, ',', ' ');
				$out.=$this->getIf($a['rent_currency'], ' ');
				$out.=$this->getIf($a['rent_period'], ' ');

			}
		}
		else
		{
			if( (float) $a['price_full'] )
			{
				$out=number_format($a['price_full'], 0, ',', ' ');
				$out.=$this->getIf($a['price_currency'], ' ');
			}
		}
		if( $out ) $out=str_replace(' ', '&nbsp;', $out);
		return $out;
	}
}

class DomstorDemandPriceField extends DomstorCommonField
{
	protected $action;

	public function getValue()
	{
		$a = $this->table->getRow();
        $out = '';
		if( $this->action=='rentuse' )
		{
			$out=$this->getPriceFromTo($a['rent_full_min'], $a['rent_full_max'], $a['rent_currency'], $a['rent_period']);
		}
		else
		{
			$out=$this->getPriceFromTo($a['price_full_min'], $a['price_full_max'], $a['price_currency']);
		}
		return $out;
	}

}

class DomstorFlatTypeField extends DomstorCommonField
{
	public function getValue()
	{
		$a=$this->getTable()->getRow();
		$out=$this->getIf($a['type']);
		$out.=$this->getIf($a['planning'], ' (', ')');
		return $out;
	}
}

class DomstorAddressField extends DomstorCommonField
{
	protected $in_region;
	protected $object_href;

	public function getValue()
	{
		$a = $this->getTable()->getRow();

		$address = '';

        $street = '';
        if( $a['street'] and $a['street_id'] ) {
            $street = $a['street'];
            if( isset($a['building_num']) and $a['building_num'] ) {
                $street.= ', '.$a['building_num'];
                if( $a['corpus'] ) {
                    if( is_numeric($a['corpus']) ) {
                        $street.= '/';
                    }
                    $street.= $a['corpus'];
                }
            }
        }

		if( $this->in_region )
		{
			if( $a['subregion'] ) $address.= $a['subregion'].', ';
            if( $a['location_name'] ) $address.= $a['location_name'].', ';
            $address.= $street? $street : $a['address_note'];
            $address = trim($address, ', ');
		}
		else
		{
			$address = $street;
		}

		if( isset($a['cooperative_name']) and $a['cooperative_name'] ) $address.= ', '.$a['cooperative_name'];
        $clear_address = trim($address, ', ');

        $out = '';
		if( $clear_address )
		{
			$href = str_replace('%id', $a['id'], $this->object_href);
            $out = sprintf('<a href="%s" title="Перейти на страницу объекта %s" class="domstor_link">%s</a>',
                    $href, $a['code'], $clear_address);
		}
		return $out;
	}
}

class DomstorAddressDemandField extends DomstorCommonField
{
	protected $in_region;
	protected $object_href;
	public function getValue()
	{
		$a = $this->getTable()->getRow();
        $out = '';
		if( $this->in_region)
		{
			$out.=$this->getIf($a['address_note'], '', ', ');
			$out.=$this->getIf($a['city'], '', ', ');

		}
		else
		{
			$out.=$this->getIf($a['street'], '', ', ');
			$out.=$this->getIf($a['address_note'], '', ', ');
		}
		$out=substr($out, 0, -2);

		if( $out )
		{
			$href=str_replace('%id', $a['id'], $this->object_href);
			$out='<a href="'.$href.'" title="Перейти на страницу заявки '.$a['code'].'" class="domstor_link">'.$out.'</a>';
		}
		return $out;
	}
}

class DomstorSquareGroundField extends DomstorCommonField
{
	public function getValue()
	{
		$a = $this->getTable()->getRow();
        $out = '';
		if( isset($a['square_ground']) and $a['square_ground'] )
		{
			$out=$a['square_ground'].' '.strtolower($a['square_ground_unit']);
		}
		elseif( isset($a['square_ground_m2']) and $a['square_ground_m2'] )
		{
			if( $a['square_ground_m2'] )$out=$a['square_ground_m2'].'&nbsp;кв.м.';
		}
		return $out;
	}
}

class DomstorSquareGroundDeamandField extends DomstorCommonField
{
	public function getValue()
	{
		$a = $this->getTable()->getRow();
		$out = $this->getFromTo($a['square_ground_min'], $a['square_ground_max'], '&nbsp;'.$a['square_ground_unit']);
		return $out;
	}
}

//FLAT-FIELDS**********************************************

class DomstorFlatFloorField extends DomstorCommonField
{
	public function getValue()
	{
		$a = $this->getRow();
        $out = '';
		if( $a['object_floor'] or $a['building_floor'] )
		{
			$object=$a['object_floor']? $a['object_floor'] : '-';
			$building=$a['building_floor']? $a['building_floor'] : '-';
			$out=$object.'/'.$building;
		}
		return $out;
	}
}

class DomstorFlatSquareField extends DomstorCommonField
{
	public function getValue()
	{
		$a = $this->getRow();
        $out = '';
		if( $a['square_house'] or $a['square_living'] or $a['square_kitchen'] )
		{
			$house = $a['square_house']? $a['square_house'] : '-';
			$living = $a['square_living']? $a['square_living'] : '-';
			$kitchen = $a['square_kitchen']? $a['square_kitchen'] : '-';
			$out = $house.'/'.$living.'/'.$kitchen;
			$out = str_replace('.', ',', $out);
		}
		return $out;
	}
}

class DomstorFlatBalconyField extends DomstorCommonField
{
	public function getValue()
	{
		$a = $this->getRow();
        $out = $space = '';
		if( !empty($a['balcony_count']) )
		{

			$out.='Балкон';
			if( $a['balcony_count']>1 )
			{
				$out.=' ('.$a['balcony_count'].')';
			}
			$space=', ';
		}

		if( !empty($a['loggia_count']) )
		{
			$out.=$space.'Лоджия';
			if( $a['loggia_count']>1 )
			{
				$out.=' ('.$a['loggia_count'].')';
			}
			$space=', ';
		}

		if( !empty($a['balcony_arrangement']) )
		{
			$out.=$space.$a['balcony_arrangement'];
		}
		return $out;
	}
}

class DomstorFlatDemandRoomsField extends DomstorCommonField
{
	public function getValue()
	{
		$a=$this->getRow();
		$rooms=array();
		for($room=1; $room<6; $room++)
		{
			if( $a['room_count_'.$room] ) $rooms[]=$room;
		}
		$out=implode(', ', $rooms);
		return $out;
	}
}

class DomstorFlatDemandFloorField extends DomstorCommonField
{
	public function getValue()
	{
		$a=$this->getRow();
		$floor=array();
		if( $a['object_floor'] ) $floor[]='Не выше '.$a['object_floor'].' этажа';
		if( $a['object_floor_limit'] ) $floor[]=$a['object_floor_limit'];
		$out=implode(', ', $floor);
		return $out;
	}
}

//LAND-FIELDS**********************************************

class DomstorLandAddressField extends DomstorCommonField
{
	protected $object_href;
	public function getValue()
	{
		$a=$this->getRow();
		if( $a['address_note'] ) $out.=$a['address_note'].', ';
		if( $a['cooperative_name'] ) $out.=$a['cooperative_name'].', ';
		if( $a['city'] ) $out.=$a['city'].', ';
		$out=substr($out, 0, -2);
		if( $out )
		{
			$href=str_replace('%id', $a['id'], $this->object_href);
			$out='<a href="'.$href.'" title="Перейти на страницу объекта '.$a['code'].'" class="domstor_link">'.$out.'</a>';
		}
		return $out;
	}
}

//COMMERCE-FIELDS**********************************************
class DomstorCommercePurposeField extends DomstorCommonField
{
	public function getValue()
	{
		$a = $this->getRow();
        $out = '';
		if( $purp = $a['Purposes'] )
		{
			if( isset($purp[1001]) and $purp[1001] )
			{
				unset($purp[1002], $purp[1003]);
			}
			if( isset($purp[1004]) and $purp[1004] )
			{
				unset($purp[1005], $purp[1006]);
			}
			if( isset($purp[1009]) and $purp[1009] )
			{
				for($i=1013; $i<1022; $i++)
				{
					unset($purp[$i]);
				}
			}
			$out=implode(', ', $purp);
		}
		return $out;
	}
}

class DomstorCommerceFloorField extends DomstorCommonField
{
	public function getValue()
	{
		$a = $this->getRow();
		$min = $a['object_floor_min'];
		$max = $a['object_floor_max'];
		$min_flag = FALSE;
		$max_flag = FALSE;
		$out = '';

		if( isset($min) and $min != '' ) $min_flag = TRUE;
		if( isset($max) and $max != '' ) $max_flag = TRUE;

		if( $min_flag and $max_flag )
		{
			if( $min == $max )
			{
				$out = ($min == '0')? 'цоколь' : $min;
			}
			else
			{
				$out = 'от&nbsp;'.$min.' до&nbsp;'.$max;
				$out = str_replace('0', 'цоколя', $out);
			}
		}
		elseif( $min_flag or $max_flag )
		{
			if( $min_flag )
			{
				$out = 'от&nbsp;'.$min;
			}
			else
			{
				$out = 'до&nbsp;'.$max;
			}
			$out = str_replace('0', 'цоколя', $out);
		}

		return $out;
	}
}

class DomstorCommerceSquareField extends DomstorCommonField
{
	public function getValue()
	{
		$a=$this->getRow();

		$out=$this->getFromTo(str_replace('.', ',', $a['square_house_min']), str_replace('.', ',', $a['square_house_max']), ' кв.м', '', true);
		return $out;
	}
}

class DomstorCommerceSquareGroundField extends DomstorCommonField
{
	public function getValue()
	{
		$a=$this->getRow();
		$a['square_ground_min'] = $a['square_ground_min']? str_replace('.', ',', $a['square_ground_min']) : NULL;
		$a['square_ground_max'] = $a['square_ground_max']? str_replace('.', ',', $a['square_ground_max']) : NULL;
		if( $a['square_ground_unit_id']==1177 ) $a['square_ground_unit']='кв.м';
		elseif( $a['square_ground_unit'] == 'Гектар' ) $a['square_ground_unit']='Га';
		$out=$this->getFromTo($a['square_ground_min'], $a['square_ground_max'], ' '.$a['square_ground_unit'], '', true);
		return $out;
	}
}

class DomstorCommercePriceField extends DomstorCommonField
{
	protected $action;

	public function __construct($attr)
	{
		parent::__construct($attr);
		if( $this->action=='rent' )
		{
			$this->title='Арендная ставка';
		}
		else
		{
			$this->title='Цена';
		}
	}

	public function getValue()
	{
		$a = $this->table->getRow();
        $out = '';
		$price_ground_unit = (isset($a['price_m2_unit']) and $a['price_m2_unit'])? $a['price_m2_unit'] : 'кв.м';

		$a['rent_m2_min'] = (float) $a['rent_m2_min'];
		$a['rent_m2_max'] = (float) $a['rent_m2_max'];
		$a['rent_full'] = (float) $a['rent_full'];

		$a['price_m2_min'] = (float) $a['price_m2_min'];
		$a['price_m2_max'] = (float) $a['price_m2_max'];
		$a['price_full'] = (float) $a['price_full'];

		if( $this->action == 'rent' )
		{
			if( $a['offer_parts'] and ($a['rent_m2_min'] or $a['rent_m2_max']) )
			{
				$out=$this->getIf($this->getPriceFromTo($a['rent_m2_min'], $a['rent_m2_max'], $a['rent_currency']), '', '/ '.$price_ground_unit.' '.$a['rent_period'] );
			}
			elseif( $a['rent_full'] )
			{
				$out=number_format($a['rent_full'], 0, ',', ' ');
				$out.=$this->getIf($a['rent_currency'], ' ');
				$out.=$this->getIf($a['rent_period'], ' ');
				$out=str_replace(' ', '&nbsp;', $out);
			}
		}
		else
		{
			if( $a['offer_parts'] and ($a['price_m2_min'] or $a['price_m2_max']) )
			{
				$out=$this->getIf($this->getPriceFromTo($a['price_m2_min'], $a['price_m2_max'], $a['price_currency']), '', '/ '.$price_ground_unit );
			}
			elseif( $a['price_full'] )
			{
				$out=number_format($a['price_full'], 0, ',', ' ');
				$out.=$this->getIf($a['price_currency'], ' ');
				$out=str_replace(' ', '&nbsp;', $out);
			}
		}

		return $out;
	}
}

class DomstorCommerceDemandPriceField extends DomstorCommonField
{
	protected $action;

	public function getFormatedPrice()
	{
		$a = $this->table->getRow();
        $out = '';
		if( $a['price_full'] )
		{
			$out=number_format($a['price_full'], 0, ',', ' ');
			$out.=$this->getIf($a['price_currency'], ' ');
			$out=str_replace(' ', '&nbsp;', $out);
		}
		return $out;
	}

	public function getFormatedPriceM2()
	{
		$a=$this->table->getRow();
		if( $a['price_m2'] )
		{
			$out=number_format($a['price_m2'], 0, ',', ' ');
			$out.=$this->getIf($a['price_currency'], ' ');
			$unit = $a['price_m2_unit']=='кв.метров'? 'кв.м':$a['price_m2_unit'];
			$out.=$this->getIf($unit, ' за ');
			$out=str_replace(' ', '&nbsp;', $out);
		}
		return $out;
	}

	public function getFormatedRent()
	{
		$a = &$this->object;
        $out = '';
		if( $a['rent_full'] )
		{
			$out=number_format($a['rent_full'], 0, ',', ' ');
			$out.=$this->getIf($a['rent_currency'], ' ');
			if( $a['rent_period'] ) $out.=' '.$a['rent_period'];
			$out=str_replace(' ', '&nbsp;', $out);
		}
		return $out;
	}

	public function getFormatedRentM2()
	{
		$a=$this->table->getRow();
		if( $a['rent_m2'] )
		{
			$out=number_format($a['rent_m2'], 0, ',', ' ');
			$out.=$this->getIf($a['rent_currency'], ' ');
			$unit = $a['rent_m2_unit']=='кв.метров'? 'кв.м':$a['rent_m2_unit'];
			$out.=$this->getIf($unit, ' за ');
			if( $a['rent_period'] ) $out.=' '.$a['rent_period'];
			$out=str_replace(' ', '&nbsp;', $out);
		}
		return $out;
	}

	public function __construct($attr)
	{
		parent::__construct($attr);
		if( $this->action=='rent' )
		{
			$this->title='Бюджет';
		}
		else
		{
			$this->title='Бюджет';
		}
	}

	public function getValue()
	{
		$a = $this->table->getRow();
        $out = '';
		if( $this->action=='rentuse' )
		{
			$rent=$this->getIf($this->getFormatedRent());
			$rent_m2=$this->getIf($this->getFormatedRentM2());
			if( $rent and $rent_m2 ) $rent_m2=' ('.$rent_m2.')';
			$out=$rent.$rent_m2;
		}
		else
		{
			$price=$this->getIf($this->getFormatedPrice());
			$price_m2=$this->getIf($this->getFormatedPriceM2());
			if( $price and $price_m2 ) $price_m2=' ('.$price_m2.')';
			$out=$price.$price_m2;
		}

		return $out;
	}
}

class DomstorCommerceAddressField extends DomstorCommonField
{
	protected $in_region;
	protected $object_href;

	public function getValue()
	{
		$a = $this->getTable()->getRow();
		$out = '';
		if( $this->in_region or $a['district'] == 'Пригород' or $a['district_parent'] == 'Пригород')
		{
			if( $this->in_region ) $out.= $this->getIf($a['city'], '', ', ');
			$out.= $this->getIf($a['district'], '', ', ');
			$out.= $this->getIf($a['address_note'], '', ', ');
			$out = substr($out, 0, -2);
		}
		else
		{
			if( $a['street'] and $a['street_id'] )
			{
				$out = $a['street'];
				if( $a['building_num'] )
				{
					$out.= ', '.$a['building_num'];
					if( $a['corpus'] )
					{
						if( is_numeric($a['corpus']) )
						{
							$out.= '/'.$a['corpus'];
						}
						else
						{
							$out.= strtoupper($a['corpus']);
						}
					}
				}
				$s = ', ';
			}
			else
			{
				$out = $a['address_note'];
			}

			$s = $out? ', ' : '';

			if( $a['district'] ) $out.= $s.$a['district'];

			if( $out and $a['city'] and $this->getTable()->cityId() and $a['city_id'] != $this->getTable()->cityId() )
			{
				$out = $a['city'].', '.$out;
			}

			if( !$out )
			{
				$space = (isset($a['address_note']) and $a['address_note'])? ', ' : '';
				$region_city = $a['city']? $a['city'] : $a['Region']['Names']['im'];

				if( $a['district'] )
				{
					$out = $a['address_note'];
				}
				else
				{
					$out = $region_city.$space.$a['address_note'];
				}
			}

			if( $a['district'] )
			{

				if( !$out and isset($a['address_note']) and $a['address_note'] ) $out = $region_city.$space.$a['address_note'];
			}
			else
			{
				$region_city = $a['city']? $a['city'] : $a['Region']['Names']['im'];
				if( !$out ) $out = $region_city.$space.$a['address_note'];
			}
		}

		if( $out )
		{
			$href=str_replace('%id', $a['id'], $this->object_href);
			$out='<a href="'.$href.'" title="Перейти на страницу объекта '.$a['code'].'" class="domstor_link">'.$out.'</a>';
		}

		return $out;
	}

	/* public function getValue()
	{
		$a = $this->getTable()->getRow();
		$out = '';

		if( $this->in_region or $a['district']=='Пригород' or $a['district_parent']=='Пригород')
		{
			if( $this->in_region )
			{
				$out.= $this->getIf($a['city'], '', ', ');
				$out.= $this->getIf($a['district'], '', ', ');
			}
			$out.= $this->getIf($a['address_note'], '', ', ');
			$out = substr($out, 0, -2);
		}
		else
		{
			$before_district = '';
			if( $a['street'] and $a['street_id'] )
			{
				$out = $a['street'];
				if( $a['building_num'] )
				{
					$out.= ', '.$a['building_num'];
					if( $a['corpus'] )
					{
						if( is_numeric($a['corpus']) )
						{
							$out.= '/'.$a['corpus'];
						}
						else
						{
							$out.= strtoupper($a['corpus']);
						}
					}
				}
				$before_district = ', ';
			}

			if( $a['district'] )
			{
				if( substr($a['district'], -1)=='й' )
				{
					$out.= $before_district.$a['district'].'&nbsp;р-н';
				}
				else
				{
					$out.= $before_district.'р-н&nbsp;'.$a['district'];
				}
			}

			$before_note = $out? ', ' : '';
			if( isset($a['address_note']) and $a['address_note'] ) $out.= $before_note.$a['address_note'];
		}

		if( $out )
		{
			$href=str_replace('%id', $a['id'], $this->object_href);
			$out='<a href="'.$href.'" title="Перейти на страницу объекта '.$a['code'].'" class="domstor_link">'.$out.'</a>';
		}

		return $out;
	}
 */
 }

class DomstorCommerceDemandAddressField extends DomstorCommonField
{
	protected $in_region;
	protected $object_href;

	public function getValue()
	{
		$a=$this->getTable()->getRow();
		if( $this->in_region )
		{
			$out.=$this->getIf($a['address_note'], '', ', ');
			$out.=$this->getIf($a['city'], '', ', ');
			$out=substr($out, 0, -2);
		}
		else
		{
			if( isset($a['district']) and $a['district'] ) $out='Районы: '.$a['district'].'; ';
			if( isset($a['street']) and $a['street'] ) $out.='Улицы: '.$a['street'].'; ';
			$out=substr($out, 0, -2);
		}

		if( $out )
		{
			$href=str_replace('%id', $a['id'], $this->object_href);
			$out='<a href="'.$href.'" title="Перейти на страницу заявки '.$a['code'].'" class="domstor_link">'.$out.'</a>';
		}
		return $out;
	}
}

//TABLES**********************************************

class DomstorCommonTable extends HtmlTable
{
	protected $in_region;
	protected $object;
	protected $action;
	protected $pagination;
	protected $object_href;
	protected $filter;
	protected $_show_filter = FALSE;
	protected $empty_list_message = 'Список пуст';
	protected $city_id;

	public function cityId($val = NULL)
	{
		if( is_null($val) ) return $this->city_id;

		$this->city_id = $val;
		return $this;
	}

    public function getObjectHref()
    {
        return $this->object_href;
    }

	public function inRegion($val)
	{
		$this->in_region=(bool)$val;
	}

	public function isInRegion()
	{
		return $this->in_region;
	}

	public function getServerName()
	{
		return $this->server_name;
	}

	public function setPagination($value)
	{
		$this->pagination=$value;
	}

	public function getPagination()
	{
		return $this->pagination;
	}

	public function setPager($pager)
	{
		$this->pager = $pager;
	}

	public function getPager()
	{
		return $this->pager;
	}

	public function setFilter($value)
	{
		$this->filter=$value;
	}

	public function getFilter()
	{
		return $this->filter;
	}

	public function showFilter($val)
	{
		$this->_show_filter = (bool) $val;
		return $this;
	}

	public function getEmptyListMessage()
	{
		return $this->empty_list_message;
	}

	public function display()
    {
        echo $this->getHtml();
    }

    public function getHtml()
	{
		$out = '';
		if( $this->_show_filter ) $out.= (string) $this->getFilter();
		if( count($this->data) )
		{
			$out.= parent::getHtml();
			$out.= $this->getPagination();
		}
		else
		{
			$out.= $this->empty_list_message;
		}
		return $out;
	}

	public function __construct($attr)
	{
		parent::__construct($attr);
		//var_dump($this->data);
		$this->css_class='domstor_table';

		$code_field = new DomstorCodeField( array(
				'name'=>'code',
				'title'=>'Код',
				'css_class'=>'domstor_code',
				'position'=>0,
				'object_href'=>$this->object_href,
				'sort_name'=>'sort-code',
		));

		$type_field = new HtmlTableField( array(
			'name'=>'type',
			'title'=>'Тип',
			'css_class'=>'domstor_type',
			'position'=>100,
			'sort_name'=>'sort-type',
		) );

		$district_field = new HtmlTableField( array(
				'name'=>'district',
				'title'=>'Район',
				'css_class'=>'domstor_district',
				'position'=>200,
				'sort_name'=>'sort-district',
		));


		$contact_field = new DomstorContactField( array(
				'name'=>'contact_phone',
				'title'=>'Контактный телефон',
				'css_class'=>'domstor_contact',
				'position'=>'last',
				'position'=>300,
		));

		$note_web_field = new DomstorCommentField( array(
				'name'=>'note_web',
				'title'=>'Комментарий',
				'css_class'=>'domstor_note_web',
				'position'=>'last',
				'position'=>400,
		));

		$this->addField($code_field)
			 ->addField($type_field)
			 ->addField($district_field)
			 ->addField($contact_field)
			 ->addField($note_web_field)
		;
	}
}

//COMMON-TABLES**********************************************

class DomstorObjectTable extends DomstorCommonTable
{
	public function checkThumb()
	{
		foreach( $this->data as $a )
		{
			if( isset($a['thumb']) ) return TRUE;
		}
		return FALSE;
	}

	public function __construct($attr)
	{
		parent::__construct($attr);

		$thumb_field = new DomstorThumbField( array(
				'name'=>'thumb',
				'title'=>'Фото',
				'css_class'=>'domstor_thumb',
				'position'=>25,
				'object_href'=>$this->object_href,
		));

		$price_field = new DomstorPriceField( array(
				'name'=>'price',
				'css_class'=>'domstor_price',
				'action'=>$this->action,
				'sort_name'=>'sort-price',
				'position'=>260,
		));

		$address_field = new DomstorAddressField( array(
				'name'=>'address',
				'title'=>'Адрес',
				'css_class'=>'domstor_address',
				'in_region'=>$this->in_region,
				'object_href'=>$this->object_href,
				'position'=>230,
				'sort_name'=>'sort-street',
		));

		$this->addField($price_field)
			 ->addField($address_field)
		;
		if( $this->checkThumb() ) $this->addField($thumb_field);
		if( $this->action=='rent' )$this->getField('price')->setSortName('sort-rent');
	}
}

class DomstorDemandTable extends DomstorCommonTable
{
	public function __construct($attr)
	{
		parent::__construct($attr);
		$price_field = new DomstorDemandPriceField( array(
				'name'=>'price',
				'css_class'=>'domstor_price',
				'title'=>'Бюджет',
				'action'=>$this->action,
				'sort_name'=>'sort-price',
				'position'=>260,
		));

		$this->addField($price_field)
		;
		if( $this->action=='rent' )$this->getField('price')->setSortName('sort-rent');
	}
}

//FLAT-TABLES**********************************************

class FlatSaleList extends DomstorObjectTable
{
	public function __construct($attr)
	{
		parent::__construct($attr);
		$this->getField('type')->setName('flat_type');
		$room_count_field = new HtmlTableField( array(
			'name'=>'room_count',
			'title'=>'Число комнат',
			'css_class'=>'domstor_room_count',
			'dont_show_if'=>'0',
			'position'=>50,
			'sort_name'=>'sort-rooms',
		) );

		$building_material_field = new HtmlTableField( array(
			'name'=>'building_material',
			'title'=>'Материал строения',
			'css_class'=>'domstor_building_material',
			'position'=>101,
		) );

		$floor_field = new DomstorFlatFloorField( array(
			'name'=>'floor',
			'title'=>'Этаж',
			'css_class'=>'domstor_floor',
			'position'=>232,
			'sort_name'=>'sort-floor',
		) );

		$square_field = new DomstorFlatSquareField( array(
			'name'=>'square',
			'title'=>'Площадь, кв.м<br />общ./жил./кух.',
			'css_class'=>'domstor_square',
			'sort_name'=>'sort-square',
			'position'=>234,
		) );

		$phone_field = new HtmlYesNoTableField( array(
			'name'=>'phone',
			'title'=>'Телефон',
			'css_class'=>'domstor_phone',
			'yes'=>'Тел.',
			'position'=>236,
		) );

		$balcony_field = new DomstorFlatBalconyField( array(
			'name'=>'balcony',
			'title'=>'Балкон, лоджия',
			'css_class'=>'domstor_balcony',
			'position'=>238,
		) );

		$this->addField($room_count_field)
			 ->addField($building_material_field)
			 ->addField($floor_field)
			 ->addField($square_field)
			 ->addField($phone_field)
			 ->addField($balcony_field)
		;

		if( $this->in_region )
		{
			$this->deleteField('district');
			$this->getField('address')->setTitle('Местоположение');
		}
	}
}

class FlatRentList extends FlatSaleList
{

}

class FlatNewList extends FlatSaleList
{

}

class FlatExchangeList extends FlatSaleList
{

}

class FlatPurchaseList extends DomstorDemandTable
{
	public function __construct($attr)
	{
		parent::__construct($attr);

		$room_count_field = new DomstorFlatDemandRoomsField( array(
				'name'=>'room_count',
				'title'=>'Число комнат',
				'css_class'=>'domstor_room_count',
				'sort_name'=>'sort-rooms',
				'position'=>1,
		));

		$floor_field = new DomstorFlatDemandFloorField( array(
				'name'=>'floor',
				'title'=>'Этаж',
				'css_class'=>'domstor_floor',
				'position'=>201,
		));

		$building_material_field = new HtmlTableField( array(
			'name'=>'building_material',
			'title'=>'Материал строения',
			'css_class'=>'domstor_building_material',
			'position'=>101,
		) );

		$phone_field = new HtmlTableField( array(
			'name'=>'phone_want',
			'title'=>'Телефон',
			'css_class'=>'domstor_phone',
			'position'=>236,
		) );

		$this->addField($room_count_field)
			 ->addField($floor_field)
			 ->addField($building_material_field)
			 ->addField($phone_field)
			 ->getField('type')->removeSortName();
		;
	}
}

class FlatRentuseList extends FlatPurchaseList
{

}

//HOUSE-TABLES**********************************************

class HouseSaleList extends DomstorObjectTable
{
	public function __construct($attr)
	{
		parent::__construct($attr);

		$this->getField('address')->setTitle('Местоположение');

		$room_count_field = new HtmlTableField( array(
			'name'=>'room_count',
			'title'=>'Число комнат',
			'css_class'=>'domstor_room_count',
			'dont_show_if'=>'0',
			'position'=>101,
			'sort_name'=>'sort-rooms',
		) );

		$floor_field = new HtmlTableField( array(
			'name'=>'building_floor',
			'title'=>'Этажей',
			'css_class'=>'domstor_floor',
			'dont_show_if'=>'0',
			'position'=>102,
			'sort_name'=>'sort-floor',
		) );

		$square_field = new DomstorFlatSquareField( array(
			'name'=>'square',
			'title'=>'Площадь, кв.м<br />общ./жил./кух.',
			'css_class'=>'domstor_square',
			'sort_name'=>'sort-square',
			'position'=>234,
		) );

		$square_ground_field = new DomstorSquareGroundField( array(
			'name'=>'square_round',
			'title'=>'Площадь участка',
			'css_class'=>'domstor_square_round',
			'sort_name'=>'sort-ground',
			'position'=>236,
		) );

		$other_building_field = new HtmlTableField( array(
			'name'=>'other_building',
			'title'=>'Постройки',
			'css_class'=>'domstor_other_building',
			'position'=>238,
		) );

		$this->addField($room_count_field)
			 ->addField($floor_field)
			 ->addField($square_field)
			 ->addField($square_ground_field)
			 ->addField($other_building_field)
		;

		if( $this->in_region )
		{
			$this->deleteField('district');
			$this->getField('address')->setTitle('Местоположение');
		}
	}
}

class HouseRentList extends HouseSaleList
{
}

class HouseExchangeList extends HouseSaleList
{
}

class HousePurchaseList extends DomstorDemandTable
{
	public function __construct($attr)
	{
		parent::__construct($attr);

		$room_count_field = new HtmlMinMaxTableField(array(
			'name'=>'room_count',
			'title'=>'Число комнат',
			'min'=>'room_count_min',
			'max'=>'room_count_max',
			'dont_show_if'=>'0',
			'position'=>101,
		));

		$square_field = new HtmlMinMaxTableField(array(
			'name'=>'square_house',
			'title'=>'Площадь дома',
			'min'=>'square_house_min',
			'max'=>'square_house_max',
			'dont_show_if'=>'0',
			'adds'=>' кв.м.',
			'position'=>234,
		));

		$square_ground_field = new DomstorSquareGroundDeamandField( array(
			'name'=>'square_round',
			'title'=>'Площадь участка',
			'css_class'=>'domstor_square_round',
			'position'=>236,
		) );

		$other_building_field = new HtmlTableField( array(
			'name'=>'other_building',
			'title'=>'Постройки',
			'css_class'=>'domstor_other_building',
			'position'=>238,
		) );

		$this->addField($room_count_field)
			 //->addField($floor_field)
			 ->addField($square_field)
			 ->addField($square_ground_field)
			 //->addField($other_building_field)
		;
	}
}

class HouseRentuseList extends HousePurchaseList
{
}

//GARAGE-TABLES**********************************************

class GarageSaleList extends DomstorObjectTable
{
	public function __construct($attr)
	{
		parent::__construct($attr);

		$this->getField('address')->setTitle('Местоположение');
		$this->getField('type')->setTitle('Вид гаража');

		$placing_type_field = new HtmlTableField( array(
			'name'=>'placing_type',
			'title'=>'Вид размещения',
			'css_class'=>'domstor_placing_type',
			'position'=>101
		) );

		$material_wall_field = new HtmlTableField( array(
			'name'=>'material_wall',
			'title'=>'Материал стен',
			'css_class'=>'domstor_material_wall',
			'sort_name'=>'sort-wall',
			'position'=>102
		) );

		$size_field = new HtmlDelimitedTableField( array(
			'name'=>'size',
			'title'=>'Размеры,&nbsp;м<br />Ш&nbsp;х&nbsp;Д&nbsp;х&nbsp;В',
			'css_class'=>'domstor_size',
			'params' => array('size_x','size_y', 'size_z'),
			'delimiter' => '&nbsp;x&nbsp;',
			'dont_show_if' => '0',
			'position'=>232
		) );

		$cellar_field = new HtmlTableField( array(
			'name'=>'cellar',
			'title'=>'Погреб',
			'css_class'=>'domstor_cellar',
			'position'=>234
		) );

		$this->addField($placing_type_field)
			 ->addField($material_wall_field)
			 ->addField($size_field)
			 ->addField($cellar_field)
		;

		if( $this->in_region )
		{
			$this->deleteField('district');
		}
	}
}

class GarageRentList extends GarageSaleList
{

}

class GaragePurchaseList extends DomstorDemandTable
{
	public function __construct($attr)
	{
		parent::__construct($attr);

		$this->getField('type')->setTitle('Вид гаража');

		$cellar_field = new HtmlTableField( array(
			'name'=>'cellar_want',
			'title'=>'Наличие погреба',
			'css_class'=>'domstor_cellar',
			'position'=>234
		) );

		$this->addField($cellar_field)
		;

	}
}

class GarageRentuseList extends GaragePurchaseList
{
}

//LAND-TABLES**********************************************

class LandSaleList extends DomstorObjectTable
{
	public function __construct($attr)
	{
		parent::__construct($attr);

		$this->getField('type')->setTitle('Тип участка');

		$square_field = new DomstorSquareGroundField( array(
			'name'=>'square_ground',
			'title'=>'Площадь',
			'css_class'=>'domstor_square_ground',
			'position'=>101,
			'sort_name'=>'sort-square',
		) );

		$address_field = new DomstorAddressField( array(
			'name'=>'address',
			'title'=>'Местоположение',
			'css_class'=>'domstor_address',
			'position'=>230,
			'object_href'=>$this->object_href,
			'in_region'=>$this->in_region,
		) );


		$living_building_field = new HtmlTableField( array(
			'name'=>'living_building',
			'title'=>'Жилые постройки',
			'css_class'=>'living_building_type',
			'position'=>232,
		) );

		$square_house_field = new HtmlTableField( array(
			'name'=>'square_house',
			'title'=>'Площадь жилой постройки',
			'dont_show_if'=>'0',
			'adds'=>' кв.м',
			'css_class'=>'square_house_type',
			'position'=>234,
		) );

		$other_building_field = new HtmlYesNoTableField( array(
			'name'=>'other_building',
			'title'=>'Прочие постройки',
			'yes'=>'Есть',
			'css_class'=>'other_building_type',
			'position'=>236,
		) );

		$this->addField($square_field)
			 ->addField($address_field)
			 ->addField($living_building_field)
			 ->addField($square_house_field)
			 ->addField($other_building_field)
		;

		if( $this->in_region )
		{
			$this->deleteField('district');
		}
	}
}

class LandRentList extends LandSaleList
{

}

class LandPurchaseList extends DomstorDemandTable
{
	public function __construct($attr)
	{
		parent::__construct($attr);
		$this->getField('type')->setTitle('Тип участка');

		$square_field = new DomstorSquareGroundDeamandField( array(
			'name'=>'square_ground',
			'title'=>'Площадь',
			'css_class'=>'domstor_square_ground',
			'position'=>101,
		) );

		$address_field = new DomstorAddressDemandField( array(
			'name'=>'address',
			'title'=>'Местоположение',
			'css_class'=>'domstor_address',
			'position'=>230,
			'object_href'=>$this->object_href,
		) );

		$living_building_field = new HtmlTableField( array(
			'name'=>'living_building',
			'title'=>'Жилые постройки',
			'css_class'=>'living_building_type',
			'position'=>232,
		) );

		$this->addField($square_field)
			 ->addField($address_field)
			 ->addField($living_building_field)
		;

	}
}

class LandRentuseList extends LandPurchaseList
{
}

//COMMERCE-TABLES**********************************************

class CommerceSaleList extends DomstorObjectTable
{
	protected $show_square_house=false;
	protected $show_square_ground=false;

	public function checkSquare()
	{
		if( !is_array($this->data) ) return FALSE;
		foreach( $this->data as $a )
		{
			if( isset($a['Purposes'][1009]) and $a['Purposes'][1009] )
			{

				if( count($a['Purposes'])==1 )
				{
					$this->show_square_ground=true;
				}
				else
				{
					$this->show_square_ground=true;
					$this->show_square_house=true;
					return;
				}
			}
			else
			{
				$this->show_square_house=true;
			}
		}
	}

	public function __construct($attr)
	{
		parent::__construct($attr);

		$this->getField('address')->setTitle('Местоположение');
		$this->deleteField('district');
		$type_field = new DomstorCommercePurposeField( array(
			'name'=>'type',
			'title'=>'Назначение',
			'css_class'=>'domstor_type',
			'sort_name'=>'sort-purpose',
			'position'=>100,
		) );

		$address_field = new DomstorCommerceAddressField( array(
			'name'=>'address',
			'title'=>'Местоположение',
			'css_class'=>'domstor_address',
			'in_region'=>$this->in_region,
			'object_href'=>$this->object_href,
			'sort_name'=>'sort-location',
			'position'=>230,
		));

		$floor_field = new DomstorCommerceFloorField( array(
			'name'=>'floor',
			'title'=>'Этаж',
			'css_class'=>'domstor_floor',
			'position'=>231,
		) );

		$square_field = new DomstorCommerceSquareField( array(
			'name'=>'square_house',
			'title'=>'Площадь',
			'css_class'=>'domstor_square_house',
			'sort_name'=>'sort-square',
			'position'=>232,
		) );

		$square_ground_field = new DomstorCommerceSquareGroundField( array(
			'name'=>'square_ground',
			'title'=>'Площадь земельного участка',
			'css_class'=>'domstor_square_ground',
			'sort_name'=>'sort-groundsq',
			'position'=>233,
		) );

		$price_field = new DomstorCommercePriceField( array(
			'name'=>'price',
			'css_class'=>'domstor_price',
			'action'=>$this->action,
			'sort_name'=>'sort-price',
			'position'=>260,

		) );

		$this->checkSquare();
		$this->addField($type_field)
			 ->addField($floor_field)
			 ->addField($price_field)
			 ->addField($address_field)
		;
		if( $this->show_square_house ) $this->addField($square_field);
		if( $this->show_square_ground ) $this->addField($square_ground_field);
		if( $this->in_region )
		{
			$this->deleteField('district');
		}
		if( $this->action=='rent' )$this->getField('price')->setSortName('sort-rent');
	}
}

class CommerceRentList extends CommerceSaleList
{

}

class CommercePurchaseList extends DomstorDemandTable
{
	protected $show_square_house=false;
	protected $show_square_ground=false;

	public function checkSquare()
	{
		foreach( $this->data as $a )
		{
			if( isset($a['Purposes'][1009]) and $a['Purposes'][1009] )
			{
				if( count($a['Purposes'])==1 )
				{
					$this->show_square_ground=true;
				}
				else
				{
					$this->show_square_ground=true;
					$this->show_square_house=true;
					return;
				}
			}
			else
			{
				$this->show_square_house=true;
			}
		}
	}

	public function __construct($attr)
	{
		parent::__construct($attr);
		$this->deleteField('district');

		$type_field = new DomstorCommercePurposeField( array(
			'name'=>'type',
			'title'=>'Назначение',
			'css_class'=>'domstor_type',
			'sort_name'=>'sort-purpose',
			'position'=>100,
		) );

		$address_field = new DomstorCommerceDemandAddressField( array(
				'name'=>'address',
				'title'=>'Местоположение',
				'css_class'=>'domstor_address',
				'in_region'=>$this->in_region,
				'object_href'=>$this->object_href,
				'position'=>230,
		));


		$square_field = new DomstorCommerceSquareField( array(
			'name'=>'square_house',
			'title'=>'Площадь',
			'css_class'=>'domstor_square_house',
			'sort_name'=>'sort-square',
			'position'=>232,
		) );

		$square_ground_field = new DomstorCommerceSquareGroundField( array(
			'name'=>'square_ground',
			'title'=>'Площадь земельного участка',
			'css_class'=>'domstor_square_ground',
			'sort_name'=>'sort-groundsq',
			'position'=>233,
		) );

		$price_field = new DomstorCommerceDemandPriceField( array(
			'name'=>'price',
			'css_class'=>'domstor_price',
			'sort_name'=>'sort-price',
			'position'=>260,
			'action'=>$this->action,
		) );

		$this->checkSquare();
		$this->addField($type_field)
			 ->addField($price_field)
			 ->addField($address_field)
		;
		if( $this->show_square_house ) $this->addField($square_field);
		if( $this->show_square_ground ) $this->addField($square_ground_field);
	}
}

class CommerceRentuseList extends CommercePurchaseList
{
}

//TRADE-TABLES**********************************************

class TradeSaleList extends CommerceSaleList
{

}

class TradeRentList extends CommerceRentList
{

}

class TradePurchaseList extends CommercePurchaseList
{
}

class TradeRentuseList extends CommerceRentuseList
{
}

//LANDCOM-TABLES**********************************************

class LandcomSaleList extends CommerceSaleList
{

}

class LandcomRentList extends CommerceRentList
{
}

class LandcomPurchaseList extends CommercePurchaseList
{
}

class LandcomRentuseList extends CommerceRentuseList
{
}

//OFFICE-TABLES**********************************************

class OfficeSaleList extends CommerceSaleList
{
}

class OfficeRentList extends CommerceRentList
{
}

class OfficeRentuseList extends CommerceRentuseList
{
}

class OfficePurchaseList extends CommercePurchaseList
{
}

//PRODUCT-TABLES**********************************************

class ProductSaleList extends CommerceSaleList
{
}

class ProductRentList extends CommerceRentList
{
}

class ProductRentuseList extends CommerceRentuseList
{
}

class ProductPurchaseList extends CommercePurchaseList
{
}

//STOREHOUSE-TABLES**********************************************

class StorehouseSaleList extends TradeSaleList
{
}

class StorehouseRentList extends TradeRentList
{
}

class StorehouseRentuseList extends TradeRentuseList
{
}

class StorehousePurchaseList extends TradePurchaseList
{
}

//COMPLEX-TABLES**********************************************

class ComplexSaleList extends TradeSaleList
{
}

class ComplexRentList extends TradeRentList
{
}

//OTHER-TABLES**********************************************

class OtherSaleList extends TradeSaleList
{
}

class OtherRentList extends TradeRentList
{
}

class OtherRentuseList extends TradeRentuseList
{
}

class OtherPurchaseList extends TradePurchaseList
{
}