<?php

/**
 * Description of citydistrict
 *
 * @author pahhan
 */
class Domstor_Transformer_Supply_Address implements Domstor_Transformer_Interface
{
    public function get($data)
    {
        $out = '';
        if( $data['street'] and $data['street_id'] ) {
            $out = $data['street'];
            if( isset($data['building_num']) and $data['building_num'] ) {
                $out.= ', '.$data['building_num'];
                if( $data['corpus'] ) {
                    if( is_numeric($data['corpus']) ) {
                        $out.= '/';
                    }
                    $out.= $data['corpus'];
                }
            }
        }
        return $out;
    }
}

