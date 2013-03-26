<?php

/**
 * Description of OneOfTwoField
 *
 * @author pahhan
 */
class Ds_Form_Field_OneOfTwoField extends Spv_Form_Field_RadioSet
{
    public function __construct($name, array $params = array())
    {
        parent::__construct($name, array_merge(array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/one_of_two.html.twig',
        ), $params));
    }
}

