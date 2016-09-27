<?php
/**
 * Description of DeliveryTransformer
 *
 * @author Dmitry Anikeev <da@kww.su>
 */
class Ds_Form_Transformer_DeliveryTransformer extends Spv_Form_SourceTransformer
{
    public function sourceToForm($source_value)
    {

    }

    public function formToSource($form_value)
    {
        if( isset( $form_value['quarter']) )
            $form_value['quarter'] = str_replace(' ', '', $form_value['quarter']);

        if( isset( $form_value['year']) )
            $form_value['year'] = str_replace(' ', '', $form_value['year']);

        return $form_value;
    }
}
