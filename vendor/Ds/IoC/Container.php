<?php

/**
 * Inversion of Control conatiner realisation
 *
 * @author pahhan
 */
class Ds_IoC_Container
{
    private static $instance;

    private $config_loader;

    private $cycle = array();
    private $instances = array();
    private $prototypes = array();

    private $services = array();

    private function __construct(){}

    /**
     *
     * @return Ds_IoC_Container
     */
    public static function instance()
    {
        if( !self::$instance )
            self::$instance = new Ds_IoC_Container();

        return self::$instance;
    }

    public function setConfigLoader(Ds_IoC_ConfigLoader_ConfigLoaderInterface $loader)
    {
        $this->config_loader = $loader;
        $this->services = $loader->load();
    }

    /**
     *
     * @param string $id
     * @return mixed
     * @throws RuntimeException
     * @throws Exception
     */
    public function get($id)
    {
        if( isset($this->instances[$id]) )
            return $this->instances[$id];

        if( isset($this->cycle[$id]) and $this->cycle[$id] )
            throw new RuntimeException(sprintf('Circular reference detected for service "%s"', $id));

        if( !isset($this->services[$id]) )
            throw new Exception(sprintf('Undefined srvice with id "%s"', $id));

        $this->cycle[$id] = true;
        $def = $this->services[$id];
        $class = $def['class'];
        $args = array();
        if( isset($def['arguments']) )
            $args = $this->makeArguments($def['arguments']);

        if( !isset($def['factory']) ) $def['factory'] = 'instance';

        $object = $this->create($id, $class, $def['factory'], $args);

        if( isset($def['calls']) )
            $this->executeCalls($object, $def['calls']);


        $this->cycle[$id] = false;


        return $object;
    }

    private function makeArguments(array $arguments)
    {
        $args = array();
        foreach( $arguments as $argument )
        {
            $arg_val = $argument['value'];
            if( is_string($arg_val) and strstr($arg_val, '@') )
            {
                $arg_val = $this->get( substr($arg_val, 1) );
            }
            $args[] = $arg_val;
        }
        return $args;
    }

    /**
     *
     * @param type $id
     * @param type $class
     * @param type $factory
     * @param type $args
     * @return stdClass
     * @throws Exception
     */
    private function create($id, $class, $factory, $args)
    {
        if( $factory == 'instance' or $factory == 'new' )
        {
            $reflector = new ReflectionClass($class);
            $object = $reflector->newInstanceArgs($args);
            if( $factory == 'instance' ) $this->instances[$id] = $object;
            return $object;
        }
        elseif( $factory == 'prototype' )
        {
            if( isset($this->prototypes[$id]) )
                $ptototype = $this->prototypes[$id];

            $reflector = new ReflectionClass($class);
            $ptototype = $reflector->newInstanceArgs($args);
            $this->prototypes[$id] = $ptototype;

            return clone $ptototype;
        }

        throw new Exception(sprintf('Unknown factory "%s"', $factory));
    }

    private function executeCalls($object, array $calls)
    {
        foreach( $calls as $call )
        {
            $method = $call['method'];
            $arg_val = $call['value'];
            if( is_string($arg_val) and strstr($arg_val, '@') )
            {
                $arg_val = $this->get( substr($arg_val, 1) );
            }
            $object->$method($arg_val);
        }
    }
}

