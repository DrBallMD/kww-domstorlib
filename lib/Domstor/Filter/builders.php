<?php
class DomstorCommonBuilder
{
	protected $_form;
	protected $_domstor;
	protected $_object;
	protected $_action;

	public function __construct()
	{
		$this->_form = new DomstorFilterForm;
		$this->_form->setBuilder($this);
		$data_loader = new DomstorFilterDataLoader($this->_form);

		// ���������� ������ �������� �����
		DomstorSubmitConstructor::add($this->_form);
		$code = new SP_Form_Filter_InputText;
		$code->setName('code')->setLabel('��� �������');
		$this->_form->addField($code);
	}

	public function setDomstor($val)
	{
		$this->_domstor = $val;
		$this->_form->getDataLoader()->setConfig($this->_domstor->getFilterDataLoaderConfig());
		return $this;
	}

	public function getDomstor()
	{
		return $this->_domstor;
	}

	public function setObject($val)
	{
		$this->_object = $val;
		return $this;
	}

	public function getObject()
	{
		return $this->_object;
	}

	public function setAction($val)
	{
		$this->_action = $val;
		return $this;

	}

	public function getAction()
	{
		return $this->_action ;
	}
}

class DomstorPriceConstructor
{
	public static function add($form)
	{
		/* $price_min_field = new SP_Form_Filter_InputText;
		$price_min_field->setName('price_min')->setLabel('��');

		$price_max_field = new SP_Form_Filter_InputText;
		$price_max_field->setName('price_max')->setLabel('��');

		$form->addFields(array(
			$price_min_field,
			$price_max_field,
		)); */

		$price_form = new DomstorPriceForm;
		$form->addField($price_form);

		return $form;
	}
}

class DomstorRentForm extends DomstorFilterForm
{
	public function __construct()
	{
		$this->setName('rent');

		$min = new SP_Form_Filter_InputText;
		$min->setName('min')->setLabel('��');

		$max = new SP_Form_Filter_InputText;
		$max->setName('max')->setLabel('��');

		$period = new SP_Form_Filter_Select;
		$period->setName('period')->setOptions(array('1'=>'� �����', '12'=>'� ���'));

		$this->addFields(array(
			$min,
			$max,
			$period,
		));
	}

	public function getServerRequestString()
	{
		$values = $this->getValue();
		$out = '';
		$coef = (float) $values['period'];
		if( $values['min'] ) $out.= '&rent_min='.$values['min']/$coef;
		if( $values['max'] ) $out.= '&rent_max='.$values['max']/$coef;

		return $out;
	}
}

class DomstorPriceForm extends DomstorFilterForm
{
	public function __construct()
	{
		$this->setName('price');

		$min = new SP_Form_Filter_InputText;
		$min->setName('min')->setLabel('��');

		$max = new SP_Form_Filter_InputText;
		$max->setName('max')->setLabel('��');

		$this->addFields(array(
			$min,
			$max,
		));
	}

	public function getServerRequestString()
	{
		$values = $this->getValue();
		$out = '';
		if( $values['min'] ) $out.= '&price_min='.$values['min'] * 1000;
		if( $values['max'] ) $out.= '&price_max='.$values['max'] * 1000;

		return $out;
	}
}

class DomstorRentLivingConstructor
{
	public static function add($form)
	{
		$rent_form = new DomstorRentForm;
		$rent_form->getField('period')->addOptions(array('0.033'=>'� �����'));
		$form->addField($rent_form);
		return $form;
	}
}

class DomstorSubmitConstructor
{
	public static function add($form)
	{
		$submit_field = new SP_Form_Filter_Submit;
		$submit_field->setLabel('�����');

		$submitlink_field = new SP_Form_Filter_SubmitLink;
		$submitlink_field->setLabel('�����');

		$form->addFields(array(
			$submit_field,
			$submitlink_field,
		));
		return $form;
	}
}

class DomstorSuburbanConstructor
{
    public static function add($form, $domstor)
	{
        $field = new SP_Form_Filter_CheckboxList;
        $options = $form->getDataLoader()->getSuburbans();
        $field->setName('suburban')
				->setLabel('��������')
				->setOptions($options)
				->isDropDown(FALSE)
			;
        $form->addField($field);
    }
}

