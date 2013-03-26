<?php

/**
 * Description of FormHiddenFields
 *
 * @author pahhan
 */
class Ds_Form_FormHiddenFields extends Spv_Widget_Widget
{

    protected $templating;
    protected $template = '@form/hidden_fields.html.twig';
    protected $values;

    public function __construct(Spv_Templating_TemplatingInterface $templating)
    {
        $this->templating = $templating;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function setValues(array $values)
    {
        $this->values = $values;
    }

    protected function valuesToNames(array $values, $key = NULL)
    {
        $out = array();

        foreach( $values as $name => $value)
        {
            if( is_array($value) )
                $out += $this->valuesToNames($value, $name);
            elseif( !is_null($value) )
            {
                if( $key )
                {
                    $out[sprintf('%s[%s]', $key, $name)] = $value;
                }
                else
                {
                    $out[$name] = $value;
                }
            }

        }

        return $out;
    }

    public function render()
    {
        $names = $this->valuesToNames($this->values);
        return $this->templating->render($this->template, array('names' => $names));
    }

}

