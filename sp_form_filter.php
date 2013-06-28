<?php
/* Author: Pavel Stepanets
 * Email:  pahhan.ne@gmail.com
 */

interface SP_Form_Filter_iField
{
	public function setName($value);
	public function getName();
	public function setLabel($value);
	public function getLabel();
	public function setMethod($method);
	public function getMethod();
	public function getMethodName();
	public function renderLabel();
	public function getFullName();
	public function getId();
//  public function render(); defined as abstract in SP_Form_Filter_Field class
	public function display();
	public function setForm(SP_Form_Filter_iForm $value);
	public function bind(array $value);
	public function &getRequestArray();
	public function bindFromRequest();
	public function getValue();
	public function isValuable();
	public function setTransformer($trans);
	public function getTransformedValue();
}

interface SP_Form_Filter_iForm
{
	public function addField(SP_Form_Filter_iField $field);
	public function addFields(array $fields);
	public function hasField($name);
	public function getField($name);
	public function deleteField($name);
	public function renderOpenTag();
	public function renderCloseTag();
	public function setAction($action);
	public function getAction();
	public function setRenderTemplate($path);
	public function getRequestString();
	public function replaceString($key, $string);
}

interface SP_Form_Filter_iTransformer
{

}

abstract class SP_Form_Filter_Transformer
{
	protected $_field;

	public function setField(SP_Form_Filter_iField $field)
	{
		$this->_field = $field;
	}

	abstract public function getRequestString();

	abstract public function getServerRequestString();
}

abstract class SP_Form_Filter_Field implements SP_Form_Filter_iField
{
	const METHOD_GET = 100;
	const METHOD_POST = 101;
	protected $_method = 100;
	protected $_name;
	protected $_label;
	protected $_form;
	protected $_value;
	protected $_is_valuable = true;
	protected $_default;
	protected $_classes = array();
	protected $_transformer;
	protected $_empty_value = '';
	protected $_is_hidden = FALSE;

	public function __construct()
	{
		return $this;
    }

	public function isHidden($val = NULL)
	{
		if( is_null($val) ) return $this->_is_hidden;
		$this->_is_hidden = (bool) $val;
		return $this;
	}

	public function setIsHidden($val)
	{
		return $this->isHidden($val);
	}

	public function count()
	{
		return 0;
	}

	public function setEmptyValue($value)
	{
		$this->_empty_value = $value;
		return $this;
	}

	public function isEmptyValue()
	{
		$empty_vals = (array) $this->_empty_value;

		foreach($empty_vals as $empty)
		{
			if( $this->_value === $empty or is_null($this->_value) ) return TRUE;
		}

		return FALSE;
	}

	public function setName($value)
	{
		$this->_name = $value;
		return $this;
	}

	public function getName()
	{
		return $this->_name;
	}

	public function setMethod($value)
	{
		$this->_method = $value;
		return $this;
	}

	public function getMethod()
	{
		$method = is_null($this->_form)? $this->_method : $this->_form_->getMethod();
		return $method;
	}

	public function getMethodName()
	{
		$method = $this->getMethod();
		if( $method == self::METHOD_GET )
		{
			return 'GET';
		}
		elseif( $method == self::METHOD_POST )
		{
			return 'POST';
		}
		throw new Exception('Unknown method');
	}

	public function setLabel($value)
	{
		$this->_label = $value;
		return $this;
	}

	public function getLabel()
	{
		return $this->_label;
	}

	public function getFullName()
	{
		if( isset($this->_form) )
		{
			$name = $this->_form->getFullName().'['.$this->getName().']';
		}
		else
		{
			$name = $this->getName();
		}

		return $name;
	}

	public function getId()
	{
		$form_name = isset($this->_form)? $this->_form->getId().'_' : '';
		return $form_name.$this->getName();
	}

	public function setDefault($value)
	{
		$this->_default = $value;
		return $this;
	}

	public function getDefault()
	{
		return $this->_default;
	}

	public function isValuable($value = NULL)
	{
		if( is_null($value) )
		{
			return $this->_is_valuable;
		}
		else
		{
			$this->_is_valuable = (bool)$value;
			return $this;
		}
	}

