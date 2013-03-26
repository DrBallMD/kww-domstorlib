<?php

/**
 * Description of AbstractDetail
 *
 * @author pahhan
 */
abstract class Ds_Detail_AbstractDetail implements Ds_Detail_DetailInterface
{
    /**
     * @var Ds_Detail_DetailData
     */
    protected $data;

    /**
     * Template engine
     * @var Spv_Templating_TemplatingInterface
     */
    private $templating;

    protected $template;

    /**
     *
     * @var Ds_Detail_Block_BlockFactory
     */
    private $block_factory;

    private $blocks = array();

    /**
     * @var Spv_Transformer_TransformerChainInterface
     */
    protected $transformer_chain;

    public function __construct(Spv_Templating_TemplatingInterface $templating, Ds_Detail_Block_BlockFactory $block_factory)
    {
        $this->templating = $templating;
        $this->block_factory = $block_factory;
        $block_factory->setTemplating($templating);
    }

    /**
     * Returns template engine
     * @return Spv_Templating_TemplatingInterface
     */
    protected function getTemplating()
    {
        return $this->templating;
    }

    /**
     * @return Ds_Detail_Block_BlockFactory
     */
    public function getBlockFactory() {
        return $this->block_factory;
    }

    public function setTransformerChain(Spv_Transformer_TransformerChainInterface $chain)
    {
        $this->transformer_chain = $chain;
    }

    public function setData($data)
    {
        if( !is_array($data) )
            throw new Ds_Detail_DetailException('Detail data must be an array');

        if( $this->transformer_chain )
            $data = $this->transformer_chain->transform ($data);

        $this->data = new Ds_Detail_DetailData($data);
        $this->block_factory->setData($this->data);
    }

    /**
     * @return Ds_Detail_DetailData
     */
    protected function getData()
    {
        return $this->data;
    }

    /**
     * @return boolean
     */
    public function hasData()
    {
        return $this->data && count($this->data)!==0;
    }

    protected function renderTemplate($template, array $vars = array())
    {
        $vars['data'] = $this->getData()->getArray();
        return $this->getTemplating()->render($template, $vars);
    }

    /**
     *
     * @param string $id
     * @return Ds_Detail_Block_BlockInterface
     */
    public function getBlock($id)
    {
        if( isset($this->blocks[$id]) )
            return $this->blocks[$id];

        $block = $this->getBlockFactory()->create($id);
        $this->blocks[$id] = $block;

        return $block;
    }

    public function render()
    {
        $vars['detail'] = $this;
        $vars['data'] = $this->getData()->getArray();
        return $this->templating->render($this->template, $vars);
    }

}