<?php

/**
 * Description of GarageFormBuilder
 *
 * @author pahhan
 */
class Ds_Form_Builder_GarageFormBuilder extends Ds_Form_Builder_BaseFormBuilder
{
    public function getLoaderInfo()
    {
        $info = parent::getLoaderInfo();

        $info['data']['types'] = array(
            'params' => array(
                'object' => 'garage',
                'ref_city' => $this->ref_city,
            )
        );

        return $info;
    }

    protected function buildSale()
    {
        $form = new Ds_Form_BaseForm('f', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/garage_sale.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_PriceField());

        $form->addForm(new Spv_Form_Field_Select('car_count', array(
            'options' => array(1 => '1', 2 => '2', 3 => '3', 4 => '4'),
            'required' => false,
            'label' => 'Машиномест:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('long', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/slider.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Длина:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('width', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/slider.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Ширина:',
        )));

        $form->addForm(new Spv_Form_Field_Select('mat_wall', array(
            'required' => false,
            'multiple' => true,
            'label' => 'Материал стен:',
        )));

        $form->addForm(new Ds_Form_Field_TypeField());

        return $form;
    }

    protected function buildRent()
    {
        $form = new Ds_Form_BaseForm('f', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/garage_rent.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_RentField());

        $form->addForm(new Spv_Form_Field_Select('car_count', array(
            'options' => array(1 => '1', 2 => '2', 3 => '3', 4 => '4'),
            'required' => false,
            'label' => 'Паркомест:',
        )));

        $form->addForm(new Spv_Form_Field_Select('mat_wall', array(
            'required' => false,
            'multiple' => true,
            'label' => 'Материал стен:',
        )));

        $form->addForm(new Ds_Form_Field_TypeField());

        return $form;
    }

    protected function buildPurchase()
    {
        $form = new Ds_Form_BaseForm('f', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/garage_purchase.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_PriceField());

        $form->addForm(new Spv_Form_Field_Select('car_count', array(
            'options' => array(1 => '1', 2 => '2', 3 => '3', 4 => '4'),
            'required' => false,
            'label' => 'Машиномест:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('long', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/slider.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Длина:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('width', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/slider.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Ширина:',
        )));

        $form->addForm(new Spv_Form_Field_Select('mat_wall', array(
            'required' => false,
            'multiple' => true,
            'label' => 'Материал стен:',
        )));

        $form->addForm(new Ds_Form_Field_TypeField());

        return $form;
    }

    protected function buildRentuse()
    {
        $form = new Ds_Form_BaseForm('f', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/garage_rentuse.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_RentField());

        $form->addForm(new Spv_Form_Field_Select('car_count', array(
            'options' => array(1 => '1', 2 => '2', 3 => '3', 4 => '4'),
            'required' => false,
            'label' => 'Паркомест:',
        )));

        $form->addForm(new Spv_Form_Field_Select('mat_wall', array(
            'required' => false,
            'multiple' => true,
            'label' => 'Материал стен:',
        )));

        $form->addForm(new Ds_Form_Field_TypeField());

        return $form;
    }

}
