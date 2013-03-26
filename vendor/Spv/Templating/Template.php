<?php

/**
 * Description of Template
 *
 * @author pahhan
 */
class Spv_Templating_Template
{
    private $tmpl_path;
    private $vars = array();

    public function __construct($tmpl_path)
    {
        $this->tmpl_path = $tmpl_path;
    }

    public function render(array $vars = array())
    {
        extract(array_merge($this->vars, $vars));
        ob_start();
        include $this->tmpl_path;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public function display()
    {
        echo $this->render();
    }

    public function setVars(array $vars)
    {
        $this->vars = $vars;
    }

    public function __toString()
    {
        return $this->render();
    }
}

