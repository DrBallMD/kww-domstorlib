<?php

/**
 * Description of StateField
 *
 * @author pahhan
 */
class Ds_Form_Field_StateField extends Spv_Form_Field_Select
{
    public function __construct()
    {
        parent::__construct('state', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/state.html.twig',
            'multiple' => TRUE,
            'label' => 'Состояние:',
            'required' => FALSE,
        ));
    }
}

