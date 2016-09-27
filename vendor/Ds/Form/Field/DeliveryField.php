<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DeliveryField
 *
 * @author Dmitry Anikeev <da@kww.su>
 */
class Ds_Form_Field_DeliveryField extends Spv_Form_Form
{
    public function __construct($name, array $properties = array())
    {
        parent::__construct($name, $properties);

        $this->addForm(new Spv_Form_Field_Select('quarter', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/select.html.twig',
            'label' => 'квартал',
            'options' => array_combine(range(1, 4), range(1, 4)),
            'required'=>FALSE

        )));

        $this->addForm(new Spv_Form_Field_Select('year', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/select.html.twig',
            'label' => 'год',
            'options' => array_combine(range(2011, 2020), range(2011, 2020)),
            'required'=>FALSE
        )));
    }
}
