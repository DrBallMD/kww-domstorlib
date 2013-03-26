<?php

/**
 * Description of PageDefiner
 *
 * @author pahhan
 */
class Ds_Definer_PageDefiner implements Ds_Definer_DefinerInterface
{
    protected $value;

    public function bind(array $value)
    {
        $this->value = $value;
    }

    public function define()
    {
        if( isset($_REQUEST['page']) ) return $_REQUEST['page'];
    }

}

