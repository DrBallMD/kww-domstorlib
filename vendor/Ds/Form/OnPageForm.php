<?php

/**
 * Description of OnPageForm
 *
 * @author pahhan
 */
class Ds_Form_OnPageForm extends Spv_Form_Field_Select
{
    protected $hidden_fields;


    public function __construct($hidden_fields) {
        parent::__construct('onpage', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/onpage.html.twig',
            'options' => array(
                '10' => '10',
                '20' => '20',
                '30' => '30',
                '40' => '40',
                '50' => '50',
            ),
        ));

        $this->hidden_fields = $hidden_fields;
    }

    public function render() {
        return parent::render(array('hidden_fields' => $this->hidden_fields));
    }
}

