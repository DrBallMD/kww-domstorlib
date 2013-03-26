<?php

/**
 *
 * @author pahhan
 */
interface Spv_Transformer_TransformerChainInterface extends Spv_Transformer_TransformerInterface
{
    public function addTransformer($name, Spv_Transformer_TransformerInterface $transformer);
    public function deleteTransformer($name);
}
