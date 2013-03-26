<?php

/**
 *
 * @author pahhan
 */
interface Ds_Detail_Block_BlockInterface
{
    public function setDetail(Ds_Detail_DetailInterface $detail);
    public function setParams(array $params);

    public function render(array $params);
}