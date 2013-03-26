<?php

/**
 * Base form
 *
 * @author pahhan
 */
class Ds_Form_BaseForm extends Spv_Form_Form
{
    protected $templating;

    public function setProperties(array $properties)
    {
        foreach ($properties as $property => $value)
        {
            $this->$property = $value;
        }
    }

    public function __construct(Spv_Templating_TemplatingInterface $templating)
    {
        $this->templating = $templating;
    }

    public function getTemplating()
    {
        return $this->templating;
    }
}