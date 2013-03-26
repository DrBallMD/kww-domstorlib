<?php

/**
 * Description of TransformerChain
 *
 * @author pahhan
 */
class Spv_Transformer_TransformerChain implements Spv_Transformer_TransformerChainInterface
{
    protected $transformers = array();

    public function addTransformer($name, Spv_Transformer_TransformerInterface $transformer)
    {
        $this->transformers[$name] = $transformer;
    }

    public function deleteTransformer($name)
    {
        if( isset($this->transformers[$name]) )
            unset ($this->transformers[$name]);
    }

    public function transform($data)
    {
        foreach ($this->transformers as $transformer)
        {
            $data = $transformer->transform($data);
        }
        return $data;
    }
}

