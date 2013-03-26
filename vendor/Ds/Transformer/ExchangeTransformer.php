<?php

/**
 * Description of ExchangeTransformer
 *
 * @author pahhan
 */
class Ds_Transformer_ExchangeTransformer  implements Spv_Transformer_TransformerInterface
{
    protected $flat_url;
    protected $house_url;

    public function __construct($flat_url, $house_url)
    {
        $this->flat_url = $flat_url;
        $this->house_url = $house_url;
    }

    public function transform($row)
    {
        if( isset($row['flat_demands']) and is_array($row['flat_demands']) )
        {
            foreach($row['flat_demands'] as $key => $flat)
            {
                $row['flat_demands'][$key] = $this->transformFlat($flat);
            }
        }

        return $row;
    }

    protected function transformFlat(array $flat)
    {
        $out = array();

        $out['code'] = $flat['code'];
        $out['url'] = str_replace(':id', $flat['id'], $this->flat_url);

        $out['rooms'] = array();
        for($i=1; $i<=5; $i++)
        {
            if( isset($flat['room_count_'.$i]) and $flat['room_count_'.$i] ) $out['rooms'][] = $i;
        }

        $out['districts'] = array();
        if( isset($flat['Districts']) and is_array($flat['Districts']) )
        {
            foreach($flat['Districts'] as $district)
            {
                $out['districts'][$district['id']] = $district['name'];
            }
            unset($district);
        }

        $out['cities'] = array();
        if( isset($flat['Cities']) and is_array($flat['Cities']) )
        {
            foreach($flat['Cities'] as $city)
            {
                $out['cities'][$city['id']] = $city['type_id'] == '3'? $city['name'].' р-н' : $city['name'];
            }
            unset($city);
        }

        $out['currency'] = isset($flat['price_currency'])? $flat['price_currency'] : '';
        $out['price_min'] = isset($flat['price_full_min'])? $flat['price_full_min'] : '';
        $out['price_max'] = isset($flat['price_full_max'])? $flat['price_full_max'] : '';

        $out['price'] = '';
        if( $out['price_min'] && ($out['price_min'] == $out['price_max']) )
        {
            $out['price'] = $out['price_min'];
            unset($out['price_min'], $out['price_max']);
        }

        return $out;
    }
}