class DomstorLocationsConstructor
{
    public static function add($form, $domstor)
	{
		if( $domstor->inRegion() ) // ���� ������ � �������
		{

        }
    }
}

class DomstorDistrictConstructor
{
	public static function add($form, $domstor)
	{
		if( $domstor->inRegion() ) // ���� ������ � �������
		{
			// ������ � ������ ������ �������
			$district = new SP_Form_Filter_CheckboxList;
			$options = $form->getDataLoader()->getSubregions();//$domstor->read($url);
			$district->setName('subregion')
				->setLabel('����� / ����������&nbsp;�����')
				->setOptions($options)
				->isDropDown(FALSE)
			;
		}
		else
		{
			// ������ ������
			$district = new DomstorDistrictField;
			$options = $domstor->read('/gateway/location/district?ref_city='.$domstor->getRealParam('ref_city'));
			$district->setName('district')
				->setLabel('�����')
				->setOptions($options)
				->isDropDown(FALSE)
			;
		}

		$form->addField($district, 'district');
		return $form;
	}
}

class DomstorRoomCountConstructor
{
	public static function add($form)
	{
		// ����� ������
		$room_count = new SP_Form_Filter_CheckboxSet;
		$room_count->setOptions(array(1=>'1', 2=>'2', 3=>'3', 4=>'4', 5=>'5 � �����'));
		$room_count->setName('room_count')->setLabel('����� ������: ');
		$form->addField($room_count);
		return $form;
	}
}

class DomstorSquareHouseConstructor
{
    public static function add($form)
    {
        // ������� ���������
        $square_min_field = new SP_Form_Filter_InputText;
        $square_min_field->setName('squareh_min')->setLabel('��');

        $square_max_field = new SP_Form_Filter_InputText;
        $square_max_field->setName('squareh_max')->setLabel('��');

        $form->addFields(array(
            $square_min_field,
            $square_max_field,
        ));
        return $form;
    }
}

class DomstorSquareGroundForm extends DomstorFilterForm
{

    public function __construct()
    {
        $this->setName('squareg');

        $min = new SP_Form_Filter_InputText;
        $min->setName('min')->setLabel('��');

        $max = new SP_Form_Filter_InputText;
        $max->setName('max')->setLabel('��');

        $unit = new SP_Form_Filter_Select;
        $unit->setName('unit')->setOptions(array('100'=>'���.', '10000'=>'��'));

        $this->addFields(array(
            $min,
            $max,
            $unit,
        ));
    }

    public function getServerRequestString()
    {
        $values = $this->getValue();
        $out = '';
        $coef = (float) $values['unit'];
        if( $values['min'] ) $out.= '&squareg_min='.$values['min']*$coef;
        if( $values['max'] ) $out.= '&squareg_max='.$values['max']*$coef;

        return $out;
    }
}

class DomstorSquareGroundConstructor
{
    public static function add($form)
    {
        $sq_form = new DomstorSquareGroundForm;
        $form->addField($sq_form);
        return $form;
    }
}


