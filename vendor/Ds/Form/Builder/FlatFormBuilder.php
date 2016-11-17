<?php

/**
 * Description of FlatFormBuilder
 *
 * @author pahhan
 */
class Ds_Form_Builder_FlatFormBuilder extends Ds_Form_Builder_BaseFormBuilder
{
    public function getLoaderInfo()
    {
        $info = parent::getLoaderInfo();

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

        if( $this->action == 'exchange' )
        {
            if( $this->in_region )
            {
                $this->form_instance->getForm('exch_city')->setOptions(
                        $this->form_instance->getForm('city')->getOptions()
                        );
            }
            else
            {
                $this->form_instance->getForm('exch_dist')->setOptions(
                        $this->form_instance->getForm('district')->getOptions()
                        );
            }
        }
    }

    public function build()
    {
        $form = parent::build();

        /* Add location field for exchange if action is exchage */
        if( $this->action == 'exchange' )
            $form->addForm($this->createExchangeLocationField($this->in_region));

        return $form;
    }

    /**
     * Creates field for exchange location
     * @param integer $ref_city_id
     * @param boolean $in_region
     * @return \Spv_Form_Form
     */
    private function createExchangeLocationField($in_region)
    {
        if( $in_region )
        {
            $field = new Ds_Form_Field_CityField('exch_city');
        }
        else
        {
            $field = new Ds_Form_Field_DistrictField('exch_dist');
        }
        return $field;
    }

    /**
     *
     * @param string $action
     * @return Ds_Form_BaseForm
     * @throws Exception If action is undefined
     */
    protected function createForm($action)
    {
        if( $action === 'sale' )
            $form = $this->buildSale();
        elseif( $action === 'new' )
            $form = $this->buildNew();
        elseif( $action === 'rent' )
            $form = $this->buildRent();
        elseif( $action === 'purchase' )
            $form = $this->buildPurchase();
        elseif( $action === 'rentuse' )
            $form = $this->buildRentuse();
        elseif( $action === 'exchange' )
            $form = $this->buildExchange();
        else
            throw new Exception(sprintf('Unknown action "%s"', $action));

        return $form;
    }

    protected function buildSale()
    {
        $form = $this->getContainer()->get('form.base');
        $form->setProperties(array(
            'name' => 'f',
            'template' => '@form/flat_sale.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));
        
        $form->addForm(new Ds_Form_Field_RoomsField());

        $form->addForm(new Ds_Form_Field_PriceField());

        $form->addForm(new Spv_Form_Field_Select('floor_type', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/select.html.twig',
            'required' => FALSE,
            'label' => 'Этаж:',
            'options' => array(
                'first' => 'только первый',
                'last' => 'только последний',
                'not_first' => 'кроме первого',
                'not_last' => 'кроме последнего',
                'not_first_last' => 'кроме первого и последнего',
            ),
        )));

        $form->addForm(new Spv_Form_Field_Select('max_floor', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/select.html.twig',
            'required' => FALSE,
            'label' => 'не выше',
            'options' => array_combine(range(1, 20), range(1, 20)),
        )));

        $form->addForm(new Ds_Form_Field_IsNewField());

        $form->addForm(new Ds_Form_Field_SliderField('square', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Общая:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('squarel', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Жилая:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('squarek', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 5,
            'slider_max' => 20,
            'slider_step' => 1,
            'label' => 'Кухня:',
        )));

        $form->addForm(new Spv_Form_Field_Checkbox('together', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/checkbox.html.twig',
            'label' => 'Совместная продажа соседних квартир',
        )));

        $form->addForm(new Spv_Form_Field_Checkbox('for_com', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/checkbox.html.twig',
            'label' => 'Квартиры под нежилое',
        )));

