<?php
/* Author: Pavel Stepanets
 * Email:  pahhan.ne@gmail.com
 */




// FIELDS




class SP_Form_Filter_SubmitLink extends SP_Form_AbstractField
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

class SP_Form_Filter_Radio extends SP_Form_AbstractField
{
	public function render()
	{
		$value = ($this->_value===null)? $this->_default : $this->_value;
		$check = $value? ' checked' : '';
		return '<input type="radio" name="'.$this->getFullName().'" id="'.$this->getId().'"'.$check.' value="'.$value.'" />';
	}
}

class SP_Form_Filter_RadioSet extends SP_Form_AbstractField
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

class SP_Form_Filter_Checkbox extends SP_Form_AbstractField
{

	public function render()
	{
		$value = ($this->_value===null)? $this->_default : $this->_value;
		$check = $value? ' checked' : '';
		return '<input type="checkbox" name="'.$this->getFullName().'" id="'.$this->getId().'"'.$check.' value="1" />';
	}

}

class SP_Form_Filter_CheckboxSet extends SP_Form_AbstractField
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
class SP_Form_Filter_SimpleForm extends SP_Form_Form
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