// FLAT
// 		Sale
class DomstorFlatSaleFilterBuilder extends DomstorCommonBuilder
{
	public function buildFilter()
	{
		// ���������� ����� ���� �������
		DomstorPriceConstructor::add($this->_form);

		// ���������� ���� ������ (������� �� ���� ��������������)
		DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if( !$this->_domstor->inRegion() ) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

		// ����� ������
		DomstorRoomCountConstructor::add($this->_form);

        // � ���������� ��� ���
		$in_communal = new SP_Form_Filter_CheckBox;
		$in_communal
			->setName('in_communal')
			->setLabel('������� � ����������')
		;

		// ��� �����
		$floor_type = new SP_Form_Filter_Select;
		$floor_type->setOptions(array(
			''=>'�����',
			'first'=>'������ ������',
			'last'=>'������ ���������',
			'not_first'=>'����� �������',
			'not_last'=>'����� ����������',
			'not_first_last'=>'����� ������� � ����������'
		));
		$floor_type->setName('floor_type')->setLabel('����');

		// ������������ ����
		$max_floor = new SP_Form_Filter_Select;
		$max_floor->setName('max_floor')->setLabel('�� ����')->setRange(2, 20, array(''=>''));

		// ����������� ��� ���
		$new_building = new SP_Form_Filter_RadioSet;
		$new_building
			->setName('new_building')
			->setLabel('')
			->setOptions(array(''=>'��� �����������', '0'=>'��������� �����', '1'=>'�����������'))
			->setLabelFirst(0)
			->setSeparator('<br />')
			->setDefault('')
		;

		// ��� ��������
		$type = new SP_Form_Filter_CheckboxList;
		$options = $this->_domstor->read('/gateway/type?object='.$this->_object.'&ref_city='.$this->_domstor->getRealParam('ref_city'));
		$type->setName('type')
			->setLabel('��� ��������')
			->setOptions($options)
			->isDropDown(FALSE)
		;

		// ���������� ����� � �����
		$this->_form
			->addField($in_communal)
			->addField($floor_type)
			->addField($max_floor)
			->addField($new_building)
			->addField($type)
		;

		return $this->_form;
	}
}

// 		Rent
class DomstorFlatRentFilterBuilder extends DomstorFlatSaleFilterBuilder
{
	public function buildFilter()
	{
		$filter = parent::buildFilter();
		// �������� ����� �� ������ ��� ������
		$filter->deleteField('price');
		$filter->deleteField('new_building');
		// ���������� ����� ��������� ������ �������
		DomstorRentLivingConstructor::add($filter);

		return $filter;
	}
}

// 		Purchase
class DomstorFlatPurchaseFilterBuilder extends DomstorCommonBuilder
{
	public function buildFilter()
	{
		// ���������� ����� ���� �������
		DomstorPriceConstructor::add($this->_form);

		// ���������� ���� ������ (������� �� ���� ��������������)
		DomstorDistrictConstructor::add($this->_form, $this->_domstor);

		// ����� ������
		DomstorRoomCountConstructor::add($this->_form);

        // � ���������� ��� ���
		$in_communal = new SP_Form_Filter_CheckBox;
		$in_communal
			->setName('in_communal')
			->setLabel('������� � ����������')
		;

		// ��� ��������
		$type = new SP_Form_Filter_CheckboxList;
		$options = $this->_domstor->read('/gateway/type?object='.$this->_object.'&ref_city='.$this->_domstor->getRealParam('ref_city'));
		$type->setName('type')
			->setLabel('��� ��������')
			->setOptions($options)
			->isDropDown(FALSE)
		;

		// ���������� ����� � �����
		$this->_form
			->addField($in_communal)
			->addField($type)
		;

		return $this->_form;
	}
}

// 		Rentuse
class DomstorFlatRentuseFilterBuilder extends DomstorCommonBuilder
{
	public function buildFilter()
	{
		// ���������� ����� ���� �������
		DomstorRentLivingConstructor::add($this->_form);

		// ���������� ���� ������ (������� �� ���� ��������������)
		DomstorDistrictConstructor::add($this->_form, $this->_domstor);

		// ����� ������
		DomstorRoomCountConstructor::add($this->_form);

        // � ���������� ��� ���
		$in_communal = new SP_Form_Filter_CheckBox;
		$in_communal
			->setName('in_communal')
			->setLabel('������� � ����������')
		;

		// ��� ��������
		$type = new SP_Form_Filter_CheckboxList;
		$options = $this->_domstor->read('/gateway/type?object='.$this->_object.'&ref_city='.$this->_domstor->getRealParam('ref_city'));
		$type->setName('type')
			->setLabel('��� ��������')
			->setOptions($options)
			->isDropDown(FALSE)
		;

		// ���������� ����� � �����
		$this->_form
			->addField($in_communal)
			->addField($type)
		;

		return $this->_form;
	}
}

