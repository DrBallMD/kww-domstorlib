<?php

/**
 * Description of BlockFactory
 *
 * @author pahhan
 */
class Ds_Detail_Block_BlockFactory
{
    private $config;

    /**
     * @var Ds_Detail_DetailData
     */
    private $data;

    /**
     * @var Spv_Templating_TemplatingInterface
     */
    private $templating;

    public function setData(Ds_Detail_DetailData $data) {
        $this->data = $data;
    }

    public function setTemplating(Spv_Templating_TemplatingInterface $templating) {
        $this->templating = $templating;
    }

    public function setConfig(array $config) {
        if( $this->config )
            throw new Ds_Detail_Block_BlockFactoryException('Config is already setted');

        $this->config = $config;
    }

    /**
     *
     * @param string $id
     * @return Ds_Detail_Block_BlockInterface
     * @throws Ds_Detail_Block_BlockFactoryException
     */
    public function create($id)
    {
        if( !$this->config )
            throw new Ds_Detail_Block_BlockFactoryException('Config undefined');

        $config = $this->getConfig($id);

        if( !isset($config['class']) )
            throw new Ds_Detail_Block_BlockFactoryException(sprintf('Undefined class for block with id="%s"', $id));

        $class = $config['class'];
        $template = isset($config['template'])? $config['template'] : NULL;

        $reflection = new ReflectionClass($class);

        if( !$reflection->implementsInterface('Ds_Detail_Block_BlockInterface') )
            throw new Ds_Detail_Block_BlockFactoryException(sprintf(
                    'Class "%s" for block "%s" must implements "Ds_Detail_Block_BlockInterface" interface',
                    $class,
                    $id));

        return new $class($this->data, $this->templating, $template);
    }

    private function getConfig($id)
    {
        if( !isset($this->config[$id]) )
            throw new Ds_Detail_Block_BlockFactoryException(sprintf('Undefined block id %s', $id));

        return $this->config[$id];
    }


}

