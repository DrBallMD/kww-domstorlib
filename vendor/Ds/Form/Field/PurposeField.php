<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PurposeField
 *
 * @author Dmitry Anikeev <da@kww.su>
 */
class Ds_Form_Field_PurposeField extends Spv_Form_Field_Select
{
    public function __construct()
    {
        parent::__construct('purpose', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/commerce/purpose.html.twig',
            'multiple' => TRUE,
            'label' => 'Назначение:',
            'required' => FALSE,
        ));
    }
}