// 		Exchange
class DomstorFlatExchangeFilterBuilder extends DomstorCommonBuilder
{
	public function buildFilter()
	{
		// ���������� ����� ���� �������
		DomstorPriceConstructor::add($this->_form);

		// ���������� ���� ������ (������� �� ���� ��������������)
		DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if( !$this->_domstor->inRegion() ) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

		// ����� ������
		DomstorRoomCountConstructor::add($this->_form);

        // � ���������� ��� ���
		$in_communal = new SP_Form_Filter_CheckBox;
		$in_communal
			->setName('in_communal')
			->setLabel('������� � ����������')
		;

		// ��� �����
		$floor_type = new SP_Form_Filter_Select;
		$floor_type->setOptions(array(
			''=>'�����',
			'first'=>'������ ������',
			'last'=>'������ ���������',
			'not_first'=>'����� �������',
			'not_last'=>'����� ����������',
			'not_first_last'=>'����� ������� � ����������'
		));
		$floor_type->setName('floor_type')->setLabel('����');

		// ������������ ����
		$max_floor = new SP_Form_Filter_Select;
		$max_floor->setName('max_floor')->setLabel('�� ����')->setRange(2, 20, array(''=>''));

		// ��� ��������
		$type = new SP_Form_Filter_CheckboxList;
		$options = $this->_domstor->read('/gateway/type?object='.$this->_object.'&ref_city='.$this->_domstor->getRealParam('ref_city'));
		$type->setName('type')
			->setLabel('��� ��������')
			->setOptions($options)
			->isDropDown(FALSE)
		;

		// ���������� ����� � �����
		$this->_form
			->addField($in_communal)
			->addField($floor_type)
			->addField($max_floor)
			->addField($type)
		;

		return $this->_form;
	}
}

// 		New
class DomstorFlatNewFilterBuilder extends DomstorCommonBuilder
{
	public function buildFilter()
	{
		// ���������� ����� ���� �������
		DomstorPriceConstructor::add($this->_form);

		// ���������� ���� ������ (������� �� ���� ��������������)
		DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if( !$this->_domstor->inRegion() ) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

		// ����� ������
		DomstorRoomCountConstructor::add($this->_form);

		// ��� �����
		$floor_type = new SP_Form_Filter_Select;
		$floor_type->setOptions(array(
			''=>'�����',
			'first'=>'������ ������',
			'last'=>'������ ���������',
			'not_first'=>'����� �������',
			'not_last'=>'����� ����������',
			'not_first_last'=>'����� ������� � ����������'
		));
		$floor_type->setName('floor_type')->setLabel('����');

		// ������������ ����
		$max_floor = new SP_Form_Filter_Select;
		$max_floor->setName('max_floor')->setLabel('�� ����')->setRange(2, 20, array(''=>''));

		// ��� ��������
		$type = new SP_Form_Filter_CheckboxList;
		$options = $this->_domstor->read('/gateway/type?object='.$this->_object.'&ref_city='.$this->_domstor->getRealParam('ref_city'));
		$type->setName('type')
			->setLabel('��� ��������')
			->setOptions($options)
			->isDropDown(FALSE)
		;

		// ���������� ����� � �����
		$this->_form
			->addField($floor_type)
			->addField($max_floor)
			->addField($type)
		;

		return $this->_form;
	}
}

// HOUSE
// 		Sale
class DomstorHouseSaleFilterBuilder extends DomstorCommonBuilder
{
    public function buildFilter()
    {
        // ���������� ����� ���� �������
        DomstorPriceConstructor::add($this->_form);

        // ���������� ���� ������ (������� �� ���� ��������������)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if( !$this->_domstor->inRegion() ) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

        // ������� ���������
        DomstorSquareHouseConstructor::add($this->_form);

        // ������� �������
        DomstorSquareGroundConstructor::add($this->_form);

        // ����� ������
        DomstorRoomCountConstructor::add($this->_form);

        // ��� ����
        $type = new SP_Form_Filter_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object='.$this->_object.'&ref_city='.$this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('��� ����')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
				->isDropDown(FALSE)
        ;

        // ���������� ����� � �����
        $this->_form
            ->addField($type)
        ;

        return $this->_form;
    }
}

