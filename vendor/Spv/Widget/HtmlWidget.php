<?php

/**
 * Description of Html
 *
 * @author pahhan
 */
abstract class Spv_Widget_HtmlWidget extends Spv_Widget_Widget
{
    /**
     * Array of tag's attributes
     * @var array
     */
    protected $attrs = array();

    /**
     * Array of tag's classes
     * @var array
     */
	protected $classes = array(); // HTML tag classes

    /**
     *  Sets attributes directly
     * @param array $attrs
     */
    public function setAttrs(array $attrs)
    {
        $this->attrs = $attrs;
    }

    /**
     * Renders attribute with given name, if attribute not exists, returns empty
     * string
     * @param string $name
     * @return string
     */
    public function renderAttr($name)
    {
        if( $name == 'class' and count($this->classes) )
        {
            return sprintf('class="%s"', $this->_renderClasses());
        }

        if( isset($this->attrs[$name]) )
        {
            return sprintf('%s="%s"', $name, $this->attrs[$name]);
        }

        return '';
    }

    /**
     * Echo renderAttr() method
     * @param string $name
     */
    public function displayAttr($name)
    {
        echo $this->renderAttr($name);
    }

    public function renderAttrs(array $attrs = array(), array $classes = array())
	{
		$attrs = array_merge($this->attrs, $attrs);

		if( !array_key_exists('class', $attrs) )
		{
			$classes = array_merge($this->classes, $classes);
            $class = $this->_renderClasses($classes);
            if( $class ) $attrs['class'] = $class;
		}

		$out = '';
		if( count($attrs) )
		{
			foreach ($attrs as $key => $attr)
			{
				$out.= ' '.$key.'="'.$attr.'"';
			}
		}
		return $out;
	}

    public function displayAttrs(array $attrs = array(), array $classes = array())
    {
        echo $this->renderAttrs($attrs, $classes);
    }

	protected function _renderClasses($classes = NULL)
	{
		if( is_null($classes) ) $classes = $this->classes;
		$out = '';
		if( is_array($classes) and count($classes) )
		{
			$out = implode(' ', $classes);
		}
		return $out;
	}

	public function addAttr($name, $value)
	{
		$this->attrs[$name] = $value;
		return $this;
	}

	public function deleteAttr($name)
	{
		unset($this->attrs[$name]);
		return $this;
	}

	public function addClass($name)
	{
		if( array_search($name, $this->classes) === FALSE )
			$this->classes[] = $name;
		return $this;
	}

	public function clearClasses()
	{
		$this->classes = array();
	}

	public function deleteClass($name)
	{
		$key = array_search($name, $this->classes);
		if( $key !== FALSE )
		{
			unset($this->classes[$key]);
		}
		return $this;
	}
}