<?php

/**
 * Description of DemandAddress
 *
 * @author pahhan
 */
class Domstor_List_Field_Commerce_DemandAddress extends Domstor_List_Field_Common
{
	protected $in_region;
	protected $object_href;

	public function getValue()
	{
		$a=$this->getTable()->getRow();
		if( $this->in_region )
		{
			$out.=$this->getIf($a['address_note'], '', ', ');
			$out.=$this->getIf($a['city'], '', ', ');
			$out=substr($out, 0, -2);
		}
		else
		{
			if( isset($a['district']) and $a['district'] ) $out='������: '.$a['district'].'; ';
			if( isset($a['street']) and $a['street'] ) $out.='�����: '.$a['street'].'; ';
			$out=substr($out, 0, -2);
		}

		if( $out )
		{
			$href=str_replace('%id', $a['id'], $this->object_href);
			$out='<a href="'.$href.'" title="������� �� �������� ������ '.$a['code'].'" class="domstor_link">'.$out.'</a>';
		}
		return $out;
	}
}