	public function getThisOrTrans()
	{

	}

	public function getValue()
	{
		return $this->_value;
	}

	public function getRequestString()
	{
		return '&'.$this->getFullName().'='.$this->getValue();
	}

	public function getServerRequestString()
	{
		return $this->getRequestString();
	}

	public function setTransformer($trans)
	{
		$trans->setField($this);
		$this->_transformer = $trans;
		return $this;
	}

	public function getTransformedValue()
	{
		$value = $this->getValue();

		if( !is_null($this->_transformer) )
		{
			//var_dump($value);
			$value = $this->_transformer->transform($value);
		}
		return $value;
	}

	public function setForm(SP_Form_Filter_iForm $value)
	{
		$this->_form = $value;
		return $this;
	}

	public function renderLabel()
	{
		$out = '';
		if( $label = $this->getLabel() )
		{
			$input_id = $this->getId();
			$id = 'label_'.$input_id;
			$out = '<label for="'.$input_id.'" id="'.$id.'">'.$this->getLabel().'</label>';
		}
		return $out;
	}

	public function displayLabel()
	{
		echo $this->renderLabel();
	}

	abstract public function render();

	public function display()
	{
		echo $this->render();
		return $this;
	}

	public function __toString()
	{
        try {
            return $this->render();
        }
        catch (Exception $e ) {
            return $e->getMessage();
        }
	}

	public function bind(array $values)
	{
		$name = $this->getName();
		if( isset($values[$name]) ) $this->_value = $values[$name];
		return $this;
	}

	public function &getRequestArray()
	{
		if( is_null($this->_form) )
		{
			$method = $this->getMethod();
			if( $method == self::METHOD_GET )
			{
				$array = &$_GET;
			}
			if( $method == self::METHOD_POST )
			{
				$array = &$_POST;
			}
		}
		else
		{
			$array = &$this->_form->getRequestArray();
			//var_dump($array);
		}
		if( is_null($array) ) $array = array();
		return $array;
	}

	public function bindFromRequest()
	{
		$array = &$this->getRequestArray();
		if( !is_array($array) ) $array = array();
		$this->bind($array);
		return $this;
	}

	protected function _renderClass()
	{
		if( count($this->_classes)>0 )
		{
			$classes = implode(' ', $this->_classes);
			$out = ' class="'.$classes.'"';
		}
		else
		{
			$out = '';
		}
		return $out;
	}
}

class SP_Form_Filter_Form extends SP_Form_Filter_Field implements SP_Form_Filter_iForm, Iterator, Countable
{
	protected $_fields = array();
	protected $_action = '';
	protected $_render_template;

	// Begin Iterator interface
	protected $_current_field;

	public function rewind() {

		$this->_current_field = 0;
    }

    public function current() {

		$key = $this->key();
        return $this->_fields[$key];
    }

    public function key() {

		$keys = array_keys($this->_fields);
		$key = $keys[$this->_current_field];
        return $key;
    }

    public function next() {

	   $this->_current_field += 1;
    }

    public function valid() {
		$keys = array_keys($this->_fields);
        return isset($keys[$this->_current_field]);
    }
	// End Iterarot interface

	// Begin Countable interface
	public function count() {

		return count($this->_fields);
    }
	// End Countable interface


	public function __construct()
	{
        $this->_current_field = 0;
		return $this;
    }

	public function setAction($value)
	{
		$this->_action = $value;
		return $this;
	}

	public function getAction()
	{
		return $this->_action;
	}

	public function setDefault(array $values)
	{
		foreach( $this->_fields as $field )
		{
			$field->setDefault( $values[$field->getName()] );
		}
		return $this;
	}

	public function getDefault()
	{
		$values = array();
		foreach( $this->_fields as $field )
		{
			$values[$field->getName()] = $field->getDefault();
		}
		return $values;
	}

	public function getValue()
	{
		$values = array();
		foreach( $this->_fields as $field )
		{
			if( $field->isValuable() ) $values[$field->getName()] = $field->getValue();
		}
		return $values;
	}

