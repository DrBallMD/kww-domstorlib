<?php

/**
 * Description of SourceTransformer
 *
 * @author pahhan
 */
abstract class Spv_Form_SourceTransformer implements Spv_Transformer_TwoWayTransformerInterface
{
    private $form;

    /**
     * sourceToForm() alias
     * @param mixed $value
     * @return mixed
     */
    public function forwardTransform($value)
    {
        return $this->sourceToForm($value);
    }

    /**
     * formToSource() alias
     * @param mixed $value
     * @return mixed
     */
    public function backwardTransform($value)
    {
        return $this->formToSource($value);
    }

    abstract public function sourceToForm($source_value);
    abstract public function formToSource($form_value);

    public function setForm($form)
    {
        $this->form = $form;
    }

    public function getForm()
    {
        return $this->form;
    }
}