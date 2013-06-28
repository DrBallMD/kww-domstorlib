<?php

/**
 * Description of Purchase
 *
 * @author pahhan
 */
class Domstor_List_Garage_Purchase extends Domstor_List_Demand
{
	public function __construct($attr)
	{
		parent::__construct($attr);

		$this->getField('type')->setTitle('��� ������');

		$cellar_field = new HtmlTableField( array(
			'name'=>'cellar_want',
			'title'=>'������� �������',
			'css_class'=>'domstor_cellar',
			'position'=>234
		) );

		$this->addField($cellar_field)
		;

	}
}
