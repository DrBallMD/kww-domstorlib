<?php

/**
 * Contains common functional. Implements Ds_DataLoader_DataLoaderClientInterface.
 * Builder should receive data to fill options for fields such as: type, state, districts, etc.
 * So before loader loads data, form must be builded
 *
 * @author pahhan
 */
abstract class Ds_Form_Builder_BaseFormBuilder implements Ds_Form_Builder_FormBuilderInterface,
                                                          Ds_DataLoader_DataLoaderClientInterface,
                                                          Ds_IoC_ContainerAwareInterface
{

    /**
     * id of city reference
     * @var integer
     */
    protected $ref_city;

    /**
     * Entity action: sale, rent, purchase,...
     * @var string
     */
    protected $action;

    /**
     * Forms are differ depends on where are entities from cities or from regions
     * @var boolean
     */
    protected $in_region;

    /**
     * Reference to buided form
     * @var Ds_Form_BaseForm
     */
    protected $form_instance;

    abstract protected function buildSale();

    abstract protected function buildRent();

    abstract protected function buildPurchase();

    abstract protected function buildRentuse();

    public function getLoaderInfo()
    {
        $info = array(
            'key' => 'form.builder.flat',
            'data' => array(
                'states' => array(
                    'params' => array(
                        'ref_city' => $this->ref_city,
                    )
                ),
            )
        );

//        if ($this->in_region)
//        {
            $info['data']['cities']['params'] = array(
                'location' => $this->ref_city
            );
//        }
//        else
//        {
            $info['data']['districts']['params'] = array(
                'location' => $this->ref_city,
            );
            $info['data']['suburbans']['params'] = array(
                'location' => $this->ref_city,
            );
            $info['data']['streets']['params'] = array(
                'location' => $this->ref_city,
            );
//      }

        return $info;
    }

    public function onDataReceived(array $data)
    {
        if (!$this->form_instance)
        {
            $this->build();
        }

        if ($this->form_instance->hasForm('type') and isset($data['types']) and is_array($data['types']))
        {
            $options = array();
            foreach ($data['types'] as $type)
            {
                $options[$type['id']] = $type['name'];
            }
            $this->form_instance->getForm('type')->setOptions($options);
        }

        if ($this->form_instance->hasForm('state') and isset($data['states']) and is_array($data['states']))
        {
            $options = array();
            foreach ($data['states'] as $state)
            {
                $options[$state['id']] = $state['name'];
            }
            $this->form_instance->getForm('state')->setOptions($options);
        }

        if (isset($data['districts']) and is_array($data['districts']) and $this->form_instance->hasForm('district'))
        {
            $this->form_instance->getForm('district')->setOptions($data['districts']);
        }

        if (isset($data['suburbans']) and is_array($data['suburbans']) and $this->form_instance->hasForm('suburban'))
        {
            $this->form_instance->getForm('suburban')->setOptions($data['suburbans']);
        }

        if (isset($data['cities']) and is_array($data['cities']) and $this->form_instance->hasForm('city'))
        {
            $options = array();
            foreach ($data['cities'] as $city)
            {
                $options[$city['id']] = $city['name'];
            }
            $this->form_instance->getForm('city')->setOptions($options);
        }
        if (isset($data['streets']) and is_array($data['streets']) and $this->form_instance->hasForm('street'))
        {
            $this->form_instance->getForm('street')->setOptions($data['streets']);
        }
    }

    public function getContainer()
    {
        return Ds_IoC_Container::instance();
    }

    /**
     * Required params: ref_city, in_region, action
     * @param array $params
     */
    public function init(array $params)
    {
        $this->ref_city = $params['ref_city'];
        $this->in_region = $params['in_region'];
        $this->action = $params['action'];
    }

    public function build()
    {
        /* Create form for given action */
        $form = $this->createForm($this->action);
        $this->form_instance = $form;

        /* Add locations field */
        $this->addLocationsField($form, $this->in_region, $this->ref_city);

        /* Add exposition and code field */
        $this->addCodeExpositionFields($form, $this->action);

        return $form;
    }

    /**
     * Creates form for given action
     * @param string $action
     * @return \Estate\Search\Form\BaseForm
     * @throws \Exception If action is undefined
     */
    protected function createForm($action)
    {
        if ($action === 'sale')
        {
            $form = $this->buildSale();
        }
        elseif ($action === 'rent')
        {
            $form = $this->buildRent();
        }
        elseif ($action === 'purchase')
        {
            $form = $this->buildPurchase();
        }
        elseif ($action === 'rentuse')
        {
            $form = $this->buildRentuse();
        }
        else
        {
            throw new Exception(sprintf('Unknown action "%s"', $action));
        }

        return $form;
    }

    /**
     *
     * @param Spv_Form_Form $form
     * @param boolean $in_region
     * @param integer $ref_city_id
     */
    protected function addLocationsField(Spv_Form_Form $form, $in_region, $ref_city_id)
    {
//        if ($in_region)
//        {
            $form->addForm(new Ds_Form_Field_CityField());
//        }
//        else
//        {
            $suburban = new Ds_Form_Field_SuburbanField();
            $form->addForm($suburban);
            $district = new Ds_Form_Field_DistrictField();
            $form->addForm($district);
            $form->addForm(new Ds_Form_Field_StreetField());
//        }
    }

    /**
     * Adds code and exposition fields which are common for all search forms
     * @param \Estate\Search\Form\BaseForm $form
     * @param string $action Defines code label
     */
    protected function addCodeExpositionFields(Ds_Form_BaseForm $form, $action)
    {
        $form->addForm(new Spv_Form_Field_InputText('code', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/input_text.html.twig',
            'label' => ( $action == 'purchase' or $action == 'rentuse' ) ? 'Код заявки:' : 'Код объекта:',
        )));

        $form->addForm(new Spv_Form_Field_InputText('expo', array(
            'templating_key' => 'ds_twig',
            'template' => '@form/fields/expo.html.twig',
            'label' => 'Обновления за',
        )));
    }

}
