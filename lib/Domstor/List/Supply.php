<?php

/**
 * Description of Supply
 *
 * @author pahhan
 */
class Domstor_List_Supply extends Domstor_List_Common
{
	public function checkThumb()
	{
		foreach( $this->data as $a )
		{
			if( isset($a['thumb']) ) return TRUE;
		}
		return FALSE;
	}

	public function __construct($attr)
	{
		parent::__construct($attr);

		$thumb_field = new Domstor_List_Field_Thumb( array(
				'name'=>'thumb',
				'title'=>'����',
				'css_class'=>'domstor_thumb',
				'position'=>25,
				'object_href'=>$this->object_href,
		));

		$price_field = new Domstor_List_Field_Price( array(
				'name'=>'price',
				'css_class'=>'domstor_price',
				'action'=>$this->action,
				'sort_name'=>'sort-price',
				'position'=>260,
		));

        $district_field = new Domstor_List_Field_Common( array(
				'name'=>'district',
				'title'=>'�����',
				'css_class'=>'domstor_district',
				'position'=>200,
				'sort_name'=>'sort-district',
                'transformer' => $this->in_region?
                    new Domstor_Transformer_Supply_RegionDistrict() :
                    new Domstor_Transformer_Supply_CityDistrict(),
		));

        $address_field = new Domstor_List_Field_Common( array(
				'name'=>'address',
				'title'=>'�����',
				'css_class'=>'domstor_address',
				'position'=>230,
				'sort_name'=>'sort-street',
                'transformer' => $this->in_region?
                    new Domstor_Transformer_Supply_RegionAddress() :
                    new Domstor_Transformer_Supply_CityAddress(),
		));

		$this->addField($price_field)
			 ->addField($address_field)
             ->addField($district_field)
		;
		if( $this->checkThumb() ) $this->addField($thumb_field);
		if( $this->action=='rent' )$this->getField('price')->setSortName('sort-rent');
	}
}