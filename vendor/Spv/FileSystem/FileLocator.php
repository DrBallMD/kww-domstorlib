<?php

/**
 * Description of FileLocator
 *
 * @author pahhan
 */
class Spv_FileSystem_FileLocator
{
    /**
     * Array of paths where file can be located
     * @var array
     */
    private $paths = array();

    /**
     * Array of named paths
     * @var array
     */
    private $ns_paths = array();

    /**
     * Extension to search
     * @var type
     */
    private $extension;

    /**
     *
     * @param string $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     *
     * @param string $path
     * @param string $namespace
     */
    public function addPath($path, $namespace = null)
    {
        if( !is_dir($path) )
            throw new Exception(sprintf('Directory "%s" does not exists', $path));
        
        if(is_null($namespace) )
            $this->paths[] = rtrim($path, '/');
        else
            $this->addNamespace($namespace, $path);

        return $this;
    }

    private function addNamespace($name, $path)
    {
        $this->ns_paths[$name] = rtrim($path, '/');
    }

    private function findPath($file)
    {
        if( $this->extension ) $file = $file.'.'.$this->extension;
        foreach ($this->paths as $path)
        {
            $file_path = $path.'/'.$file;

            if( is_file($file_path) and is_readable($file_path) )
                return $file_path;
        }

        return FALSE;
    }

    private function findNSPath($namespace, $file)
    {
        if( !isset($this->ns_paths[$namespace]) )
            throw new Spv_Templating_TemplatingException(sprintf('Tamplate namespase "%s" is not defined', $namespace));

        if( $this->extension ) $file = $file.'.'.$this->extension;
        $path = $this->ns_paths[$namespace];

        $file_path = $path.'/'.$file;
        if( is_file($file_path) and is_readable($file_path) )
                return $file_path;

        return FALSE;
    }

    public function find($file)
    {
        $file_parts = explode('/', $file);
        if( strpos($file_parts[0], '@') === 0 )
        {
            $namespace = str_replace('@', '', $file_parts[0]);
            unset($file_parts[0]);
            $file = implode('/', $file_parts);

            return $this->findNSPath($namespace, $file);
        }

        return $this->findPath($file);
    }
}

