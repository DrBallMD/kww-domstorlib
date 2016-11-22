<?php

/**
 * Description of BuildingMaterialField
 *
 * @author Dmitry Anikeev <da@kww.su>
 */
class Ds_Form_Field_WallMaterialField extends Spv_Form_Field_Select
{
    public function __construct()
    {
        parent::__construct('mat_wall', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/wall_material.html.twig',
            'multiple' => TRUE,
            'label' => 'Материал дома:',
            'required' => FALSE,
        ));
    }
}
