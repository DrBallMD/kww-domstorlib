<?php

/**
 * Description of DetailData
 *
 * @author pahhan
 */
class Ds_Detail_DetailData implements ArrayAccess, Countable, Iterator
{
    private $__data_;

    public function __construct( array $data = array() )
    {
        $this->__data_ = $data;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->__data_);
    }

    /**
     * Returns value or new DataDetail or default
     * @param string $key
     * @param mixed $default
     * @return Ds_Detail_DetailData|mixed
     */
    public function get($key, $default = NULL)
    {
        return $this->has($key)?
            $this->convert($this->__data_[$key]):
            $default;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Returns data array
     * @return array
     */
    public function getArray()
    {
        return $this->__data_;
    }


    public function isSetAnd($key)
    {
        return isset($this->__data_[$key]) && $this->__data_[$key];
    }

    public function isSetAndArray($key)
    {
        return isset($this->__data_[$key]) && is_array($this->__data_[$key]);
    }

    private function convert($value)
    {
        if( is_array($value) )
            return new Ds_Detail_DetailData($value);

        return $value;
    }

    public function offsetExists($offset) {
        return $this->has($offset);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value) {

    }

    public function offsetUnset($offset) {

    }

    public function count() {
        return count($this->__data_);
    }

    public function current() {
        return current($this->__data_);
    }

    public function key() {
        return key($this->__data_);
    }

    public function next() {
        return next($this->__data_);
    }

    public function rewind() {
        reset($this->__data_);
    }

    public function valid() {
        $r = (bool) next($this->__data_);
        prev($this->__data_);
        return $r;
    }
}

