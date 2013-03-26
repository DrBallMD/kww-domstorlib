<?php

/**
 * Description of Helper
 *
 * @author pahhan
 */
class Ds_Detail_Helper
{
    public static function getCity(Ds_Detail_DetailData $data)
	{
		$name = $data->name;
		$socr = $data->socr;

		if( in_array($socr, array('г', 'п')) ) $socr.= '.';
		if( substr($name, -1) == 'й' )
		{
			return $name.' '.$socr;
		}
		else
		{
			return $socr.' '.$name;
		}
	}

	public static function getMetroDemand(array $metros)
	{
		$out = array();

		foreach( $metros as $metro )
		{
			$out[] = $metro['name'];
		}

		if( empty($out) ) return '';

		return implode(', ', $out);
	}

	public static function getBuilding(Ds_Detail_DetailData $data)
	{
        $out = '';
        if( $out = $data->building_num )
		{
			if( $corpus = $data->corpus )
			{
				$s = is_numeric($corpus)? '/' : '';
				$out.= $s.$corpus;
			}
		}
		return $out;
	}

	public function getLocation(Ds_Detail_DetailData $data, array $options = array())
	{
		$in_region = isset($options['in_region'])? (bool) $options['in_region'] : false;
		$add_street_abbr = isset($options['add_street_abbr'])? (bool) $options['add_street_abbr'] : true;
		$distr = @$data['District']['name'];
		$distr_parent = @$data['District']['ParentAddress']['name'];

		if( $distr == 'Пригород' or $distr_parent == 'Пригород' )
		{
			$district = '';
			$location = @$data['Region']['Names']['im'].', '.$distr;
		}
		else
		{
			$district = $distr;
			$location = @$data['City']['name'];
			if( $in_region ) $location = @$data['Region']['Names']['im'].', '.$location.' '.@$data['City']['socr'];
		}

		$street = @$data['Street']['name'];
		$address = '';

		if( $street )
		{
			$address = $street;
			if( $add_street_abbr ) $address.= ' '.$data['Street']['abbr'];
		}
		elseif( $data['address_note'] )
		{
			$address = $data['address_note'];
		}

		if( !$address )
		{
			$address = $district;
		}

		$location.= ', '.$address;

		return $location;
	}

    public static function getAddress(Ds_Detail_DetailData $data)
    {
        $out = '';
        if( !($data->isSetAndArray('Street') or $data->Street->name) ) return;

        $out.= ($data->Street->isSetAnd('abbr')? $data->Street->abbr.' ' : '').$data->Street->name;
        if( $data->isSetAnd('building_num') ) $out.= ', '.$data->building_num;
        if( $data->isSetAnd('corpus') ) $out.= (is_numeric($data->corpus)? '/' : '').$data->corpus;

        return $out;
    }
}

