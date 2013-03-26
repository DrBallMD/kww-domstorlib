<?php

/**
 * Description of Type
 *
 * @author pahhan
 */
class Ds_Form_Field_TypeField extends Spv_Form_Field_Select
{
    public function __construct()
    {
        parent::__construct('type', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/type.html.twig',
            'multiple' => TRUE,
            'required' => FALSE,
        ));
    }
}

