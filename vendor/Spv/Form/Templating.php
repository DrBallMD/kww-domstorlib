<?php

/**
 * Description of Templating
 *
 * @author pahhan
 */
class Spv_Form_Templating extends Spv_Templating_Templating
{
    public function __construct()
    {
        $file_locator = new Spv_FileSystem_FileLocator();
        $file_locator->addPath(dirname(__FILE__).'/view');
        parent::__construct($file_locator);
    }
}