	public function getRequestString()
	{
		$out = '';

		foreach( $this->_fields as $field )
		{
			if( $field->isValuable() )
			{
				if( $field->count() )
				{
					$out.= $field->getRequestString();
				}
				elseif( !$field->isEmptyValue() )
				{
					$out.= '&'.$field->getFullName().'='.$field->getValue();
				}
			}
		}
		return $out;
	}

	public function getServerRequestString()
	{
		$out = '';

		foreach( $this->_fields as $field )
		{
			if( $field->isValuable() )
			{
				if( $field->count() )
				{
					$out.= $field->getServerRequestString();
				}
				elseif( !$field->isEmptyValue() )
				{
					$out.= '&'.$field->getFullName().'='.$field->getValue();
				}
			}
		}
		return $out;
	}

	public function replaceString($key, $string)
	{
		return str_replace($key, $this->getRequestString(), $string);
	}

	public function setForm(SP_Form_Filter_iForm $value)
	{
		$this->_form = $value;
		return $this;
	}

	public function setRenderTemplate($path)
	{
		$this->_render_template = $path;
		return $this;
	}

	public function renderTemplate()
	{
		ob_start();
		@include($this->_render_template);
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	public function render()
	{
		if( isset($this->_render_template) ) return $this->renderTemplate();
		throw new Exception('Render method must be redefined in extended class');
	}

	public function renderLabel()
	{
		$out = '';
		if( $label = $this->getLabel() )
		{
			$input_id = $this->getId();
			$id = 'label_'.$input_id;
			$out = $this->getLabel();
		}
		return $out;
	}

	public function renderOpenTag()
	{
		return '<form action="'.$this->getAction().'" method="'.$this->getMethodName().'" id="'.$this->getId().'"'.$this->_renderClass().'>'."\r\n";
	}

	public function renderCloseTag()
	{
		return '</form>';
	}

	public function displayOpenTag()
	{
		echo $this->renderOpenTag();
		return $this;
	}

	public function displayCloseTag()
	{
		echo $this->renderCloseTag();
		return $this;
	}

	public function displayLabel($name)
	{
		echo $this->getField($name)->renderLabel();
	}

	public function displayField($name)
	{
		echo $this->getField($name)->render();
	}

	public function bind(array $values)
	{
		foreach( $this->_fields as $field )
		{
			$field->bind( $values );
		}
	}

	public function bindFromRequest()
	{
		foreach( $this->_fields as $field )
		{
			$field->bindFromRequest();
		}
	}

	public function &getRequestArray()
	{
		$name = $this->getName();
		if( is_null($this->_form) )
		{
			$method = $this->getMethod();
			if( $method == self::METHOD_GET )
			{
				$array = &$_GET;
			}
			elseif( $method == self::METHOD_POST )
			{
				$array = &$_POST;
			}
		}
		else
		{
			$array = &$this->_form->getRequestArray();
		}
		//var_dump($array[$name]);
		return $array[$name];
	}

	public function addField(SP_Form_Filter_iField $field, $name = NULL)
	{
		$field->setForm($this);
		if( is_null($name) ) $name = $field->getName();
		$this->_fields[$name] = $field;
		return $this;
	}

	public function addFields(array $fields)
	{
		foreach( $fields as $field )
		{
			$this->addField($field);
		}
		return $this;
	}

	public function hasField($name)
	{
		return array_key_exists($name, $this->_fields);
	}

	public function getField($name)
	{
		if( $this->hasField($name) ) return $this->_fields[$name];
		throw new Exception('Form "'.$this->getName().'" do not contain "'.$name.'" field');
	}

	public function deleteField($name)
	{
		unset($this->_fields[$name]);
	}
}


// FIELDS
class SP_Form_Filter_InputText extends SP_Form_Filter_Field
{
	protected $_is_password = FALSE;
	protected $_is_xhtml = TRUE;

	public function render()
	{
		$value = ($this->_value===null)? $this->_default : $this->_value;
		$type = ($this->_is_hidden)? 'hidden' : (($this->_is_password)? 'password' : 'text');
		return '<input type="'.$type.'" name="'.$this->getFullName().'" id="'.$this->getId().'"'.$this->_renderClass().' value="'.$value.'" />';
	}
}

class SP_Form_Filter_Select extends SP_Form_Filter_Field
{
	protected $_options = array();
	protected $_multiple = FALSE;
	protected $_size;

