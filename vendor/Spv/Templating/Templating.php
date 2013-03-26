<?php

/**
 * Description of Templating
 *
 * @author pahhan
 */
class Spv_Templating_Templating implements Spv_Templating_TemplatingInterface
{
    /**
     * File locator for searchig teplate files in file system
     * @var Spv_FileSystem_FileLocator
     */
    private $file_locator;

    public function __construct(Spv_FileSystem_FileLocator $file_locator)
    {
        $this->file_locator = $file_locator;
    }

    /**
     *
     * @param Spv_FileSystem_FileLocator $file_locator
     */
    public function setFileLocator($file_locator)
    {
        $this->file_locator = $file_locator;
    }

    /**
     * Returns file locator
     * @return Spv_Templating_FileLocator
     */
    public function getFileLocator()
    {
        return $this->file_locator;
    }

    /**
     *
     * @param Spv_Templating_Template $template
     * @param array $vars
     * @return string
     */
    public function render($template, array $vars = array())
    {
        $template = $this->getTemplate($template);

        return $template->render($vars);
    }

    /**
     *
     * @param Spv_Templating_Template $template
     * @return \Spv_Templating_Template
     * @throws Spv_Templating_TemplatingException If template not found
     */
    public function getTemplate($template)
    {
        $tmpl_path = $this->file_locator->find($template);
        if( !$tmpl_path )
            throw new Spv_Templating_TemplatingException(sprintf('Can not find "%s" template', $template));

        $template = new Spv_Templating_Template($tmpl_path);

        return $template;
    }
}

