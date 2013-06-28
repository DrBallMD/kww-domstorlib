<?php

/**
 * Description of DemandFloor
 *
 * @author pahhan
 */
class Domstor_List_Field_Flat_DemandFloor extends Domstor_List_Field_Common
{
	public function getValue()
	{
		$a=$this->getRow();
		$floor=array();
		if( $a['object_floor'] ) $floor[]='�� ���� '.$a['object_floor'].' �����';
		if( $a['object_floor_limit'] ) $floor[]=$a['object_floor_limit'];
		$out=implode(', ', $floor);
		return $out;
	}
}

