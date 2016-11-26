<?php

/**
 * Description of CityField
 *
 * @author pahhan
 */
class Ds_Form_Field_CityField extends Spv_Form_Field_Select
{
    public function __construct($name = 'city')
    {
        parent::__construct($name, array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/city.html.twig',
            'multiple' => TRUE,
            'required' => FALSE,
            'label' => 'Населенный<br>пункт:',
        ));
    }
}
