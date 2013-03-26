<?php

/**
 * Description of TemplatingFactory
 *
 * @author pahhan
 */
class Spv_Form_TemplatingDispatcher
{
    private static $instance;

    private $engines = array();

    /**
     *
     * @return Spv_Form_TemplatingDispatcher
     */
    public static function getInstance()
    {
        if(is_null(self::$instance) )
            self::$instance = new Spv_Form_TemplatingDispatcher();

        return self::$instance;
    }

    /**
     *
     * @param string $key
     * @param Spv_Templating_TemplatingInterface $templating
     */
    public function register($key, Spv_Templating_TemplatingInterface $templating)
    {
        $this->engines[$key] = $templating;
    }

    /**
     *
     * @param string $key
     * @return Spv_Templating_TemplatingInterface
     * @throws Spv_Form_FormException
     */
    public function get($key)
    {
        if( !isset($this->engines[$key]) )
            throw new Spv_Form_FormException(sprintf('Templating with key "%s" not define', $key));

        return $this->engines[$key];
    }


}