	public function setOptions(array $options)
	{
		$this->_options = $options;
		return $this;
	}

	public function getOptions()
	{
		return $this->_options;
	}

	public function addOptions(array $array)
	{
		return $this->_options = $this->_options + $array;
	}

	public function setRange($from, $to = NULL, $first = NULL)
	{
		if( is_null($to) )
		{
			$to = $from;
			$from = 0;
		}

		$options = array();
		if( is_array($first) ) $options[key($first)]=current($first);

		for($i = $from; $i <= $to; $i++)
		{
			$options[$i] = $i;
		}

		$this->setOptions($options);

		return $this;
	}

	public function setMultiple($multiple)
	{
		$this->_multiple = (bool) $multiple;
		return $this;
	}

	public function setSize($size)
	{
		$this->_size = (integer) $size;
		return $this;
	}

	public function render()
	{
		$value = ($this->_value===null)? $this->_default : $this->_value;
		$value = (array) $value;
		$name = $this->getFullName();
		$multiple = '';

		if( $this->_multiple )
		{
			$name.= '[]';
			$multiple = ' multiple';
		}

		$size = $this->_size? ' size="'.$this->_size.'"' : '';

		$out = '<select name="'.$name.'"'.$multiple.$size.' id="'.$this->getId().'">'.PHP_EOL;

		foreach( $this->_options as $key => $option )
		{
			$selected = in_array($key, $value)? ' selected' : '';
			$out.= '<option value="'.$key.'"'.$selected.'>'.$option.'</option>'.PHP_EOL;
		}
		$out.= '</select>';

		return $out;
	}



}

class SP_Form_Filter_Submit extends SP_Form_Filter_Field
{
	public function __construct()
	{
		parent::__construct();
		$this->setName('submit');
		$this->isValuable(FALSE);
	}

	public function render()
	{
		return '<input type="submit" name="'.$this->getFullName().'" id="'.$this->getId().'"'.$this->_renderClass().' value="'.$this->getLabel().'" />';
	}

}

class SP_Form_Filter_SubmitLink extends SP_Form_Filter_Field
{
	public function __construct()
	{
		parent::__construct();
		$this->setName('submit_link');
		$this->isValuable(FALSE);
	}

	public function render()
	{
		return '<a href="" onClick="document.getElementById(\''.$this->_form->getId().'\').submit(); return false;">'.$this->getLabel().'</a> ';
	}

}

class SP_Form_Filter_Radio extends SP_Form_Filter_Field
{
	public function render()
	{
		$value = ($this->_value===null)? $this->_default : $this->_value;
		$check = $value? ' checked' : '';
		return '<input type="radio" name="'.$this->getFullName().'" id="'.$this->getId().'"'.$check.' value="'.$value.'" />';
	}
}

class SP_Form_Filter_RadioSet extends SP_Form_Filter_Field
{
	protected $_options = array();
	protected $_separator = ' ';
	protected $_label_first = TRUE;

	public function setOptions(array $options)
	{
		$this->_options = $options;
		return $this;
	}

	public function setSeparator($separator)
	{
		$this->_separator = (string) $separator;
		return $this;
	}

	public function setLabelFirst($val)
	{
		$this->_label_first = (bool) $val;
		return $this;
	}

	public function render()
	{
		$id = $this->getId();
		$value = ($this->_value===null)? $this->_default : $this->_value;
		$out = '';
		foreach( $this->_options as $key => $option )
		{
			if( $this->_label_first )
			{
				$out.= $this->renderRadioLabel($key, $option);
				$out.= $this->renderRadioField($key);
				$out.= $this->_separator.PHP_EOL;
			}
			else
			{
				$out.= $this->renderRadioField($key);
				$out.= $this->renderRadioLabel($key, $option);
				$out.= $this->_separator.PHP_EOL;
			}
		}

		return  $out;
	}

	public function renderLabel()
	{
		return $this->_label;
	}

