<?php
/**
 * Description of SliderField
 *
 * @author pahhan
 */
class Ds_Form_Field_SliderField extends Spv_Form_Form
{
    protected $slider_min;
    protected $slider_max;
    protected $slider_step;

    public function __construct($name, array $properties = array())
    {
        parent::__construct($name, $properties);

        $this->addForm(new Spv_Form_Field_InputText('min', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/input_text.html.twig',
            'label' => 'от',

        )));

        $this->addForm(new Spv_Form_Field_InputText('max', array(
            'templating_key' => 'ds_twig',
            'template' => '@spv/input_text.html.twig',
            'label' => 'до',
        )));
    }

    public function getSliderMin() {
        return $this->slider_min;
    }

    public function setSliderMin($slider_min) {
        $this->slider_min = $slider_min;
    }

    public function getSliderMax() {
        return $this->slider_max;
    }

    public function setSliderMax($slider_max) {
        $this->slider_max = $slider_max;
    }

    public function getSliderStep() {
        return $this->slider_step;
    }

    public function setSliderStep($slider_step) {
        $this->slider_step = $slider_step;
    }

    public function getSliderId()
    {
        return sprintf('%s_slider', $this->getId());
    }


}

