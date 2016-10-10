<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CommerceFormBuilder
 *
 * @author Dmitry Anikeev <da@kww.su>
 */
class Ds_Form_Builder_CommerceFormBuilder extends Ds_Form_Builder_BaseFormBuilder
{
    public function getLoaderInfo()
    {
        $info = parent::getLoaderInfo();
        $info['key'] = 'form.builder.commerce';
        $info['data']['types'] = array(
            'params' => array(
                'object' => 'flat',
                'ref_city' => $this->ref_city,
            )
        );
        return $info;
    }

    public function onDataReceived(array $data)
    {
        parent::onDataReceived($data);
        var_dump($data);
        if ($this->form_instance->hasForm('purpose') and isset($data['Purpose']) and is_array($data['Purpose']))
        {
            $options = array();
            foreach ($data['Purpose'] as $type)
            {
                $options[$type['id']] = $type['name'];
            }
            $this->form_instance->getForm('purpose')->setOptions($options);
        }
    }

    public function build()
    {
        $form = parent::build();
        return $form;
    }

    /**
     *
     * @param string $action
     * @return Ds_Form_BaseForm
     * @throws Exception If action is undefined
     */
    protected function createForm($action)
    {
        if ($action === 'sale')
        {
            $form = $this->buildSale();
        }
        else
        {
            throw new Exception(sprintf('Unknown action "%s"', $action));
        }
        return $form;
    }

    protected function buildSale()
    {
        $form = $this->getContainer()->get('form.base');
        $form->setProperties(array(
            'name' => 'f',
            'template' => '@form/commerce_sale.html.twig'
        ));
        $form->addForm(new Ds_Form_Field_PurposeField());
//        $form->addForm(new Ds_Form_Field_RoomsField());
//        $form->addForm(new Ds_Form_Field_PriceField());
//        $form->addForm(new Spv_Form_Field_Select('floor_type', array(
//            'templating_key' => 'ds_twig',
//            'template' => '@spv/select.html.twig',
//            'required' => FALSE,
//            'label' => 'Этаж:',
//            'options' => array(
//                'first' => 'только первый',
//                'last' => 'только последний',
//                'not_first' => 'кроме первого',
//                'not_last' => 'кроме последнего',
//                'not_first_last' => 'кроме первого и последнего',
//            ),
//        )));
//        $form->addForm(new Spv_Form_Field_Select('max_floor', array(
//            'templating_key' => 'ds_twig',
//            'template' => '@spv/select.html.twig',
//            'required' => FALSE,
//            'label' => 'не выше',
//            'options' => array_combine(range(1, 20), range(1, 20)),
//        )));
//        $form->addForm(new Ds_Form_Field_SliderField('square', array(
//            'templating_key' => 'ds_twig',
//            'template' => '@form/fields/square.html.twig',
//            'slider_min' => 20,
//            'slider_max' => 200,
//            'slider_step' => 5,
//            'label' => 'Общая:',
//        )));
//        $form->addForm(new Ds_Form_Field_SliderField('squarel', array(
//            'templating_key' => 'ds_twig',
//            'template' => '@form/fields/square.html.twig',
//            'slider_min' => 20,
//            'slider_max' => 200,
//            'slider_step' => 5,
//            'label' => 'Жилая:',
//        )));
//        $form->addForm(new Ds_Form_Field_SliderField('squarek', array(
//            'templating_key' => 'ds_twig',
//            'template' => '@form/fields/square.html.twig',
//            'slider_min' => 5,
//            'slider_max' => 20,
//            'slider_step' => 1,
//            'label' => 'Кухня:',
//        )));
//        $form->addForm(new Ds_Form_Field_TypeField());
//        $form->addForm(new Ds_Form_Field_StateField());
//        $form->addForm(new Ds_Form_Field_BuildingMaterialField());
//        $form->addForm(new Spv_Form_Field_Checkbox('delivered', array(
//            'templating_key' => 'ds_twig',
//            'template' => '@spv/checkbox.html.twig',
//            'label' => 'Объект сдан',
//            'required' => FALSE
//        )));
//        $form->addForm(new Ds_Form_Field_DeliveryField('delivery_from', array(
//            'templating_key' => 'ds_twig',
//            'required' => FALSE,
//            'template' => '@form/fields/delivery.html.twig',
//            'label'=>'Срок сдачи с'
//        )));
//        $form->addForm(new Ds_Form_Field_DeliveryField('delivery_to', array(
//            'templating_key' => 'ds_twig',
//            'required' => FALSE,
//            'template' => '@form/fields/delivery.html.twig',
//            'label'=>'Срок сдачи по'
//        )));
        return $form;
    }

    protected function buildRent()
    {
        
    }

    protected function buildPurchase()
    {
        
    }

    protected function buildRentuse()
    {
        
    }

    protected function buildNew()
    {
        
    }

    protected function buildExchange()
    {
        
    }
}
