<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OnPageDefiner
 *
 * @author pahhan
 */
class Ds_Definer_OnPageDefiner implements Ds_Definer_DefinerInterface
{
    public function bind(array $value)
    {

    }

    public function define()
    {
         if( isset($_REQUEST['onpage']) )
         {
             $o = (int) $_REQUEST['onpage'];
             if( $o > 0 or $o < 50 ) return $o;
         }
         return 50;
    }

}

