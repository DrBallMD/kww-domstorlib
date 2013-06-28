<?php

/**
 * Description of DetailFactory
 *
 * @author pahhan
 */
class Domstor_Detail_DetailFactory
{
	/**
     *
     * @param string $object
     * @param string $action
     * @param array $params
     * @return boolean|DomstorCommonObject
     */
    public function create($object, $action, array $params)
	{
		if( !Domstor_Helper::checkEstateAction( $object, $action) ) return FALSE;

		$demand = ($action=='purchase' or $action=='rentuse')? 'Demand' : '';

		$commerce = array(
			'trade',
			'office',
			'product',
			'storehouse',
			'landcom',
			'other',
		);
		if( in_array($object, $commerce) ) $object = 'Commerce';

		$class = 'Domstor'.$object.$demand;

		$obj = new $class($params);
		return $obj;
	}
}