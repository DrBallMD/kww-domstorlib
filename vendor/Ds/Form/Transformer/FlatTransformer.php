<?php

/**
 * Description of FlatTransformer
 *
 * @author pahhan
 */
class Ds_Form_Transformer_FlatTransformer extends Spv_Form_SourceTransformer
{
    public function sourceToForm($source_value)
    {

    }

    public function formToSource($form_value)
    {

        foreach( $form_value as $key => $value )
        {
            if( is_null($value) or (is_array($value) and count($value)===0) )
                unset($form_value[$key]);
        }

        if( isset( $form_value['price'] ) )
        {
            $price = $form_value['price'];
            $form_value['price_min'] = $price['min'];
            $form_value['price_max'] = $price['max'];
            unset($form_value['price']);
        }

        if( isset( $form_value['rent'] ) )
        {
            $rent = $form_value['rent'];
            $form_value['rent_min'] = $rent['min'];
            $form_value['rent_max'] = $rent['max'];
            $form_value['rent_period'] = $rent['period'];
            unset($form_value['price']);
        }

        foreach( array('square', 'squarel', 'squarek') as $square_name )
        {
            if( isset( $form_value[$square_name] ) )
            {
                $square = $form_value[$square_name];
                $form_value[$square_name.'_min'] = $square['min'];
                $form_value[$square_name.'_max'] = $square['max'];
                unset($form_value[$square_name]);
            }
        }

        return $form_value;
    }
}

