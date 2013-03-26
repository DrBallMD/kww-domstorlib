<?php

/**
 * Description of AbstractBlock
 *
 * @author pahhan
 */
abstract class Ds_Detail_Block_AbstractBlock implements Ds_Detail_Block_BlockInterface
{
    private $data;

    private $templating;

    private $template;

    protected $params = array();

    public function __construct(Ds_Detail_DetailData $data, Spv_Templating_TemplatingInterface $templating, $template = NULL)
    {
        $this->data = $data;
        $this->templating = $templating;
        $this->template = $template;
    }

    /**
     *
     * @return Ds_Detail_DetailData
     */
    public function getData() {
        return $this->data;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function getParam($name)
    {
        if( isset($this->params[$name]) )
            return $this->params[$name];
    }

    /**
     *
     * @return Spv_Templating_TemplatingInterface
     */
    public function getTemplating() {
        return $this->templating;
    }

    public function setTemplate($template) {
        $this->template = $template;
    }

    public function getTemplate() {
        return $this->template;
    }

    public function __toString() {
        return $this->render(array());
    }

    public function setDetail(Ds_Detail_DetailInterface $detail) {
        $this->detail = $detail;
    }

}