// 		Rent
class DomstorHouseRentFilterBuilder extends DomstorHouseSaleFilterBuilder
{
	public function buildFilter()
	{
		$filter = parent::buildFilter();

		// �������� ����� �� ������ ��� ������
		$filter->deleteField('price');
		// ���������� ����� ��������� ������ �������
		DomstorRentLivingConstructor::add($filter);

		return $filter;
	}
}

// 		Purchase
class DomstorHousePurchaseFilterBuilder extends DomstorCommonBuilder
{
    public function buildFilter()
    {
        // ���������� ����� ���� �������
        DomstorPriceConstructor::add($this->_form);

        // ���������� ���� ������ (������� �� ���� ��������������)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        // ������� ���������
        DomstorSquareHouseConstructor::add($this->_form);

        // ������� �������
        DomstorSquareGroundConstructor::add($this->_form);

        // ����� ������
        DomstorRoomCountConstructor::add($this->_form);

        // ��� ����
        $type = new SP_Form_Filter_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object='.$this->_object.'&ref_city='.$this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('��� ����')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
				->isDropDown(FALSE)
        ;

        // ���������� ����� � �����
        $this->_form
            ->addField($type)
        ;

        return $this->_form;
    }
}

// 		Rentuse
class DomstorHouseRentuseFilterBuilder extends DomstorCommonBuilder
{
    public function buildFilter()
    {
        // ���������� ����� ���� �������
        DomstorRentLivingConstructor::add($this->_form);

        // ���������� ���� ������ (������� �� ���� ��������������)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        // ������� ���������
        DomstorSquareHouseConstructor::add($this->_form);

        // ����� ������
        DomstorRoomCountConstructor::add($this->_form);

        // ��� ����
        $type = new SP_Form_Filter_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object='.$this->_object.'&ref_city='.$this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('��� ����')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
				->isDropDown(FALSE)
        ;

        // ���������� ����� � �����
        $this->_form
            ->addField($type)
        ;

        return $this->_form;
    }
}

// 		Exchange
class DomstorHouseExchangeFilterBuilder extends DomstorCommonBuilder
{
    public function buildFilter()
    {
        // ���������� ����� ���� �������
        DomstorPriceConstructor::add($this->_form);

        // ���������� ���� ������ (������� �� ���� ��������������)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if( !$this->_domstor->inRegion() ) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

        // ������� ���������
        DomstorSquareHouseConstructor::add($this->_form);

        // ������� �������
        DomstorSquareGroundConstructor::add($this->_form);

        // ����� ������
        DomstorRoomCountConstructor::add($this->_form);

        // ��� ����
        $type = new SP_Form_Filter_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object='.$this->_object.'&ref_city='.$this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('��� ����')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
				->isDropDown(FALSE)
        ;

        // ���������� ����� � �����
        $this->_form
            ->addField($type)
        ;

        return $this->_form;
    }
}

// GARAGE
// 		Sale
class DomstorGarageSaleFilterBuilder extends DomstorCommonBuilder
{
    public function buildFilter()
    {
        // ���������� ����� ���� �������
        DomstorPriceConstructor::add($this->_form);

        // ���������� ���� ������ (������� �� ���� ��������������)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if( !$this->_domstor->inRegion() ) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

        // ��� ������
        $type = new SP_Form_Filter_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object='.$this->_object.'&ref_city='.$this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('��� ������')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
				->isDropDown(FALSE)
        ;

		// ������
        $x_min = new SP_Form_Filter_InputText;
        $x_min->setName('x_min')->setLabel('��');
        $x_max = new SP_Form_Filter_InputText;
        $x_max->setName('x_max')->setLabel('��');

		// �����
        $y_min = new SP_Form_Filter_InputText;
        $y_min->setName('y_min')->setLabel('��');
        $y_max = new SP_Form_Filter_InputText;
        $y_max->setName('y_max')->setLabel('��');

		// ������
        $z_min = new SP_Form_Filter_InputText;
        $z_min->setName('z_min')->setLabel('��');
        $z_max = new SP_Form_Filter_InputText;
        $z_max->setName('z_max')->setLabel('��');

        // ���������� ����� � �����
		$this->_form->addFields(array(
			$type,
			$x_min,
			$x_max,
			$y_min,
			$y_max,
			$z_min,
			$z_max,
        ));

        return $this->_form;
    }
}

