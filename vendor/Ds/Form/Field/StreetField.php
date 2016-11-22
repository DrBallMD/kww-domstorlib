<?php

/**
 * Description of DistrictField
 *
 * @author pahhan
 */
class Ds_Form_Field_StreetField extends Spv_Form_Field_Select
{
    public function __construct($name = 'street')
    {
        parent::__construct($name, array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/street.html.twig',
            'multiple' => TRUE,
            'required' => FALSE,
            'label' => 'Улица:',
        ));
    }
}

