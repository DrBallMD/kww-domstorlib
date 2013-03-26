<?php

/**
 * Description of DistrictField
 *
 * @author pahhan
 */
class Ds_Form_Field_DistrictField extends Spv_Form_Field_Select
{
    public function __construct($name = 'district')
    {
        parent::__construct($name, array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/district.html.twig',
            'multiple' => TRUE,
            'required' => FALSE,
            'label' => 'Район:',
        ));
    }
}

