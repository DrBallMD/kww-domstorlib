<?php

/**
 * Description of citydistrict
 *
 * @author pahhan
 */
class Domstor_Transformer_Supply_RegionAddress implements Domstor_Transformer_Interface
{
    protected $address_transformer;

    public function __construct() {
        $this->address_transformer = new Domstor_Transformer_Supply_Address();
    }

    public function get($data)
    {
        $out = $this->address_transformer->get($data);
        if( !$out ) $address = $data['address_note'];
        if( empty($data['city_id']) and !empty($data['location_name']) ) {
            $out = $data['location_name'].', '.$address;
        }
        return trim($out, ', ');
    }
}

