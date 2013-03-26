<?php

/**
 * Description of RoomsField
 *
 * @author pahhan
 */
class Ds_Form_Field_RoomsField extends Spv_Form_Field_Select
{
    public function __construct()
    {
        parent::__construct('rooms', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/rooms.html.twig',
            'multiple' => TRUE,
            'required' => FALSE,
            'label' => 'Число комнат:',
            'options' => array(1 => ' 1', ' 2', ' 3', ' 4', ' 5 и более'),
        ));
    }
}