	public function renderRadioField($key)
	{
		$id = $this->getId().'_'.$key;

		$value = ($this->_value===null)? $this->_default : $this->_value;
		$check = ($value === (string)$key)? ' checked' : '';
		return '<input type="radio" name="'.$this->getFullName().'" id="'.$id.'"'.$check.' value="'.$key.'" />';
	}

	public function renderRadioLabel($key, $option)
	{
		$id = $this->getId().'_'.$key;
		return '<label for="'.$id.'">'.$option.'</label>';
	}

	public function displayRadioField($key)
	{
		echo $this->renderRadioField($key);
	}

	public function displayRadioLabel($key, $option)
	{
		echo $this->renderRadioLabel($key, $option);
	}
}

class SP_Form_Filter_Checkbox extends SP_Form_Filter_Field
{

	public function render()
	{
		$value = ($this->_value===null)? $this->_default : $this->_value;
		$check = $value? ' checked' : '';
		return '<input type="checkbox" name="'.$this->getFullName().'" id="'.$this->getId().'"'.$check.' value="1" />';
	}

}

class SP_Form_Filter_CheckboxSet extends SP_Form_Filter_Field
{
	protected $_options = array();
	protected $_separator = ' ';
	protected $_label_first = FALSE;

	public function setOptions(array $options)
	{
		$this->_options = $options;
		return $this;
	}

	public function getOptions()
	{
		return $this->_options;
	}

	public function setSeparator($separator)
	{
		$this->_separator = (string) $separator;
		return $this;
	}

	public function setLabelFirst($val)
	{
		$this->_label_first = (bool) $val;
		return $this;
	}

	public function render()
	{
		if( !$this->count() ) return '';
		$out = '';

		foreach( $this->_options as $key => $option )
		{
			if( $this->_label_first )
			{
				$out.= $this->renderCheckboxLabel($key, $option);
				$out.= $this->renderCheckboxField($key);
				$out.= $this->_separator.PHP_EOL;
			}
			else
			{
				$out.= $this->renderCheckboxField($key);
				$out.= $this->renderCheckboxLabel($key, $option);
				$out.= $this->_separator.PHP_EOL;
			}
		}

		return  $out;
	}

	public function renderLabel()
	{
		if( !$this->count() ) return '';
		return $this->_label;
	}

	public function renderCheckboxField($key)
	{
		$id = $this->getId().'_'.$key;
		$name = $this->getFullName().'[]';
		$value = ($this->_value===null)? $this->_default : $this->_value;
		$value = (array) $value;
		$check = in_array($key, $value)? ' checked' : '';
		return '<input type="checkbox" name="'.$name.'" id="'.$id.'"'.$check.' value="'.$key.'" />';
	}

	public function renderCheckboxLabel($key, $option)
	{
		$id = $this->getId().'_'.$key;
		return '<label for="'.$id.'">'.$option.'</label>';
	}

	public function displayCheckboxField($key)
	{
		echo $this->renderCheckboxField($key);
	}

	public function displayCheckboxLabel($key, $option)
	{
		echo $this->renderCheckboxLabel($key, $option);
	}

	public function getRequestString()
	{
		$values = $this->getValue();
		$out = '';
		if( is_array($values) )
		{
			foreach($values as $key => $value)
			{
				$out.= '&'.$this->getFullName().'[]='.$value;
			}
		}
		return $out;
	}

	public function count()
	{
		return count($this->_options);
	}
}

class SP_Form_Filter_CheckboxList extends SP_Form_Filter_CheckboxSet
{
	protected $_layout_class;
	protected $_element_class;
	protected $_label_class;
	protected $_layout_tag = 'ul';
	protected $_element_tag = 'li';
	protected $_add_hiding_js = TRUE;
	protected $_is_drop_down = TRUE;

	public function addHidingJs($val)
	{
		$this->_add_hiding_js = (bool) $val;
		return $this;
	}

	public function isDropDown($val)
	{
		$this->_is_drop_down = (bool) $val;
		$this->addHidingJs(FALSE);
		return $this;
	}

	public function setLayoutClass($val)
	{
		$this->_layout_class = $val;
		return $this;
	}

