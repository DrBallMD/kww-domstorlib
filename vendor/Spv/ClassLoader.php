<?php

/**
 * Enables class autoloading
 *
 * @author pahhan
 */
class Spv_ClassLoader
{
    protected $_camels = array();
    protected $_prefixes = array();
    protected $_namespaces = array();
    protected $_paths = array();

    private function _splitCamel($string)
    {
        $array = preg_split('/([A-Z][^A-Z]*)/', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        return $array;
    }

    public function registerCamels(array $camels)
    {
        $this->_camels = $camels;
    }

    public function registerCamel($camel, $path)
    {
        $this->_camels[$camel] = $path;
    }

    public function registerPrefixes(array $prefixes)
    {
        $this->_prefixes = $prefixes;
    }

    public function registerPrefix($prefix, $path)
    {
        $this->_prefixes[$prefix] = $path;
    }

    public function registerPath($path)
    {
        $this->_paths[] = $path;
    }

    public function registerNamespace($ns, $path)
    {
        $this->_namespaces[$ns] = $path;
    }

    private function _loadPath($string)
    {
        foreach($this->_paths as $path)
        {
            $require_path = $path.'/'.$string.'.php';
            if( is_readable($require_path) )
            {
                require_once $require_path;
            }
        }
    }

    private function _loadCamel($string)
    {
        $array = $this->_splitCamel($string);
        if( !isset($array[0]) ) return false;

        $path = $this->getCamelPath($array[0]);
        if( $path )
        {
            $require_path = $this->_getRequiredPath($path, $this->_arrayToClassPath($array));
            if( $require_path )
            {
                require_once($require_path);
                return true;
            }
        }

        return false;
    }

    private function _loadNamespace($string)
    {
        $array = explode('\\', $string);
        if( !isset($array[0]) ) return false;

        $path = $this->getNamespacePath($array[0]);
        if( $path )
        {
            $require_path = $this->_getRequiredPath($path, $this->_arrayToClassPath($array));
            if( $require_path )
            {
                require_once($require_path);
                return true;
            }
        }

        return false;
    }

    private function _loadPrefix($string)
    {
        $array = explode('_', $string);
        if( !isset($array[0]) ) return false;

        $path = $this->getPrefixPath($array[0]);
        if( $path )
        {
            $require_path = $this->_getRequiredPath($path, $this->_arrayToClassPath($array));
            if( $require_path )
            {
                require_once($require_path);
                return true;
            }
        }

        return false;
    }

    private function _arrayToClassPath($array)
    {
        return implode('/',$array).'.php';
    }

    private function _getRequiredPath($path, $class_path)
    {
        $full_path = $path.'/'.$class_path;
        if(is_readable($full_path) )
            return $full_path;

        $full_path = $path.'/'.strtolower($class_path);
        if(is_readable($full_path) )
            return $full_path;

        return false;
    }

    private function getCamelPath($camel)
    {
        if( isset($this->_camels[$camel]) )
            return $this->_camels[$camel];

        return false;
    }

    private function getPrefixPath($prefix)
    {
        if( isset($this->_prefixes[$prefix]) )
            return $this->_prefixes[$prefix];

        return false;
    }

    private function getNamespacePath($ns)
    {
        if( isset($this->_namespaces[$ns]) )
            return $this->_namespaces[$ns];

        return false;
    }

    public function load($class)
    {
        if( $this->_loadNamespace($class) ) return;
        if( $this->_loadPrefix($class) ) return;
        if( $this->_loadPath($class) ) return;
        if( $this->_loadCamel($class) ) return;
    }

    public function register()
    {
        spl_autoload_register(array($this, 'load'));
    }

}

