<?php
/**
 * Description of TemplatingInterface
 *
 * @author pahhan
 */
interface Spv_Templating_TemplatingInterface
{
    /**
     * @param string $template
     * @param array $vars
     * @return string
     */
    public function render($template, array $vars = array());
}