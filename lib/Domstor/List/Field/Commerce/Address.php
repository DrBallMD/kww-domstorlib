<?php

/**
 * Description of Address
 *
 * @author pahhan
 */
class Domstor_List_Field_Commerce_Address extends Domstor_List_Field_Common
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
 }