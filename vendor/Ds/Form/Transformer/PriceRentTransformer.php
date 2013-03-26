<?php

/**
 * Removes whiespaces
 *
 * @author pahhan
 */
class Ds_Form_Transformer_PriceRentTransformer extends Spv_Form_SourceTransformer
{
    public function sourceToForm($source_value)
    {

    }

    public function formToSource($form_value)
    {
        if( isset( $form_value['min']) )
            $form_value['min'] = str_replace(' ', '', $form_value['min']);

        if( isset( $form_value['max']) )
            $form_value['max'] = str_replace(' ', '', $form_value['max']);

        return $form_value;
    }
}

