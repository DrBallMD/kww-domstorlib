<?php

/**
 * Description of SuburbanField
 *
 * @author pahhan
 */
class Ds_Form_Field_SuburbanField extends Spv_Form_Field_Select
{
    public function __construct($name = 'suburban')
    {
        parent::__construct($name, array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/multiselect.html.twig',
            'multiple' => TRUE,
            'required' => FALSE,
            'label' => 'Пригород:',
        ));
    }
}

