<?php

/**
 * Description of IsNewField
 *
 * @author pahhan
 */
class Ds_Form_Field_IsNewField extends Spv_Form_Field_RadioSet
{
    public function __construct()
    {
        parent::__construct('new');
        $this->setTemplatingKey('ds_twig')
                ->setTemplate('@form/fields/is_new.html.twig')
                ->setOptions(array('Вторичный рынок', 'Новостройки'));
    }
}