        $form->addForm(new Spv_Form_Field_Checkbox('in_communal', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/checkbox.html.twig',
            'label' => 'Комнаты в коммуналке',
        )));

        $form->addForm(new Ds_Form_Field_TypeField());

        $form->addForm(new Ds_Form_Field_StateField());

        return $form;
    }

    protected function buildRent()
    {
        $form = $this->getContainer()->get('form.base');
        $form->setProperties(array(
            'name' => 'f',
            'template' => '@form/flat_rent.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_RoomsField());

        $form->addForm(new Ds_Form_Field_RentField());

        $form->addForm(new Spv_Form_Field_Select('floor_type', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/select.html.twig',
            'required' => FALSE,
            'label' => 'Этаж:',
            'options' => array(
                'first' => 'только первый',
                'last' => 'только последний',
                'not_first' => 'кроме первого',
                'not_last' => 'кроме последнего',
                'not_first_last' => 'кроме первого и последнего',
            ),
        )));

        $form->addForm(new Spv_Form_Field_Select('max_floor', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/select.html.twig',
            'required' => FALSE,
            'label' => 'не выше',
            'options' => array_combine(range(1, 20), range(1, 20)),
        )));

        $form->addForm(new Ds_Form_Field_SliderField('square', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Общая:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('squarel', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Жилая:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('squarek', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 5,
            'slider_max' => 20,
            'slider_step' => 1,
            'label' => 'Кухня:',
        )));

        $form->addForm(new Spv_Form_Field_Checkbox('furniture', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/checkbox.html.twig',
            'label' => 'С мебелью',
        )));

        $form->addForm(new Spv_Form_Field_Checkbox('in_communal', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/checkbox.html.twig',
            'label' => 'Комнаты в коммуналке',
        )));

        $form->addForm(new Ds_Form_Field_TypeField());

        $form->addForm(new Ds_Form_Field_StateField());

        return $form;
    }

    protected function buildPurchase()
    {
        $form = $this->getContainer()->get('form.base');
        $form->setProperties(array(
            'name' => 'f',
            'template' => '@form/flat_purchase.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_RoomsField());

        $form->addForm(new Ds_Form_Field_PriceField());

        $form->addForm(new Ds_Form_Field_IsNewField());

        $form->addForm(new Ds_Form_Field_SliderField('square', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Общая:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('squarel', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Жилая:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('squarek', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 5,
            'slider_max' => 20,
            'slider_step' => 1,
            'label' => 'Кухня:',
        )));

        $form->addForm(new Spv_Form_Field_Checkbox('together', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/checkbox.html.twig',
            'label' => 'Совместная продажа соседних квартир',
        )));

        $form->addForm(new Spv_Form_Field_Checkbox('for_com', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/checkbox.html.twig',
            'label' => 'Квартиры под нежилое',
        )));

        $form->addForm(new Spv_Form_Field_Checkbox('in_communal', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/checkbox.html.twig',
            'label' => 'Комнаты в коммуналке',
        )));

        $form->addForm(new Ds_Form_Field_TypeField());

        $form->addForm(new Ds_Form_Field_StateField());

        return $form;
    }

    protected function buildRentuse()
    {
        $form = $this->getContainer()->get('form.base');
        $form->setProperties(array(
            'name' => 'f',
            'template' => '@form/flat_rentuse.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_RoomsField());

        $form->addForm(new Ds_Form_Field_RentField());

        $form->addForm(new Ds_Form_Field_SliderField('square', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Общая:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('squarel', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Жилая:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('squarek', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 5,
            'slider_max' => 20,
            'slider_step' => 1,
            'label' => 'Кухня:',
        )));

        $form->addForm(new Spv_Form_Field_Checkbox('furniture', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/checkbox.html.twig',
            'label' => 'С мебелью',
        )));

        $form->addForm(new Ds_Form_Field_TypeField());

        $form->addForm(new Ds_Form_Field_StateField());

        return $form;
    }

    protected function buildNew()
    {
        $form = $this->getContainer()->get('form.base');
        $form->setProperties(array(
            'name' => 'f',
            'template' => '@form/flat_new.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_RoomsField());

        $form->addForm(new Ds_Form_Field_PriceField());

        $form->addForm(new Spv_Form_Field_Select('floor_type', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/select.html.twig',
            'required' => FALSE,
            'label' => 'Этаж:',
            'options' => array(
                'first' => 'только первый',
                'last' => 'только последний',
                'not_first' => 'кроме первого',
                'not_last' => 'кроме последнего',
                'not_first_last' => 'кроме первого и последнего',
            ),
        )));

        $form->addForm(new Spv_Form_Field_Select('max_floor', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/select.html.twig',
            'required' => FALSE,
            'label' => 'не выше',
            'options' => array_combine(range(1, 20), range(1, 20)),
        )));

        $form->addForm(new Ds_Form_Field_SliderField('square', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Общая:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('squarel', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Жилая:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('squarek', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 5,
            'slider_max' => 20,
            'slider_step' => 1,
            'label' => 'Кухня:',
        )));

        $form->addForm(new Ds_Form_Field_TypeField());

        $form->addForm(new Ds_Form_Field_StateField());

        return $form;
    }

    protected function buildExchange()
    {
        $form = $this->getContainer()->get('form.base');
        $form->setProperties(array(
            'name' => 'f',
            'template' => '@form/flat_exchange.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_RoomsField());

        $form->addForm(new Ds_Form_Field_PriceField());

        $form->addForm(new Spv_Form_Field_Select('floor_type', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/select.html.twig',
            'required' => FALSE,
            'label' => 'Этаж:',
            'options' => array(
                'first' => 'только первый',
                'last' => 'только последний',
                'not_first' => 'кроме первого',
                'not_last' => 'кроме последнего',
                'not_first_last' => 'кроме первого и последнего',
            ),
        )));

        $form->addForm(new Spv_Form_Field_Select('max_floor', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/select.html.twig',
            'required' => FALSE,
            'label' => 'не выше',
            'options' => array_combine(range(1, 20), range(1, 20)),
        )));

        $form->addForm(new Ds_Form_Field_IsNewField());

        $form->addForm(new Ds_Form_Field_TypeField());

        $form->addForm(new Ds_Form_Field_OneOfTwoField('exch_flat', array(
            'options' => array(1 => 'Квартира', 0 => 'Дом')
        )));

        $form->addForm(new Spv_Form_Field_InputText('exch_rooms', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/input_text.html.twig',
            'label' => 'Число комнат:',
        )));

        $form->addForm(new Spv_Form_Field_InputText('exch_price', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/input_text.html.twig',
            'label' => 'Цена:',
        )));

        return $form;
    }

}