// 		Rent
class DomstorGarageRentFilterBuilder extends DomstorGarageSaleFilterBuilder
{
	public function buildFilter()
	{
		$filter = parent::buildFilter();

		// �������� ����� �� ������ ��� ������
		$filter->deleteField('price');
		// ���������� ����� ��������� ������ �������
		DomstorRentLivingConstructor::add($filter);

		return $filter;
	}
}

// 		Purchase
class DomstorGaragePurchaseFilterBuilder extends DomstorCommonBuilder
{
    public function buildFilter()
    {
        // ���������� ����� ���� �������
        DomstorPriceConstructor::add($this->_form);

        // ���������� ���� ������ (������� �� ���� ��������������)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        // ��� ������
        $type = new SP_Form_Filter_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object='.$this->_object.'&ref_city='.$this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('��� ������')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
				->isDropDown(FALSE)
        ;

		// ������
        $x_min = new SP_Form_Filter_InputText;
        $x_min->setName('x_min')->setLabel('��');
        $x_max = new SP_Form_Filter_InputText;
        $x_max->setName('x_max')->setLabel('��');

		// �����
        $y_min = new SP_Form_Filter_InputText;
        $y_min->setName('y_min')->setLabel('��');
        $y_max = new SP_Form_Filter_InputText;
        $y_max->setName('y_max')->setLabel('��');

		// ������
        $z_min = new SP_Form_Filter_InputText;
        $z_min->setName('z_min')->setLabel('��');
        $z_max = new SP_Form_Filter_InputText;
        $z_max->setName('z_max')->setLabel('��');

        // ���������� ����� � �����
		$this->_form->addFields(array(
			$type,
			$x_min,
			$x_max,
			$y_min,
			$y_max,
			$z_min,
			$z_max,
        ));

        return $this->_form;
    }
}

// 		Rentuse
class DomstorGarageRentuseFilterBuilder extends DomstorCommonBuilder
{
    public function buildFilter()
    {
        // ���������� ����� ���� �������
        DomstorRentLivingConstructor::add($this->_form);

        // ���������� ���� ������ (������� �� ���� ��������������)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        // ��� ������
        $type = new SP_Form_Filter_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object='.$this->_object.'&ref_city='.$this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('��� ������')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
				->isDropDown(FALSE)
        ;

		// ������
        $x_min = new SP_Form_Filter_InputText;
        $x_min->setName('x_min')->setLabel('��');
        $x_max = new SP_Form_Filter_InputText;
        $x_max->setName('x_max')->setLabel('��');

		// �����
        $y_min = new SP_Form_Filter_InputText;
        $y_min->setName('y_min')->setLabel('��');
        $y_max = new SP_Form_Filter_InputText;
        $y_max->setName('y_max')->setLabel('��');

		// ������
        $z_min = new SP_Form_Filter_InputText;
        $z_min->setName('z_min')->setLabel('��');
        $z_max = new SP_Form_Filter_InputText;
        $z_max->setName('z_max')->setLabel('��');

        // ���������� ����� � �����
		$this->_form->addFields(array(
			$type,
			$x_min,
			$x_max,
			$y_min,
			$y_max,
			$z_min,
			$z_max,
        ));

        return $this->_form;
    }
}

// LAND
// 		Sale
class DomstorLandSaleFilterBuilder extends DomstorCommonBuilder
{
    public function buildFilter()
    {
        // ���������� ����� ���� �������
        DomstorPriceConstructor::add($this->_form);

        // ���������� ���� ������ (������� �� ���� ��������������)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if( !$this->_domstor->inRegion() ) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

        // ��� �������
        $type = new SP_Form_Filter_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object='.$this->_object.'&ref_city='.$this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('��� �������')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
				->isDropDown(FALSE)
        ;

		 // ������� �������
        DomstorSquareGroundConstructor::add($this->_form);

        // ���������� ����� � �����
		$this->_form->addFields(array(
			$type,
        ));

        return $this->_form;
    }
}

