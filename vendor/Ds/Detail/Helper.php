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

        if (in_array($socr, array('г', 'п')))
        {
            $socr.= '.';
        }
        if (substr($name, -1) == 'й')
        {
            return $name . ' ' . $socr;
        }
        else
        {
            return $socr . ' ' . $name;
        }
    }

    public static function getMetroDemand(array $metros)
    {
        $out = array();

        foreach ($metros as $metro)
        {
            $out[] = $metro['name'];
        }
        if (empty($out))
        {
            return '';
        }
        return implode(', ', $out);
    }

    public static function getBuilding(Ds_Detail_DetailData $data)
    {
        $out = '';
        if ($out = $data->building_num)
        {
            if ($corpus = $data->corpus)
            {
                $s = is_numeric($corpus) ? '/' : '';
                $out.= $s . $corpus;
            }
        }
        return $out;
    }

    public static function getLocation(Ds_Detail_DetailData $data, array $options = array())
    {

        $location = $data['city_id'] ? $data['location_name'] : sprintf('%s %s, %s', $data['Subregion']['name'], $data['Subregion']['socr'], $data['location_name']);
        if (isset($data['City']) and ! empty($data['City']['name']))
        {
            $location.= ', ' . $data['City']['name'];
        }
        elseif (!empty($data['street_name']))
        {
            $location.= ', ' . $data['street_name'];
        }
        return $location;
    }

    public static function getAddress(Ds_Detail_DetailData $data)
    {
        $out = '';
        $street = '';

        if ($data['street_name'])
        {
            $street = $data['street_name'];
        }
        else
        {
            if (!($data->isSetAndArray('Street') or $data->Street->name))
            {
                return;
            }
            $street = $data->Street->name;
        }
        $out.= ($data->Street->isSetAnd('abbr') ? $data->Street->abbr . ' ' : '') . $street;
        if ($data->isSetAnd('building_num'))
        {
            $out.= ', ' . $data->building_num;
        }
        if ($data->isSetAnd('corpus'))
        {
            $out.= (is_numeric($data->corpus) ? '/' : '') . $data->corpus;
        }
        return $out;
    }

}
