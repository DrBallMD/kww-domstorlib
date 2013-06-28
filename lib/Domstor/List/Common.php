<?php

/**
 * Description of Common
 *
 * @author pahhan
 */
class Domstor_List_Common extends HtmlTable
{
	protected $in_region;
	protected $object;
	protected $action;
	protected $pagination;
	protected $object_href;
	protected $filter;
	protected $_show_filter = FALSE;
	protected $empty_list_message = '������ ����';
	protected $city_id;

	public function cityId($val = NULL)
	{
		if( is_null($val) ) return $this->city_id;

		$this->city_id = $val;
		return $this;
	}

    public function getObjectHref()
    {
        return $this->object_href;
    }

	public function inRegion($val)
	{
		$this->in_region=(bool)$val;
	}

	public function isInRegion()
	{
		return $this->in_region;
	}

	public function getServerName()
	{
		return $this->server_name;
	}

	public function setPagination($value)
	{
		$this->pagination=$value;
	}

	public function getPagination()
	{
		return $this->pagination;
	}

	public function setPager($pager)
	{
		$this->pager = $pager;
	}

	public function getPager()
	{
		return $this->pager;
	}

	public function setFilter($value)
	{
		$this->filter=$value;
	}

	public function getFilter()
	{
		return $this->filter;
	}

	public function showFilter($val)
	{
		$this->_show_filter = (bool) $val;
		return $this;
	}

	public function getEmptyListMessage()
	{
		return $this->empty_list_message;
	}

	public function display()
    {
        echo $this->getHtml();
    }

    public function getHtml()
	{
		$out = '';
		if( $this->_show_filter ) $out.= (string) $this->getFilter();
		if( count($this->data) )
		{
			$out.= parent::getHtml();
			$out.= $this->getPagination();
		}
		else
		{
			$out.= $this->empty_list_message;
		}
		return $out;
	}

	public function __construct($attr)
	{
		parent::__construct($attr);
		//print_r($this->data);
		$this->css_class='domstor_table';

		$code_field = new Domstor_List_Field_Code( array(
				'name'=>'code',
				'title'=>'���',
				'css_class'=>'domstor_code',
				'position'=>0,
				'object_href'=>$this->object_href,
				'sort_name'=>'sort-code',
		));

		$type_field = new HtmlTableField( array(
			'name'=>'type',
			'title'=>'���',
			'css_class'=>'domstor_type',
			'position'=>100,
			'sort_name'=>'sort-type',
		) );

		$contact_field = new Domstor_List_Field_Contact( array(
				'name'=>'contact_phone',
				'title'=>'���������� �������',
				'css_class'=>'domstor_contact',
				'position'=>'last',
				'position'=>300,
		));

		$note_web_field = new Domstor_List_Field_Comment( array(
				'name'=>'note_web',
				'title'=>'�����������',
				'css_class'=>'domstor_note_web',
				'position'=>'last',
				'position'=>400,
		));

		$this->addField($code_field)
			 ->addField($type_field)
			 //->addField($district_field)
			 ->addField($contact_field)
			 ->addField($note_web_field)
		;
	}
}