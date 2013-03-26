<?php

/**
 * Description of LandFormBuilder
 *
 * @author pahhan
 */
class Ds_Form_Builder_LandFormBuilder extends Ds_Form_Builder_BaseFormBuilder
{
    public function getLoaderInfo()
    {
        $info = parent::getLoaderInfo();

        $info['data']['types'] = array(
            'params' => array(
                'object' => 'land',
                'ref_city' => $this->ref_city,
            )
        );

        return $info;
    }

    protected function buildSale()
    {
        $form = new Ds_Form_BaseForm('f', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/land_sale.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_PriceField());

        $form->addForm(new Ds_Form_Field_SliderField('squareg', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/slider.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Площадь:',
        )));

        $form->addForm(new Ds_Form_Field_TypeField());

        return $form;
    }

    protected function buildRent()
    {
        $form = new Ds_Form_BaseForm('f', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/land_rent.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_RentField());

        $form->addForm(new Ds_Form_Field_SliderField('squareg', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/slider.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Площадь:',
        )));

        $form->addForm(new Ds_Form_Field_TypeField());

        return $form;
    }

    protected function buildPurchase()
    {
        $form = new Ds_Form_BaseForm('f', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/land_purchase.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_PriceField());

        $form->addForm(new Ds_Form_Field_SliderField('squareg', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/slider.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Площадь:',
        )));

        $form->addForm(new Ds_Form_Field_TypeField());

        return $form;
    }

    protected function buildRentuse()
    {
        $form = new Ds_Form_BaseForm('f', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/land_rentuse.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_RentField());

        $form->addForm(new Ds_Form_Field_SliderField('squareg', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/slider.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Площадь:',
        )));

        $form->addForm(new Ds_Form_Field_TypeField());

        return $form;
    }
}

