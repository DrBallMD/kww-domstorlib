<?php

/**
 * Description of SquareGround
 *
 * @author pahhan
 */
class Domstor_List_Field_Commerce_SquareGround extends Domstor_List_Field_Common
{
	public function getValue()
	{
		$a=$this->getRow();
		$a['square_ground_min'] = $a['square_ground_min']? str_replace('.', ',', $a['square_ground_min']) : NULL;
		$a['square_ground_max'] = $a['square_ground_max']? str_replace('.', ',', $a['square_ground_max']) : NULL;
		if( $a['square_ground_unit_id']==1177 ) $a['square_ground_unit']='кв.м';
		elseif( $a['square_ground_unit'] == 'Гектар' ) $a['square_ground_unit']='Га';
		$out=$this->getFromTo($a['square_ground_min'], $a['square_ground_max'], ' '.$a['square_ground_unit'], '', true);
		return $out;
	}
}

