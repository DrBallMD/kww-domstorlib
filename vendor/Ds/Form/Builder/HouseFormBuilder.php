<?php

/**
 * Description of HouseFormBuilder
 *
 * @author pahhan
 */
class Ds_Form_Builder_HouseFormBuilder extends Ds_Form_Builder_BaseFormBuilder
{
    public function getLoaderInfo()
    {
        $info = parent::getLoaderInfo();

        $info['data']['types'] = array(
            'params' => array(
                'object' => 'house',
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
     * @return Spv_Form_Form
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
            'template' => '@form/house_sale.html.twig',
            'source_transformer' => new Ds_Form_Transformer_HouseTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_RoomsField());

        $form->addForm(new Ds_Form_Field_PriceField());

        $form->addForm(new Ds_Form_Field_SliderField('square', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Дома:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('squareg', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Земли:',
        )));

        $form->addForm(new Ds_Form_Field_TypeField());

        $form->addForm(new Ds_Form_Field_StateField());

        return $form;
    }

    protected function buildRent()
    {
        $form = new Ds_Form_BaseForm('f', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/house_rent.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_RoomsField());

        $form->addForm(new Ds_Form_Field_RentField());

        $form->addForm(new Ds_Form_Field_TypeField());

        $form->addForm(new Ds_Form_Field_StateField());

        return $form;
    }

    protected function buildExchange()
    {
        $form = new Ds_Form_BaseForm('f', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/house_exchange.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_RoomsField());

        $form->addForm(new Ds_Form_Field_PriceField());

         $form->addForm(new Ds_Form_Field_SliderField('square', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Дома:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('squareg', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Земли:',
        )));

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

    protected function buildPurchase()
    {
        $form = new Ds_Form_BaseForm('f', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/house_purchase.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_RoomsField());

        $form->addForm(new Ds_Form_Field_PriceField());

        $form->addForm(new Ds_Form_Field_SliderField('square', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Дома:',
        )));

        $form->addForm(new Ds_Form_Field_SliderField('squareg', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/square.html.twig',
            'slider_min' => 20,
            'slider_max' => 200,
            'slider_step' => 5,
            'label' => 'Земли:',
        )));

        $form->addForm(new Ds_Form_Field_TypeField());

        $form->addForm(new Ds_Form_Field_StateField());

        return $form;
    }

    protected function buildRentuse()
    {
        $form = new Ds_Form_BaseForm('f', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/house_rentuse.html.twig',
            'source_transformer' => new Ds_Form_Transformer_FlatTransformer(),
        ));

        $form->addForm(new Ds_Form_Field_RoomsField());

        $form->addForm(new Ds_Form_Field_RentField());

        $form->addForm(new Ds_Form_Field_TypeField());

        $form->addForm(new Ds_Form_Field_StateField());

        return $form;
    }
}

