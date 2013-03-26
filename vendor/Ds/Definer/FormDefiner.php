<?php

/**
 * Description of SortDefiner
 *
 * @author pahhan
 */
class Ds_Definer_FormDefiner implements Ds_Definer_DefinerInterface
{
    protected $value;

    public function bind(array $value)
    {
        $this->value = $value;
    }

    public function define()
    {
        $value = $this->value? $this->value : $_REQUEST;

        if( isset($value['f']) and is_array($value['f']) ) return $value['f'];


        return array();
    }

}

