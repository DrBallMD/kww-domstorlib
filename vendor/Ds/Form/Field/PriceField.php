<?php

/**
 * Description of RoomsField
 *
 * @author pahhan
 */
class Ds_Form_Field_PriceField extends Ds_Form_Field_SliderField
{
    public function __construct()
    {
        parent::__construct('price', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/price.html.twig',
            'source_transformer' => new Ds_Form_Transformer_PriceRentTransformer(),
            'slider_min' => 100000,
            'slider_max' => 30000000,
            'slider_step' => 50000,
        ));

        $this->addForm(new Spv_Form_Field_InputText('min', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/input_text.html.twig',
            'label' => 'от',

        )));

        $this->addForm(new Spv_Form_Field_InputText('max', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/input_text.html.twig',
            'label' => 'до',
        )));
    }
}
