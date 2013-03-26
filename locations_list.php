<?php
class DomstorLocationsList
{
	protected $_data = array();
	protected $_href_tmpl = '';
	protected $_home_id;
	protected $_prefix = 'Недвижимость ';
	
	public function __construct(array $data, $href_tmpl, $home_id)
	{
		$this->_data = $data;
		$this->_href_tmpl = $href_tmpl;
		$this->_home_id = $home_id;
	}
	
	public function getArray()
	{
		$out = array();
		foreach($this->_data as $data)
		{
			$out[$data['id']]['name'] = $data['name'];
			$out[$data['id']]['uri_part'] = '&ref_city='.$data['id'];
			if( $in_region = ($data['type'] == 2) ) $out[$data['id']]['uri_part'].= '&inreg';
			$out[$data['id']]['is_region'] = $in_region;
		}
		return $out;
	}
	
	public function render($prefix = NULL)
	{
		if( !$prefix ) $prefix = $this->_prefix;
		$out = '';
		
		foreach($this->_data as $data)
		{
			if( $data['id'] == $this->_home_id )
			{
				$href = str_replace('&ref_city=%id', '', $this->_href_tmpl);
			}
			else
			{
				$href = str_replace('%id', $data['id'], $this->_href_tmpl);
			}
			if( $data['type']==2 ) $href.= '&inreg';
			$out.= '<p><a href="'.$href.'">'.$prefix.' '.$data['name'].'</a></p>'.PHP_EOL;
		}
		
		if( $out ) $out = '<div class="domstor_locations_list">'.PHP_EOL.$out.'</div>';
		
		return $out;
	}
	
	public function display($prefix = NULL)
	{
		echo $this->render($prefix);
	}
}