// 		Rent
class DomstorLandRentFilterBuilder extends DomstorLandSaleFilterBuilder
{
	public function buildFilter()
	{
		$filter = parent::buildFilter();

		// �������� ����� �� ������ ��� ������
		$filter->deleteField('price');
		// ���������� ����� ��������� ������ �������
		DomstorRentLivingConstructor::add($filter);

		return $filter;
	}
}

// 		Purchase
class DomstorLandPurchaseFilterBuilder extends DomstorCommonBuilder
{
    public function buildFilter()
    {
        // ���������� ����� ���� �������
        DomstorPriceConstructor::add($this->_form);

        // ���������� ���� ������ (������� �� ���� ��������������)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        // ��� �������
        $type = new SP_Form_Filter_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object='.$this->_object.'&ref_city='.$this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('��� �������')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
				->isDropDown(FALSE)
        ;

		 // ������� �������
        DomstorSquareGroundConstructor::add($this->_form);

        // ���������� ����� � �����
		$this->_form->addFields(array(
			$type,
        ));

        return $this->_form;
    }
}

// 		Rentuse
class DomstorLandRentuseFilterBuilder extends DomstorCommonBuilder
{
    public function buildFilter()
    {
        // ���������� ����� ���� �������
        DomstorRentLivingConstructor::add($this->_form);

        // ���������� ���� ������ (������� �� ���� ��������������)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);


        // ��� �������
        $type = new SP_Form_Filter_CheckboxList;
        $options = $this->_domstor->read('/gateway/type?object='.$this->_object.'&ref_city='.$this->_domstor->getRealParam('ref_city'));
        $type->setName('type')
            ->setLabel('��� �������')
            ->setOptions($options)
            ->setLayoutClass('domstor_filter_dropdown')
            ->setLabelClass('domstor_filter_trigger')
				->isDropDown(FALSE)
        ;

		 // ������� �������
        DomstorSquareGroundConstructor::add($this->_form);

        // ���������� ����� � �����
		$this->_form->addFields(array(
			$type,
        ));

        return $this->_form;
    }
}

// COMMERCE
class DomstorCommerceSaleFilterBuilder extends DomstorCommonBuilder
{
    public function buildFilter()
    {
        // ���������� ����� ���� �������
        DomstorPriceConstructor::add($this->_form);

        // ���������� ���� ������ (������� �� ���� ��������������)
        DomstorDistrictConstructor::add($this->_form, $this->_domstor);

        if( !$this->_domstor->inRegion() ) {
            DomstorSuburbanConstructor::add($this->_form, $this->_domstor);
        }

		// ����������
		$purpose = new SP_Form_Filter_CheckboxList;
		$options = array(
			'1002' => '��������',
			'1003' => '�������',
			'1005' => '����������������',
			'1006' => '���������',
			'1009' => '��������� �������',
			'1008' => '������������� ��������',
			'1007' => '������',
		);
		$purpose->setName('purpose')
			 ->setLabel('����������')
			 ->setOptions($options)
			 ->setLayoutClass('domstor_filter_dropdown')
			 ->setLabelClass('domstor_filter_trigger')
			 ->isDropDown(FALSE)
		;

		// ������� �������
        DomstorSquareGroundConstructor::add($this->_form);

		// ������� ���������
        DomstorSquareHouseConstructor::add($this->_form);

        // ���������� ����� � �����
		$this->_form->addFields(array(
			$purpose,
        ));

        return $this->_form;
    }
}

class DomstorCommerceRentFilterBuilder extends DomstorCommerceSaleFilterBuilder
{
	public function buildFilter()
	{
		$filter = parent::buildFilter();

		// �������� ����� �� ������ ��� ������
		$filter->deleteField('price_min');
		$filter->deleteField('price_max');

		// ���������� ����� ��������� ������ �������
		$filter->addField(new DomstorRentForm);

		return $filter;
	}
}

class DomstorCommercePurchaseFilterBuilder extends DomstorCommerceSaleFilterBuilder
{

}

class DomstorCommerceRentuseFilterBuilder extends DomstorCommerceRentFilterBuilder
{

}

