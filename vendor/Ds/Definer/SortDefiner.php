<?php

/**
 * Description of SortDefiner
 *
 * @author pahhan
 */
class Ds_Definer_SortDefiner implements Ds_Definer_DefinerInterface
{
    protected $value;

    public function bind(array $value)
    {
        $this->value = $value;
    }

    public function define()
    {
        $value = $this->value? $this->value : $_REQUEST;
        $out = array();

        if( isset($value['s']) and is_array($value['s']) )
        {
            foreach( $value['s'] as $key => $val )
            {
                $out[$key] = ($val == '1' || $val == 'd')? 'd' : 'a';
            }
        }

        return $out;
    }

}