	public function getLayoutClass()
	{
		if( $this->_layout_class ) return ' class="'.$this->_layout_class.'"';
	}

	public function setLayoutTag($val)
	{
		$this->_layout_tag = $val;
		return $this;
	}

	public function setLabelClass($val)
	{
		$this->_label_class = $val;
		return $this;
	}

	public function getLabelClass()
	{
		if( $this->_label_class ) return ' class="'.$this->_label_class.'"';
	}

	public function setElementClass($val)
	{
		$this->_element_class = $val;
		return $this;
	}

	public function getElementClass()
	{
		if( $this->_element_class ) return ' class="'.$this->_element_class.'"';
	}

	public function setElementTag($val)
	{
		$this->_element_tag = $val;
		return $this;
	}

	public function render()
	{
		if( !$this->count() ) return '';

		$out = '';

		$out.= '<'.$this->_layout_tag.$this->getLayoutClass().' id="'.$this->getId().'">'.PHP_EOL;
		foreach( $this->_options as $key => $option )
		{
			$out.= '<'.$this->_element_tag.$this->getElementClass().'>';
			if( $this->_label_first )
			{
				$out.= $this->renderCheckboxLabel($key, $option);
				$out.= $this->renderCheckboxField($key);
				$out.= $this->_separator;
			}
			else
			{
				$out.= $this->renderCheckboxField($key);
				$out.= $this->renderCheckboxLabel($key, $option);
				$out.= $this->_separator;
			}
			$out.= '</'.$this->_element_tag.'>'.PHP_EOL;
		}
		$out.= '</'.$this->_layout_tag.'>';
		if( $this->_add_hiding_js ) $out.= '<script type="text/javascript">el=document.getElementById(\''.$this->getId().'\');el.style.display = (el.style.display == \'none\') ? \'\' : \'none\';</script>'.PHP_EOL;
		return  $out;
	}

	public function renderLabel()
	{
		if( !$this->count() ) return '';
		if( !$this->_is_drop_down ) return $this->_label;
		return '<a'.$this->getLabelClass().' href="#" onClick="el=document.getElementById(\''.$this->getId().'\');el.style.display = (el.style.display == \'none\') ? \'\' : \'none\';return false;">'.$this->_label.'</a>'.PHP_EOL;
	}
}

// FORMS
class SP_Form_Filter_SimpleForm extends SP_Form_Filter_Form
{
	public function render()
	{
		//$out = $this->renderOpenTag();
		$out = "\r\n";
		foreach($this->_fields as $field)
		{
			$out.= '<div>'.$field->renderLabel().$field->render().'</div>'."\r\n";
		}
		//$out.= $this->renderCloseTag();
		return $out;
	}

}

// DOMSTOR FIELDS
class DomstorDistrictField extends SP_Form_Filter_CheckboxList
{
	protected $_sublayout_class;
	protected $_subelement_class;

	public function __construct()
	{

	}

	public function setSublayoutClass($val)
	{
		$this->_sublayout_class = $val;
		return $this;
	}

	public function getSublayoutClass()
	{
		if( $this->_sublayout_class ) return ' class="'.$this->_sublayout_class.'"';
	}

	public function setSubelementClass($val)
	{
		$this->_subelement_class = $val;
		return $this;
	}

	public function getSubelementClass()
	{
		if( $this->_subelement_class ) return ' class="'.$this->_subelement_class.'"';
	}

	public function render()
	{
		if( !$this->count() ) return '';

		$out = '<'.$this->_layout_tag.$this->getLayoutClass().' id="'.$this->getId().'" >'.PHP_EOL;
		foreach( $this->_options as $key => $option )
		{
			$out.= '<'.$this->_element_tag.$this->getElementClass().'>';

			$out.= $this->renderCheckboxField($key, $option);

			$out.= '</'.$this->_element_tag.'>'.PHP_EOL;
		}
		$out.= '</'.$this->_layout_tag.'>';
		if( $this->_add_hiding_js ) $out.= '<script type="text/javascript">el=document.getElementById(\''.$this->getId().'\');el.style.display = (el.style.display == \'none\') ? \'\' : \'none\';</script>'.PHP_EOL;
		return  $out;
	}

