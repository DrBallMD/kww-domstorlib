<?php

/**
 *
 * @author pahhan
 */
interface SPV_Transformer_TwoWayTransformerInterface
{
    /**
     * Forward transformation ( $object1->value = forwardTransform($object2->value); )
     * @param mixed $value
     * @return mixed
     */
    public function forwardTransform($value);

    /**
     * Backward transformation ( $object2->value = forwardTransform($object1->value); )
     * @param mixed $value
     * @return mixed
     */
    public function backwardTransform($value);
}