	public function renderCheckboxField($key, $option)
	{
		$out = parent::renderCheckboxField($key);
		$district = $this->_options[$key];
		if( count($district['Subdistricts']) )
		{
			$out.= $this->renderCheckboxLinkedLabel($key, $option);
			$out.= '<'.$this->_layout_tag.$this->getLayoutClass().' id="'.$this->getId().'_'.$key.'_fields">'.PHP_EOL;
			foreach( $district['Subdistricts'] as $subdistrict )
			{
				$out.= '<'.$this->_element_tag.$this->getElementClass().'>';
				if( $this->_label_first )
				{
                    $out.= $this->renderCheckboxSubLabel($subdistrict);
                    $out.= $this->renderCheckboxSubField($subdistrict);
					$out.= $this->_separator;
				}
				else
				{
					$out.= $this->renderCheckboxSubField($subdistrict);
					$out.= $this->renderCheckboxSubLabel($subdistrict);
					$out.= $this->_separator;
				}
				$out.= '</'.$this->_element_tag.'>'.PHP_EOL;
			}
			$out.= '</'.$this->_layout_tag.'>'.PHP_EOL;
			if( $this->_add_hiding_js ) $out.= '<script type="text/javascript">el=document.getElementById(\''.$this->getId().'_'.$key.'_fields\');el.style.display = (el.style.display == \'none\') ? \'\' : \'none\';</script>'.PHP_EOL;
		}
		else
		{
			$out.= $this->renderCheckboxLabel($key, $option);
		}
		return $out;
	}

   	public function renderCheckboxSubField($subdistrict)
	{
		return parent::renderCheckboxField($subdistrict['id']);
	}

	public function renderCheckboxSubLabel($subdistrict)
	{
		return parent::renderCheckboxLabel($subdistrict['id'], $subdistrict['name']);
	}

	public function renderCheckboxLabel($key, $option)
	{
		$option = $option['name'];
		return parent::renderCheckboxLabel($key, $option);
	}

	public function renderCheckboxLinkedLabel($key, $option)
	{
		$option = $option['name'];
		$id = $this->getId().'_'.$key.'_fields';
		if( !$this->_is_drop_down ) return '<label for="'.$this->getId().'_'.$key.'">'.$option.'</label>';
		return '<a title="Показать подрайоны" href="#" onClick="el=document.getElementById(\''.$id.'\');el.style.display = (el.style.display == \'none\') ? \'\' : \'none\';return false;">'.$option.'</a>'.PHP_EOL;
	}
}

// DOMSTOR FORMS
class DomstorFilterForm extends SP_Form_Filter_Form
{
	// Ссылка на строитель форм
	protected $_builder;

	// Ссылка на загрузчик данных
	protected $_data_loader;

	public function setDataLoader(Domstor_Filter_DataLoader $loader)
	{
		$this->_data_loader = $loader;
		return $this;
	}

	public function getDataLoader()
	{
		return $this->_data_loader;
	}

	public function getBuilder()
	{
		return $this->_builder;
	}

	public function setBuilder(DomstorCommonBuilder $builder)
	{
		$this->_builder = $builder;
		return $this;
	}

	public function renderHidden()
	{
		$get_array = array('object', 'action', 'inreg', 'ref_city');
		$out = '';
		// $out = '<input type="hidden" name="filter" value="" />'.PHP_EOL;
		if( is_array($_GET) )
		{
			foreach($_GET as $key => $value)
			{
				if( strpos($key, 'sort-')!==false or in_array($key, $get_array) )
				{
					$out.= '<input type="hidden" name="'.$key.'" value="'.$value.'" />'.PHP_EOL;
				}
			}
		}
		return $out;
	}

	public function displayHidden()
	{
		echo $this->renderHidden();
	}

	public function displayFieldLabel($name, $separator = ' ')
	{
		$this->displayField($name);
		echo $separator;
		$this->displayLabel($name);
	}

	public function displayLabelField($name, $separator = ' ')
	{
		$this->displayLabel($name);
		echo $separator;
		$this->displayField($name);
	}
}
