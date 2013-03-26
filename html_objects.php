<?php

abstract class DomstorCommon
{
	protected $object;
	protected $action;//��������� ����������� ����� ������� �������� rent ��� sale
	protected $_action;// �� ����� ������������ �������� sale, rent, purchase, rentuse �� � �.�
	protected $object_href;
	protected $exchange_flat_href;
	protected $exchange_house_href;
	protected $commerce_href;
	protected $server_name;
	protected $show_contact = TRUE;
	protected $_show_agency = TRUE;
	protected $in_region;
    protected $show_second_head = false;
	var $prev_next_html;



	//����������� � domstorlib, �� ��������� ��� ����� ������������ � ���
	public function getNavigationHtml()
	{
		$prev = $this->getVar('prev_id');
		$next = $this->getVar('next_id');
        $out = '';

		if($this->_action=='rentuse' or $this->_action=='purchase')
		{
			$name='������';
			$end='��';
		}
		else
		{
			$name='������';
			$end='��';
		}

		if( $prev )
		{
			$href = str_replace('%id', $prev, $this->object_href);
			$out.='<a class="domstor_link" href="'.$href.'">&larr;��������'.$end.' '.$name.'</a> ';
		}

		if( $next )
		{
			$href = str_replace('%id', $next, $this->object_href);
			$out.='<a class="domstor_link" href="'.$href.'">�������'.$end.' '.$name.'&rarr;</a> ';
		}
		$out='<p class="prnxt">'.$out.'</p>';
		return $out;
	}

	public function __construct($attr=null)
	{
		$this->setVars($attr);
	}

    public function getVar($name, $default = null)
    {
        if( array_key_exists($name, $this->object) and $this->object[$name] )
                return $this->object[$name];

        return $default;
    }

	public function setVars($attr=null)
	{
		if(is_array($attr))
		{
			foreach($attr as $key => $value)
			{
				$this->$key=$value;
			}
		}
	}

    public function getTitle()
    {
        return 'Undefined title';
    }

	public function getCode()
	{
		return $this->object['code'];
	}

	public function getObjectUrl($id)
	{
		$out=str_replace('%id', $id, $this->object_href);
		return $out;
	}

	public function getCommerceUrl($id)
	{
		$out=str_replace('%id', $id, $this->commerce_href);
		return $out;
	}

	public function getExchangeFlatUrl($id)
	{
		$out=str_replace('%id', $id, $this->exchange_flat_href);
		return $out;
	}

	public function getExchangeHouseUrl($id)
	{
		$out=str_replace('%id', $id, $this->exchange_house_href);
		return $out;
	}

	public function prevNextHtml()
	{
		return $this->prev_next_html;
	}

	public function render()
    {
        return $this->getHtml().$this->prevNextHtml();
    }

    public function display()
    {
        echo $this->render();
    }

    public function __toString()
	{
		return $this->render();
	}

	public function nbsp($count=1)
	{
		$out ='';
        for($i=0; $i<$count; $i++)
		{
			$out.='&nbsp;';
		}
		return $out;
	}

	public function getElement($title, $value, $after=null, $before=null)
	{
		return '<tr><th>'.$title.'</th><td>'.$before.$value.$after.'</td></tr>';
	}

	public function getElementIf($title, $value, $after=null, $before=null)
	{
		if( $value ) return $this->getElement($title, $value, $after, $before);
	}

	public function getIf($value, $before=null, $after=null)
	{
		if( $value ) return $before.$value.$after;
	}

	public function getFromTo($from, $to, $after=null, $before=null)
	{
		$from_string='��&nbsp;';
		$to_string='��&nbsp;';
		$not_show='0';
        $out = '';
        $space = '';
		if( ($from!==$not_show and isset($from)) or ($to!==$not_show and isset($to)) )
		{

			if( $from===$to )
			{

				$out=$from;

			}
			else
			{
				if( $from!==$not_show and isset($from) )
				{
					$out.=$from_string.$from;
					$space=' ';
				}
				if( $to!==$not_show and isset($to) )
				{

					$out.=$space.$to_string.$to;
				}
			}
			$out=$before.$out.$after;
		}
		return $out;
	}

	public function getPriceFromTo($from, $to, $currency, $period=null)
	{
		$from_string='��&nbsp;';
		$to_string='��&nbsp;';
        $out = '';
        $space = '';
		if( $from!==null or $to!==null )
		if( $from!='0' or  $to!='0')
		{
			if( $from == $to )
			{

				$price=number_format($from, 0, ',', ' ');
				$price=str_replace(' ', '&nbsp;', $price);
				$period = $period? '&nbsp;'.$period : '';
				$out=$price.'&nbsp;'.$currency.$period;
			}
			else
			{
				if( $from != '0' )
				{
					$price=number_format($from, 0, ',', ' ');
					$price=str_replace(' ', '&nbsp;', $price);
					$out.=$from_string.$price;
					$space=' ';
				}

				if( $to != '0' )
				{
					$price=number_format($to, 0, ',', ' ');
					$price=str_replace(' ', '&nbsp;', $price);
					$out.=$space.$to_string.$price;
				}
				$out.='&nbsp;'.$currency.'&nbsp;'.$period;
			}

		}
		return $out;
	}

	public function showContact($value)
	{
		$this->show_contact = (bool)$value;
	}

	public function showAgency($value)
	{
		$this->_show_agency = (bool) $value;
	}

    public function showSecondHead($value)
	{
		$this->show_second_head = (bool) $value;
	}

    public function getSecondHead()
    {
        if( !$this->show_second_head ) return '';

        $tmpl = '<h3>%s %s</h3>';
        return sprintf($tmpl, $this->getEntityType(), $this->getCode());
    }

	public function getCommentBlock()
	{
		$a = &$this->object;
        $out = '';
		if( $a['note_web'] ) $out='
			<div class="domstor_object_comments">
				<h3>�����������</h3>
				<p>'.$a['note_web'].'</p>
			</div>';
		return $out;
	}

	public function getContactBlock()
	{
		if( !$this->show_contact ) return '';
		$a = &$this->object;
        $out = '';
		if( $a['Agent']['tel_work'] and $a['Agent']['tel_sot'] )
		{
			$space = ', ';
		}
		switch ($a['Agency']['tipcont'])
		{
			case '1':
				$phone = $a['Agency']['tel_cont'];
				$mail = $a['Agency']['mail_cont'];
			break;
			case '2':
				$phone = $a['Filial']['phone'];
				$mail = $a['Filial']['mail'];
			break;
			case '3':
				$phone = ( isset($a['agent_phone']) && !empty($a['agent_phone']) )? $a['agent_phone'] : $a['Agent']['tel_work'].$space.$a['Agent']['tel_sot'];
				$mail = $a['Agent']['mail'];
			break;
			default:
				$out='';
			break;
		}

        if( !$a['Agency']['hide_agent'] or $a['Agency']['tipcont'] == '3' )
            $out.=$this->getIf($a['Agent']['name_as'], '<p>�����: ', '</p>');
		if( $this->_show_agency ) $out.=$this->getIf($a['Agency']['shotname'], '<p>���������: ', '</p>');
		$out.=$this->getIf($phone,'<p>�������: ', '</p>');
		$out.=$this->getIf($mail,'<p>��. �����: ', '</p>');
		$edit_dt = '';
		if( $a['edit_dt'] ) $edit_dt = '<p>���������: '.date('d.m.Y', strtotime($a['edit_dt'])).'</p>';
		$edit_dt.= '<p>����������: '.((int) $a['view_count'] + 1).'</p>';
		if( $out )
		{
			$out='<div class="domstor_object_contacts">
					<h3>���������� ����������</h3>
					<table><tr>
						<td>'.
							$out.
						'</td>
						<td>'.
							$edit_dt.
						'</td>
					</tr></table>
				</div>';
		}
		return $out;
	}

	public function getDateBlock()
	{

        $out = '';
		return $out;
	}

	public function getPurpose()
	{
        $purp = $this->getVar('Purposes');
        $out = '';
		if( $purp )
		{
			if( isset($purp[1001]) )
			{
				unset($purp[1002], $purp[1003]);
			}
			if( isset($purp[1004]) )
			{
				unset($purp[1005], $purp[1006]);
			}
			if( isset($purp[1009]) )
			{
				for($i=1013; $i<1022; $i++)
				{
					unset($purp[$i]);
				}
			}
			$out = implode(', ', $purp);
		}
		return $out;
	}

	public function isEmpty()
	{
		return empty($this->object['code']);
	}

	public function getServerName()
	{
		return $this->server_name;
	}

	public function setServerName($value)
	{
		return $this->server_name;
	}

    abstract public function getEntityType();
}


//COMMON**********************************************************

class DomstorCommonObject extends DomstorCommon
{
	protected function getImageLink($src, $type)
	{
		//var_dump($src);
		$out='<a href="http://'.$this->getServerName().'/foto/'.$src.'" class="modal" rel="'.$type.'">
			<img src="http://'.$this->getServerName().'/foto/'.$src.'" alt="" />
		</a>
		';
		return $out;
	}

	public function getObjectCode()
	{
		return '������ '.$this->object['code'];
	}

	protected function getPhotoHtml($photos)
	{
		if( !empty($photos) )
		{
			$out='<div class="domstor_photo">';
			foreach($photos as $photo)
			{
				$out.=$this->getImageLink($photo, 1);
			}
			$out.='</div>';
		}
		return $out;
	}

	protected function getPlanHtml($photos)
	{
		if( !empty($photos) )
		{
			$out='<div class="domstor_plan">';
			foreach($photos as $photo)
			{
				$out.=$this->getImageLink($photo, 2);
			}
			$out.='</div>';
		}
		return $out;
	}

	protected function getMapHtml($photos)
	{
		if( !empty($photos) )
		{
			$out='<div class="domstor_map">';
			foreach($photos as $photo)
			{
				$out.=$this->getImageLink($photo, 3);
			}
			$out.='</div>';
		}
		return $out;
	}

	protected function getImagesHtml($object)
	{
        $out = '';
        if( isset($object['img_photo']) )
            $out = $this->getPhotoHtml($object['img_photo']);

        if( isset($object['img_plan']) )
            $out.= $this->getPlanHtml($object['img_plan']);

		if( $out ) $out='<div class="domstor_images">'.$out.'</div>';
		return $out;
	}

	public function getOfferType($action=null)
	{
		if( !$action ) $action=$this->_action;
		if( $action=='rent' )
		{
			$out='������';
		}
		elseif( $action=='exchange' )
		{
			$out='�����';
		}
		else
		{
			$out='�������';
		}
		return $out;
	}

	public function getOfferType2($action = NULL)
	{
		if( !$action ) $action = $this->_action;
		if( $action == 'rent' )
		{
			$out='�������';
		}
		elseif( $action == 'exchange' )
		{
			$out='�������';
		}
		else
		{
			$out='���������';
		}
		return $out;
	}

	public function getDemandsBlock()
	{
		$obj = &$this->object;
        $out = '';
		if( $obj['Demands'] and $this->action=='exchange')
		{
			$type=array(4=>'��������', 6=>'���');
			foreach( $obj['Demands'] as $a )
			{

				if( $a['data_class']==4 )
				{
					$href=$this->getExchangeFlatUrl($a['id']);
				}
				elseif( $a['data_class']==6 )
				{
					$href=$this->getExchangeHouseUrl($a['id']);
				}
				$annotation='<a href="'.$href.'" class="domstor_link">'.$a['code'].'</a> '.$type[$a['data_class']];
				unset($rooms);
				if( $a['new_building'] ) $annotation.=', �����������';
				for($room=1; $room<6; $room++)
				{
					if( $a['room_count_'.$room] ) $rooms.=$room.', ';
				}
				$rooms=substr($rooms, 0, -2);
				if( $rooms )  $annotation.=', '.$rooms.' ����.';
				if( $a['in_communal'] ) $annotation.=', (� ����������)';
				if( $a['object_floor_limit'] )  $annotation.=', '.$a['object_floor_limit'].' ��.';
				if( $a['district'] )  $annotation.=', '.$a['district'];

				$price=DomstorCommonDemand::getPriceFromTo($a['price_full_min'], $a['price_full_max'], $a['price_currency']);
				if( $price ) $annotation.=', '.$price;
				if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
				$out.='<p>'.$annotation.'</p>';
			}
		}
		if( $out )
		{
			$out='<div class="domstor_object_demands">
						<h3>������</h3>'.$out.'
					</div>';
		}
		return $out;
	}

	public function getRoomCount($count)
	{
		$out ='';
        if( $count )
		{
			if( $count==1 )
				$room=' �������';
			elseif( $count<5 )
				$room=' �������';
			else
				$room=' ������';
			$out=$count.$room;
		}
		return $out;
	}

	public function getSquares($house, $living, $kitchen)
	{
		if( $house or $living or $kitchen )
		{
			if( !$house ) $house='-';
			if( !$living ) $living='-';
			if( !$kitchen ) $kitchen='-';
			$out=$house.'/'.$living.'/'.$kitchen;
		}
		return $out;
	}

	public function getFloors()
	{
		$a = &$this->object;
        $out = '';
		if( $this->getVar('object_floor') or  $this->getVar('building_floor') )
		{
			$object = $this->getVar('object_floor');
			$building = $this->getVar('building_floor');
			if( !$object ) $object = '-';
			if( !$building ) $building = '-';
			$out = $object.'/'.$building;
		}
		return $out;
	}

	public function getStreetBuilding()
	{
		$a = &$this->object;
		$out = '';

		if(  $this->getVar('street') and  $this->getVar('street_id') )
		{
			$out = $a['street'];
			if(  $this->getVar('building_num') )
			{
				$out.= ', '.$a['building_num'];
				if(  $this->getVar('corpus') )
				{
					if( is_numeric($a['corpus']) )
					{
						$out.= '/'.$a['corpus'];
					}
					else
					{
						$out.= strtoupper($a['corpus']);
					}
				}
			}
		}

		return $out;
	}

	public function getAddress()
	{
		$a = &$this->object;
        $out = '';
        $space2 = '';
        $space = '';
        $district_type = isset($a['district_type'])? $a['district_type'] : '';
		if( $this->in_region or $a['district'] == '��������' or $district_type == 7 )
		{
			if( $this->in_region ) $out.=$this->getIf($a['city'], '', ', ');
			$out.=$this->getIf($a['district'], '', ', ');
			$out.=$this->getIf($a['address_note'], '', ', ');
			$out=substr($out, 0, -2);
		}
		else
		{
			if( $a['street'] and $a['street_id'] )
			{
				$out = $this->getStreetBuilding();
				$space=', ';
			}

			if( $a['district'] )
			{
				if( substr($a['district'], -1)=='�' )
				{
					$out.=$space.$a['district'].'&nbsp;�-�';
				}
				else
				{
					$out.=$space.'�-�&nbsp;'.$a['district'];
				}
			}
			if( $out ) $space2=', ';
			$out.=$space2.$a['city'];
		}

		if( isset($a['cooperative_name']) and $a['cooperative_name'] ) $out.=', '.$a['cooperative_name'];

		return $out;
	}

	public function getFormatedPrice($price, $price_currency, $period = NULL)
	{
		if( (float) $price )
		{
			$out = number_format($price, 0, ',', ' ');
			if( $price_currency ) $out.= ' '.$price_currency;
			if( $period ) $out.= ' '.$period;
			$out = str_replace(' ', '&nbsp;', $out);
			return $out;
		}
	}

	public function getPrice()
	{
		$a=&$this->object;
		if( $this->action=='rent' )
		{
			$out=$this->getFormatedPrice($a['rent_full'], $a['rent_currency'], $a['rent_period']);
		}
		else
		{
			$out=$this->getFormatedPrice($a['price_full'], $a['price_currency']);
		}
		return $out;
	}

	public function getLocationBlock()
	{
		$a=&$this->object;
		$location=$this->getAddress();
		if( $location )
		{
			if( isset($a['address_note']) and $a['address_note'] and !$this->in_region ) $location.=', '.$a['address_note'];
			$location='<p>'.$location.'</p>';
			if( isset($a['first_line']) and $a['first_line'] ) $location.='<p>������ �����</p>';
			if( isset($a['metro']) and $a['metro'] ) $location.='<p>'.$a['metro'].'</p>';
			if( isset($a['available_bus']) and $a['available_bus'] ) $location.='<p>�� ��������� '.$a['available_bus'].' ���.</p>';
			if( isset($a['available_metro']) and $a['available_metro'] ) $location.='<p>�� ����� '.$a['available_metro'].' ���.</p>';
			if( isset($a['available_bus_to_metro']) and $a['available_bus_to_metro'] ) $location.='<p>����������� �� ����� '.$a['available_bus_to_metro'].' ���.</p>';
			if( isset($a['map_weblink']) and $a['map_weblink'] ) $location.='<p><a href="'.$a['map_weblink'].'" target="_blank">�� �����</a></p>';
			if( isset($a['img_map']) and $map = $this->getMapHtml($a['img_map']) ) $location.= $map;
			if( isset($a['zone']) and $a['zone'] ) $location.='<p>��������������� ����'.$a['zone'].'</p>';
			$location='
				<div class="domstor_object_place">
					<h3>��������������</h3>'.$location.'
				</div>'
			;
		}


		return $location;
	}

	public function getRealizationBlock()
	{
		$a = &$this->object;
        $out = '';
		if( $a['realization_way'] )
		{
			if( $a['realization_way_id']==1183 or $a['realization_way_id']==1184 or $a['realization_way_id']==1185 )
			{
				$out.=$this->getElement('������ ����������:', $a['realization_way']);
				$out.=$this->getElementIf('��������� ����:', $this->getFormatedPrice($a['auction_initial_price'], $a['auction_currency']));
				$out.=$this->getElementIf('����� �������:', $this->getFormatedPrice($a['auction_advance'], $a['auction_currency']));
				$out.=$this->getElementIf('��� ��������:', $this->getFormatedPrice($a['auction_step'], $a['auction_currency']));
				$out.=$this->getElementIf('��� ��������:', $a['auction_type']);
				$date=strtotime($a['auction_dttm']);
				if( $date ) $out.=$this->getElement('���� ����������:', date('d.m.Y', $date));
				if( $date ) $out.=$this->getElement('����� ����������:', date('H:i', $date));
				$out.=$this->getElementIf('����� ����������:', $a['auction_location']);
				$date=strtotime($a['auction_filing_start']);
				if( $date ) $out.=$this->getElement('���� ����� ������ ������:', date('d.m.Y', $date));
				$date=strtotime($a['auction_filing_finish']);
				if( $date ) $out.=$this->getElement('���� ��������� ������ ������:', date('d.m.Y', $date));
			}
			else
			{
				$out.=$this->getElement('', $a['realization_way']);
			}
			$out = '<div class="domstor_object_realization">
					<h3>������ ����������:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getFinanceBlock()
	{
		$a = &$this->object;
        $out = '';

		if( $this->getVar('active_sale') and (float) $this->getVar('price_full') )
            $out.=$this->getElement('����:', $this->getFormatedPrice($a['price_full'], $a['price_currency']));

		if( $this->getVar('active_rent') and (float) $this->getVar('rent_full') )
            $out.=$this->getElement('�������� ������:', $this->getFormatedPrice($a['rent_full'], $a['rent_currency'], $a['rent_period']));

		if( $out )
		{
			$out = '<div class="domstor_object_finance">
					<h3>���������� �������:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

    public function getEntityType()
    {
        return '������';
    }

    public function getSecondHead()
    {
        if( !$this->show_second_head ) return '';

        $tmpl = '<h3>��� �������: %s</h3>';
        return sprintf($tmpl, $this->getCode());
    }
}

class DomstorCommonDemand extends DomstorCommon
{
	public function getObjectCode()
	{
		return '������ '.$this->object['code'];
	}

	public function getOfferType($action)
	{
		if( $action=='rent' )
		{
			$out='������';
		}
		else
		{
			$out='�����';
		}
		return $out;
	}

    public function getOfferType2()
	{
		if( $this->action == 'rent' )
		{
			$out='�����';
		}
		else
		{
			$out='�����';
		}
		return $out;
	}

	public function getAddress()
	{
		$a = &$this->object;
        $out = '';
		if( $this->in_region )
		{
			$out.= $this->getIf($this->getVar('address_note'), '', ', ');
			$out.= $this->getIf($this->getVar('city'), '', ', ');
			$out= substr($out, 0, -2);
		}
		else
		{
			if( $this->getVar('district') ) $out='������: '.$a['district'].', ';
			if( $this->getVar('street') ) $out.='�����: '.$a['street'].', ';
			$out.= $a['city'];
		}

		return $out;
	}

	public function getFormatedPrice()
	{
        $min = (float) $this->getVar('price_full_min');
        $max = (float) $this->getVar('price_full_max');
		$out = $this->getPriceFromTo($min, $max, $this->getVar('price_currency'));
		return $out;
	}

	public function getFormatedRent()
	{
		$min = (float) $this->getVar('rent_full_min');
        $max = (float) $this->getVar('rent_full_max');
		$out = $this->getPriceFromTo($min, $max, $this->getVar('rent_currency'), $this->getVar('rent_period'));
		return $out;
	}

	public function getPrice()
	{
		if( $this->action=='rent' )
		{
			$out=$this->getFormatedRent();
		}
		else
		{
			$out=$this->getFormatedPrice();
		}
		return $out;
	}

	public function getLocationBlock()
	{
		$a = &$this->object;
		$location = $this->getAddress();

		if( $location )
		{
			if( $this->getVar('address_note') ) $location.=', '.$a['address_note'];
			$location='<p>'.$location.'</p>';
			if( $this->getVar('first_line_want') ) $location.='<p>������ �����: '.$a['first_line_want'].'</p>';
			if( $this->getVar('metro') ) $location.='<p>'.$a['metro'].'</p>';
			if( $this->getVar('available_bus') ) $location.='<p>�� ��������� �� ����� '.$a['available_bus'].' ���.</p>';
			if( $this->getVar('available_metro') ) $location.='<p>�� ����� �� ����� '.$a['available_metro'].' ���.</p>';
			if( $this->getVar('available_bus_to_metro') ) $location.='<p>����������� �� ����� �� ����� '.$a['available_bus_to_metro'].' ���.</p>';
			$location='<div class="domstor_object_place">
						<h3>��������������</h3>'.$location.'
					</div>';
		}

		return $location;
	}

	public function getFinanceBlock()
	{
        $out = '';

        $price = $this->getFormatedPrice();
        if( $this->getVar('active_sale') and $price )
            $out.= $this->getElementIf('������:', $price);

        $rent = $this->getFormatedRent();
        if( $this->getVar('active_rent') and $rent )
            $out.= $this->getElementIf('������:', $rent);

        if( $out )
		{
			$out = '<div class="domstor_object_finance">
					<h3>���������� �������:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

    public function getEntityType()
    {
        return '������';
    }

    public function getSecondHead()
    {
        if( !$this->show_second_head ) return '';

        $tmpl = '<h3>��� ������: %s</h3>';
        return sprintf($tmpl, $this->getCode());
    }
}

//FLAT******************************************************

class DomstorFlat extends DomstorCommonObject
{
	public function getPageTitle()
	{
		$a = &$this->object;

		$out = $this->getTitle();

		if( isset($a['Agency']) and isset($a['Agency']['name']) ) $out.=' &mdash; '.$a['Agency']['name'];

		return $out;
	}

	public function getTitle()
	{
		$a = &$this->object;

		$rooms = array(
			1 => '����',
			2 => '����',
			3 => '����',
			4 => '�������',
			5 => '����',
			6 => '�����',
			7 => '����',
		);

		$out = $this->getOfferType2().' ';

		if( isset($rooms[$a['room_count']]) ) $out.= $rooms[$a['room_count']].($this->_action=='exchange'? '��������� ' : '��������� ');

		$out.= ($this->_action=='exchange'? '�������� ' : '�������� ');

		if( $a['city'] ) $out.= '� '.$a['city'];

		$addr = $this->getStreetBuilding();
        if( $addr ) $out.= ', '.$addr;

		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['new_building'] ) $annotation.=', �����������';
		if( $a['room_count'] )  $annotation.=', '.$this->getRoomCount($a['room_count']);
		if( $a['in_communal'] ) $annotation.=' (� ����������)';
		if( $a['flat_type'] ) $annotation.=', '.$a['flat_type'];
		$squares=$this->getSquares($a['square_house'],$a['square_living'],$a['square_kitchen']);
		if( $squares )  $annotation.=', '.$squares;
		$floors=$this->getFloors($a['object_floor'], $a['building_floor']);
		if( $floors )  $annotation.=', '.$floors;
		$address=$this->getAddress();
		if( $address ) $annotation.=', '.$address;
		$price=$this->getPrice();
		if( $price ) $annotation.=', '.$price;
		if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getRoomsBlock()
	{
		$a=&$this->object;
		$room=$this->getRoomCount($a['room_count']);
		if( $room ) $room='<p>'.$room.'</p>';
		if( $a['in_communal'] ) $room.='<p>������� � ����������</p>';
		if( $a['in_pocket'] ) $room.='<p>���� ������</p>';
		if( $a['Together']['id'] )
		{
			$tgh=$a['Together'];
			$together='<a href="'.$this->getObjectUrl($tgh['id']).'" class="domstro_link">'.$tgh['code'].'</a>';
			if( $tgh['room_count'] ) $together.=', '.$this->getRoomCount($a['room_count']);
			$squares=$this->getSquares($tgh['square_house'], $tgh['square_living'], $tgh['square_kitchen']);
			if( $squares )  $together.=', '.$squares;
			$price=$this->getFormatedPrice($tgh['price_full'], $tgh['price_currency']);
			if( $price ) $together.=', '.$price;
			if( $a['Together'] ) $room.='<p>���������� ������� � �������� ���������:<br />'.$this->nbsp(4).$together.'</p>';
		}
		if( $room )
		{
			$room='<div class="domstor_object_rooms">
					<h3>����� ������</h3>'.
					$room.
				'</div>';
		}
		return $room;
	}

	public function getFloorsBlock()
	{
		$a=&$this->object;
		$out=$this->getFloors();
		if( $out ) $out='<p>'.$out.'</p>';
		if( $a['ground_floor'] ) $out.='<p>� ������ ������� ��������� ����</p>';
		if( $a['first_floor_commerce'] ) $out.='<p>������ ����� �������</p>';
		if( $out )
		{
			$out='<div class="domstor_object_floor">
					<h3>����</h3>'.
					$out.
				'</div>';
		}
		return $out;
	}

	public function getTypeBlock()
	{
		$a = &$this->object;
        $type = '';
		if( $this->getVar('flat_type') ) $type=$this->getElement('��� ��������:', $a['flat_type']);
		if( $this->getVar('planning') ) $type.=$this->getElement('����������:', $a['planning']);
		if( $this->getVar('building_material') ) $type.=$this->getElement('�������� ��������:', $a['building_material']);
		if( $type )
		{
			$type='<div class="domstor_object_type">
					<h3>��� �������� (������)</h3>
					<table>'.$type.'</table>
				</div>';
		}
		return $type;
	}

	public function getSizeBlock()
	{
		$a = &$this->object;
        $square = $out = '';
		if( isset($a['height']) and $a['height'] ) $out=$this->getElement('������ ��������:', $a['height'], ' �.');
		if( isset($a['floor_count']) and $a['floor_count'] > 1 ) $out.=$this->getElement('���������� �������:', $a['floor_count']);
		if( isset($a['square_house']) and $a['square_house'] )  $square.=$this->getElement($this->nbsp(4).'�����:', $a['square_house']);
		if( isset($a['square_living']) and $a['square_living'] ) $square.=$this->getElement($this->nbsp(4).'�����:', $a['square_living']);
		if( isset($a['square_kitchen']) and $a['square_kitchen'] ) $square.=$this->getElement($this->nbsp(4).'�����:', $a['square_kitchen']);
		if( isset($a['square_pocket']) and $a['square_pocket'] ) $square.=$this->getElement($this->nbsp(4).'������:', $a['square_pocket']);
		if( $square ) $square=$this->getElement('�������, ��.�.:', '').$square;
		$out.= $square;
		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>�������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getTechnicBlock()
	{
		$a = &$this->object;
        $out = '';
        $communications='';
        $show = false;

		if( $this->getVar('phone') ) $communications.='�������, ';
		if( $this->getVar('internet') ) $communications.='��������, ';
		if( $this->getVar('cable_tv') ) $communications.='��������� ��, ';
		if( $this->getVar('door_phone') ) $communications.='�������, ';
		if( $this->getVar('gas') ) $communications.='����������, ';
		if( $this->getVar('satellite_tv') ) $communications.='����������� ��, ';
		if( $this->getVar('signalizing') ) $communications.='�������� ������������, ';
		if( $this->getVar('fire_prevention') ) $communications.='��������������� ������������, ';
		if( $communications )
		{
			$communications=substr($communications, 0, -2);
			$communications = $this->getElement('������������:', $communications);
			$show=true;
		}

        $san_tech = '';
		if( $this->getVar('toilet') ) $san_tech.=$this->getElement($this->nbsp(4).'C������:', $a['toilet']);
		if( $this->getVar('toilet_count') ) $san_tech.=$this->getElement($this->nbsp(4).'���������� ��������:', $a['toilet_count']);
		if( $this->getVar('santech_year') ) $san_tech.=$this->getElement($this->nbsp(4).'��� ������ (���������) ����������:', $a['santech_year']);
		if( $this->getVar('santech_material') ) $san_tech.=$this->getElement($this->nbsp(4).'������. �����:', $a['santech_material']);
		if( $this->getVar('sewerage_material') ) $san_tech.=$this->getElement($this->nbsp(4).'����� �����������:', $a['sewerage_material']);
		if( $this->getVar('heat_battery') ) $san_tech.=$this->getElement($this->nbsp(4).'������� ���������:', $a['heat_battery']);
		if( $san_tech )
		{
			$san_tech = $this->getElement('�������, ����������, ��������:', '').$san_tech;
			$show=true;
		}

        $construction = '';
		if( $this->getVar('material_wall') ) $construction.=$this->getElement($this->nbsp(4).'�������� �������� ����:', $a['material_wall']);
		if( $this->getVar('material_ceiling') ) $construction.=$this->getElement($this->nbsp(4).'�������� ����������:', $a['material_ceiling']);
		if( $this->getVar('material_carying') ) $construction.=$this->getElement($this->nbsp(4).'�������� ������� �����������:', $a['material_carying']);
		if( $construction )
		{
			$construction = $this->getElement('����������� ������:', '').$construction;
			$show=true;
		}

		if( $this->getVar('balcony_count') == 1 )
			$balcony=' ������';
		elseif( $this->getVar('balcony_count')<5 )
			$balcony=' �������';
		else
			$balcony=' ��������';

		if( $this->getVar('loggia_count') == 1 )
			$loggia=' ������';
		elseif( $this->getVar('loggia_count') < 5 )
			$loggia=' ������';
		else
			$loggia=' ������';

        $balc_log = '';
		if(  $this->getVar('balcony_count') ) $balc_log.=$this->getElement($this->nbsp(4).'���������� ��������:', $a['balcony_count']);
		if(  $this->getVar('loggia_count') ) $balc_log.=$this->getElement($this->nbsp(4).'���������� ������:', $a['loggia_count']);
		if(  $this->getVar('balcony_arrangement') ) $balc_log.=$this->getElement($this->nbsp(4).'������������:', $a['balcony_arrangement']);
		if( $balc_log )
		{
			$balc_log = $this->getElement('������, ������:', '').$balc_log;
			$show=true;
		}

        $windows = '';
		if( $this->getVar('window_material') ) $windows.=$this->getElement($this->nbsp(4).'�������� ���:', $a['window_material']);
		if( $this->getVar('window_glasing') ) $windows.=$this->getElement($this->nbsp(4).'��� ����������:', $a['window_glasing']);
		if( $this->getVar('window_opening') ) $windows.=$this->getElement($this->nbsp(4).'��� ����������:', $a['window_opening']);
		if( $windows )
		{
			$windows = $this->getElement('����:', '').$windows;
			$show=true;
		}

        $doors = '';
		if( $this->getVar('door_room') ) $doors.=$this->getElement($this->nbsp(4).'����� ������������:', $a['door_room']);
		if( $this->getVar('door_front_material') ) $door_front_material=', '.$a['door_front_material'];
		if( $this->getVar('door_front') ) $doors.=$this->getElement($this->nbsp(4).'������� �����:', $a['door_front'].$door_front_material);
		if( $this->getVar('door_pocket_material') ) $doors.=$this->getElement($this->nbsp(4).'����� � ������:', $a['door_pocket_material']);
		if( $doors )
		{
			$doors = $this->getElement('�����:', '').$doors;
			$show=true;
		}

		//	�������
        $finish = '';
		if( $this->getVar('finish_ceiling') ) $finish.=$this->getElement($this->nbsp(4).'�������:', $a['finish_ceiling']);
		if( $this->getVar('finish_paul') ) $finish.=$this->getElement($this->nbsp(4).'����: ', $a['finish_paul']);
		if( $this->getVar('finish_partition') ) $finish.=$this->getElement($this->nbsp(4).'�����������: ', $a['finish_partition']);
		if( $finish )
		{
			$finish = $this->getElement('�������:', '').$finish;
			$show=true;
		}

		//	���������
        $state = '';

		if( $this->getVar('build_year') ) $state.=$this->getElement($this->nbsp(4).'��� ���������:', $a['build_year']);
		if( $this->getVar('wearout') ) $state.=$this->getElement($this->nbsp(4).'������� ������: ', $a['wearout'].'%');
		if( $this->getVar('state') ) $state.=$this->getElement($this->nbsp(4).'���������:', $a['state']);
		if( $state )
		{
			$state = $this->getElement('��������� �������:', '').$state;
			$show=true;
		}

		if( $show )
		{
			$out='<div class="domstor_object_technic">
					<h3>����������� ��������������</h3>
					<table>'.
						$communications.
						$san_tech.
						$construction.
						$balc_log.
						$windows.
						$doors.
						$finish.
						$state.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getFurnitureBlock()
	{
		$a = &$this->object;
        $out = '';
		if( $this->getVar('furniture') ) $out.= $this->getElement('������:', $this->getVar('furniture'));
        if( $this->getVar('household_technique') ) $out.= $this->getElement('������� �������:', $this->getVar('household_technique'));
		if( $this->getVar('in_corner') ) $out.=$this->getElement('������������:', '������� ��������');

        $window_direction = '';
		if( $this->getVar('window_to_south') ) $window_direction='��, ';
		if( $this->getVar('window_to_north') ) $window_direction.='�����, ';
		if( $this->getVar('window_to_west') ) $window_direction.='�����, ';
		if( $this->getVar('window_to_east') ) $window_direction.='������, ';
		if( $window_direction )
		{
			$window_direction=substr($window_direction, 0, -2);
			$out.=$this->getElement('������������ ����:', $window_direction);
		}

        $parking = '';
		if( $this->getVar('garbage_chute') ) $out.=$this->getElement('������������:', '�������');
		if( $this->getVar('security') ) $out.=$this->getElement('������:', $a['security']);
		if( $this->getVar('sale_with_parking') ) $parking=', �������� ������� ��������� � ������� ��� �����������';
		if( $this->getVar('parking') ) $out.=$this->getElement('��������:', $a['parking'].$parking);

		$lifts = '';
        if( $this->getVar('lift_count') == 1 )
			$lift=' ����';
		elseif( $this->getVar('lift_count') < 5 )
			$lift=' �����';
		else
			$lift=' ������';
		if( $this->getVar('lift_count') ) $lifts=$a['lift_count'].$lift.', ';
		if( $this->getVar('lift_cargo') ) $lifts.='���� ��������, ';
		if( $lifts )
		{
			$lifts=substr($lifts, 0, -2);
			$lifts = $this->getElement('�����', $lifts);
			$out.=$lifts;
		}
		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>����������, ������������:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return '������ �� ������';
		$out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
                    $this->getSecondHead().
                    $this->getDemandsBlock().
				'</div>'.
				$this->getImagesHtml($this->object).
				'<div class="domstor_object_common">'.
					$this->getLocationBlock().
					$this->getRoomsBlock().
					$this->getFloorsBlock().
				'</div>';
		$out.=$this->getRealizationBlock();
		$out.=$this->getTypeBlock();
		$out.=$this->getSizeBlock();
		$out.=$this->getTechnicBlock();
		$out.=$this->getFurnitureBlock();
		$out.=$this->getFinanceBlock();
		$out.=$this->getCommentBlock();
		$out.=$this->getContactBlock();
		$out.=$this->getDateBlock();
		$out.=$this->getNavigationHtml();
		return $out;
	}
}

class DomstorFlatDemand extends DomstorCommonDemand
{
	public function getPageTitle()
	{
		$a = &$this->object;

		$out = $this->getTitle();

		if( isset($a['Agency']) and isset($a['Agency']['name']) ) $out.=' &mdash; '.$a['Agency']['name'];

		return $out;
	}

	public function getTitle()
	{
		$a = &$this->object;

		$out = $this->getOfferType2().' ';

		$out.= str_replace('����.', '���������', $this->getRooms()).' ';
        $out.= '�������� � '.$a['city'];
        return $out;
	}

	public function getRooms()
	{
		$rooms = '';
        for($room=1; $room<6; $room++)
		{
			if( $this->getVar('room_count_'.$room) ) $rooms.=$room.', ';
		}
		$rooms=substr($rooms, 0, -2);
		if( $rooms )  $rooms.=' ����.';
		return $rooms;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $this->getVar('new_building') ) $annotation.=', �����������';
		$rooms=$this->getRooms();
		if( $rooms )  $annotation.=', '.$rooms;
		if( $this->getVar('in_communal') ) $annotation.=' (� ����������)';
		if( $this->getVar('object_floor') )  $annotation.=', '.$a['object_floor'].' ��.';
		$location=$this->getAddress();
		if( $location ) $annotation.=', '.$location;
		$price=$this->getPrice($this->getVar('price_full'), $this->getVar('price_currency'), $this->getVar('rent_full'), $this->getVar('rent_period'), $this->getVar('rent_currency'));
		if( $price ) $annotation.=', '.$price;
		if( $this->getVar('note_addition') ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getRoomsBlock()
	{
		$a = &$this->object;

        $rooms = '';
		for($room=1; $room<6; $room++)
		{
			if( $this->getVar('room_count_'.$room) ) $rooms.=$room.' ����., ';
		}
		$rooms=substr($rooms, 0, -2);

		if( $rooms ) $rooms='<p>'.$rooms.'</p>';
		if( $this->getVar('in_communal') ) $rooms.='<p>������� � ����������</p>';
		if( $rooms )
		{
			$rooms='<div class="domstor_object_rooms">
					<h3>����� ������</h3>'.
					$rooms.
				'</div>';
		}
		return $rooms;
	}

	public function getFloorsBlock()
	{
		$a = &$this->object;
        $floor = '';
		if( $this->getVar('object_floor') ) $floor='<p>'.$a['object_floor'].' ��.</p>';
		if( $this->getVar('object_floor_limit') ) $floor.='<p>'.$a['object_floor_limit'].'</p>';
		if( $floor )
		{
			$floor='<div class="domstor_object_floor">
					<h3>����</h3>'.
					$floor.
				'</div>';
		}
		return $floor;
	}

	public function getTypeBlock()
	{
		$a = &$this->object;
        $type = '';
		if( $this->getVar('flat_type') ) $type=$this->getElement('��� ��������:', $a['flat_type']);
		if( $this->getVar('planning') ) $type.=$this->getElement('����������:', $a['planning']);
		if( $this->getVar('building_material') ) $type.=$this->getElement('�������� ��������:', $a['building_material']);
		if( $type )
		{
			$type='<div class="domstor_object_type">
					<h3>��� �������� (������)</h3>
					<table>'.$type.'</table>
				</div>';
		}
		return $type;
	}

	public function getSizeBlock()
	{
		$a = &$this->object;
		$out = $this->getElementIf('������ ��������:', $this->getFromTo($this->getVar('height_min'), $this->getVar('height_max'), ' �'));

        $square = '';
		$square.=$this->getElementIf($this->nbsp(4).'�����:', $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max')));
		$square.=$this->getElementIf($this->nbsp(4).'�����:', $this->getFromTo($this->getVar('square_living_min'), $this->getVar('square_living_max')));
		$square.=$this->getElementIF($this->nbsp(4).'�����:',  $this->getFromTo($this->getVar('square_kitchen_min'), $this->getVar('square_kitchen_max')));
		if( $square ) $sqaure=$this->getElement('�������, ��.�.:', '').$square;

		$out.= $square;
		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>�������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getTechnicBlock()
	{
		$a=&$this->object;
        $out = '';
        $communications = '';
		$communications.=$this->getElementIf($this->nbsp(4).'�������:', $this->getVar('phone_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'��������:', $this->getVar('internet_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'��������� ��:', $this->getVar('cable_tv_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'�������:', $this->getVar('door_phone_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'����������:', $this->getVar('gas_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'����������� ��:', $this->getVar('satellite_tv_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'�������� ������������:', $this->getVar('signalizing_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'��������������� ������������:', $this->getVar('fire_prevention_want'));

		if( $communications )
		{
			$out = $this->getElement('������������:', '').$communications;
		}

		$out.=$this->getElementIf($this->nbsp(4).'��������� ������� �� ����� ���:', $this->getVar('state'));


		if( $out )
		{
			$out='<div class="domstor_object_technic">
					<h3>����������� ��������������</h3>
					<table>'.
						$out.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getFurnitureBlock()
	{
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('������� ������:', $this->getVar('with_furniture_want'));
		$out.=$this->getElementIf('�������� ����:', $this->getVar('lift_cargo_want'));
		$out.=$this->getElementIf('��������:', $this->getVar('parking'));
		$out.=$this->getElementIf('�������� ��������� � ������� ��� �����������:', $this->getVar('sale_with_parking'));

		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>����������, ������������:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return '������ �� �������';
		$out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
                     $this->getSecondHead().
					//'<p>'.$this->getAnnotation().'</p>'.
				'</div>
				<div class="domstor_object_common">'.
					$this->getLocationBlock().
					$this->getRoomsBlock().
					$this->getFloorsBlock().
				'</div>';
		$out.=$this->getTypeBlock();
		$out.=$this->getSizeBlock();
		$out.=$this->getTechnicBlock();
		$out.=$this->getFurnitureBlock();
		$out.=$this->getFinanceBlock();
		$out.=$this->getCommentBlock();
		$out.=$this->getContactBlock();
		$out.=$this->getDateBlock();
		$out.=$this->getNavigationHtml();
		return $out;
	}



}

//HOUSE******************************************************

class DomstorHouse extends DomstorCommonObject
{
	public function getPageTitle()
	{
		$a = &$this->object;

		$out = $this->getTitle();

		if( $a['Agency']['name'] ) $out.=' &mdash; '.$a['Agency']['name'];

		return $out;
	}

	public function getTitle()
	{
		$a = &$this->object;

		$out = $this->getOfferType2().' ';

		$type = $this->getVar('house_type', '���');
		$out.= $type.' ';

		if( $a['city'] ) $out.= '� '.$a['city'];

		$addr = $this->getStreetBuilding();
		$district = ($this->getVar('district_parent') == '��������')? ', '.$this->getVar('district') : '';
		$out.= $district.($addr? ', '.$addr : ($this->getVar('address_note')? ', '.$this->getVar('address_note') : '' ));

		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['house_type'] ) $annotation.=', '.$a['house_type'];
		if( $a['room_count'] )  $annotation.=', '.$this->getRoomCount($a['room_count']);
		if( $a['square_house'] )  $annotation.=', '.$a['square_house'].' ��.�.';
		$address=$this->getAddress();
		if( $address ) $annotation.=', '.$address;
		$price=$this->getPrice($a['price_full'], $a['price_currency'], $a['rent_full'], $a['rent_period'], $a['rent_currency']);
		if( $price ) $annotation.=', '.$price;
		if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getRoomsBlock()
	{
		$a = &$this->object;
        $room = '';
		if( $this->getVar('room_count') )
		{
			$room='<div class="domstor_object_rooms">
					<h3>����� ������</h3><p>'.
					$this->getRoomCount($a['room_count']).
				'</p></div>';
		}
		return $room;
	}

	public function getFloorsBlock()
	{
		$a = &$this->object;
        $floor = '';
		if(  $this->getVar('building_floor') )
		{
			$floor = $a['building_floor'].' ��.';
			if(  $this->getVar('ground_floor') ) $floor.=', ��������� ����';
			if(  $this->getVar('mansard') ) $floor.=', ��������';
			if(  $this->getVar('cellar') ) $floor.=', ������';
			$floor='<div class="domstor_object_floor">
					<h3>�����</h3><p>'.
					$floor.
				'</p></div>';
		}
		return $floor;
	}

	public function getSizeBlock()
	{
		$a = &$this->object;
        $out = '';

        $square = '';
		if( $this->getVar('square_house') )  $square.=$this->getElement($this->nbsp(4).'�����:', $a['square_house']);
		if( $this->getVar('square_living') ) $square.=$this->getElement($this->nbsp(4).'�����:', $a['square_living']);
		if( $this->getVar('square_kitchen') ) $square.=$this->getElement($this->nbsp(4).'�����:', $a['square_kitchen']);
		if( $this->getVar('square_utility') ) $square.=$this->getElement($this->nbsp(4).'��������� ���������:', $a['square_utility']);
		if( $square ) $sqaure=$this->getElement('�������, ��.�.:', '').$square;
		$out.=$sqaure;

        $size = '';
		if( $this->getVar('size_house_x') and $this->getVar('size_house_y') ) $size.=$this->getElement($this->nbsp(4).'��������:', $a['size_house_x'].' x '.$a['size_house_y'].' �');
		if( $this->getVar('size_house_z') ) $size.=$this->getElement($this->nbsp(4).'������ ��� �����:', $a['size_house_z'].' �');
		if( $this->getVar('size_house_z_full') ) $size.=$this->getElement($this->nbsp(4).'������ � ������:', $a['size_house_z_full'].' �');
		if( $size ) $size=$this->getElement('�������:', '').$size;
		$out.=$size;

        $square_ground = '';
        $ground = '';
		if( $this->getVar('square_ground') )
		{
			$square_ground = $a['square_ground'].' '.strtolower($a['square_ground_unit']);
		}
		else
		{
			if( $this->getVar('square_ground_m2') )$square_ground = $a['square_ground_m2'].' ��.�.';
		}
		if( $square_ground ) $ground.=$this->getElement($this->nbsp(4).'�������:', $square_ground);
		if( $this->getVar('size_ground_x') and $this->getVar('size_ground_y') ) $ground.=$this->getElement($this->nbsp(4).'��������, �.:', $a['size_ground_x'].' x '.$a['size_ground_y'].' �');
		if( $ground ) $ground=$this->getElement('��������� �������:', '').$ground;
		$out.=$ground;

		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>�������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getBuildingsBlock()
	{
		$a = &$this->object;
        $out = '';

		if( $this->getVar('bath_house') ) $out.=$this->getElement($this->nbsp(4).'����:', $a['bath_house']);
		if( $this->getVar('swimming_pool') ) $out.=$this->getElement($this->nbsp(4).'�������:', $a['swimming_pool']);
		if( $this->getVar('garage') ) $out.=$this->getElement($this->nbsp(4).'�����:', $a['garage']);
		if( $this->getVar('car_park_count') ) $out.=$this->getElement($this->nbsp(4).'���������� ���� ��� ����������:', $a['car_park_count']);
		if( $this->getVar('other_building') ) $out.=$this->getElement($this->nbsp(4).'������ ���������:', $a['other_building']);

		if( $out )
		{
			$out='<div class="domstor_object_buildings">
					<h3>���������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getTechnicBlock()
	{
		$a = &$this->object;
        $out = '';

        $communications = '';
		if( $this->getVar('phone') ) $communications.='�������, ';
		if( $this->getVar('internet') ) $communications.='��������, ';
		if( $this->getVar('cable_tv') ) $communications.='��������� ��, ';
		if( $this->getVar('door_phone') ) $communications.='�������, ';
		if( $this->getVar('gas') ) $communications.='����������, ';
		if( $this->getVar('satellite_tv') ) $communications.='����������� ��, ';
		if( $this->getVar('signalizing') ) $communications.='�������� ������������, ';
		if( $this->getVar('fire_prevention') ) $communications.='��������������� ������������, ';
		if( $communications )
		{
			$communications=substr($communications, 0, -2);
			$communications = $this->getElement('������������:', $communications);
			$show=true;
		}

        $san_tech = '';
		if( $this->getVar('toilet_count') ) $san_tech.=$this->getElement($this->nbsp(4).'���������� �������� � ����:', $a['toilet_count']);

		//�������������
        $electro = '';
		if( $this->getVar('electro_voltage') ) $electro=$this->getElement($this->nbsp(4).'����������', $a['electro_voltage'].' �');
		if( $this->getVar('electro_power') ) $electro=$this->getElement($this->nbsp(4).'��������', $a['electro_power'].' ���');
		if( $this->getVar('electro_reserve') ) $electro=$this->getElement('', '��������� ���������� ����������');
		if( $this->getVar('electro_not') ) $electro=$this->getElement('', '��� �������������');
		if( $electro )
		{
			$electro = $this->getElement('����������������:', '').$electro;
			$show=true;
		}

		$heat=$this->getElementIf('��������������:', $a['heat']);
		$water=$this->getElementIf('�������������:', $a['water']);
		$sewerage=$this->getElementIf('�����������:', $a['sewerage']);

        $construction = '';
		$construction.=$this->getElementIf($this->nbsp(4).'�������� �������� ����:', $this->getVar('material_wall'));
		$construction.=$this->getElementIf($this->nbsp(4).'�������� ����������:', $this->getVar('material_ceiling'));
		$construction.=$this->getElementIf($this->nbsp(4).'�������� ������� �����������:', $this->getVar('material_carying'));
		$construction.=$this->getElementIf($this->nbsp(4).'�������� ������:', $this->getVar('roof_material'));
		$construction.=$this->getElementIf($this->nbsp(4).'��� ������:', $this->getVar('roof_type'));
		$construction.=$this->getElementIf($this->nbsp(4).'���������:', $this->getVar('foundation'));


		if( $construction )
		{
			$construction = $this->getElement('����������� ������:', '').$construction;
			$show=true;
		}

        $windows = '';
		if( $this->getVar('window_material') ) $windows.=$this->getElement($this->nbsp(4).'�������� ���:', $a['window_material']);
		if( $this->getVar('window_glasing') ) $windows.=$this->getElement($this->nbsp(4).'��� ����������:', $a['window_glasing']);
		if( $this->getVar('window_opening') ) $windows.=$this->getElement($this->nbsp(4).'��� ����������:', $a['window_opening']);
		if( $windows )
		{
			$windows = $this->getElement('����:', '').$windows;
			$show=true;
		}

        $doors = '';
		if( $this->getVar('door_room') ) $doors.=$this->getElement($this->nbsp(4).'����� ������������:', $a['door_room']);

		//	�������
        $finish = '';
		if( $this->getVar('finish_ceiling') ) $finish.=$this->getElement($this->nbsp(4).'�������:', $a['finish_ceiling']);
		if( $this->getVar('finish_paul') ) $finish.=$this->getElement($this->nbsp(4).'����: ', $a['finish_paul']);
		if( $this->getVar('finish_partition') ) $finish.=$this->getElement($this->nbsp(4).'�����������: ', $a['finish_partition']);
		if( $this->getVar('facade') ) $finish.=$this->getElement($this->nbsp(4).'�����: ', $a['facade']);
		if( $finish )
		{
			$finish = $this->getElement('�������:', '').$finish;
			$show=true;
		}

		//	���������
        $state = '';
		if( $this->getVar('build_year') ) $state.=$this->getElement($this->nbsp(4).'��� ���������:', $a['build_year']);
		if( $this->getVar('wearout') ) $state.=$this->getElement($this->nbsp(4).'������� ������: ', $a['wearout'].'%');
		if( $this->getVar('state') ) $state.=$this->getElement($this->nbsp(4).'���������:', $a['state']);
		if( $state )
		{
			$state = $this->getElement('��������� �������:', '').$state;
			$show=true;
		}

		if( $show )
		{
			$out='<div class="domstor_object_technic">
					<h3>����������� ��������������</h3>
					<table>'.
						$communications.
						$san_tech.
						$electro.
						$heat.
						$water.
						$sewerage.
						$construction.
						$windows.
						$doors.
						$finish.
						$state.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getFurnitureBlock()
	{
		$a = &$this->object;
        $out = '';

        $obstanovka = '';
		if( $this->getVar('with_furniture') ) $obstanovka.='� �������, ';
		if( $this->getVar('garden') ) $obstanovka.='�������, ������ �� �������, ';
		if( $this->getVar('landscape_design') ) $obstanovka.='����������� ������, ';
		if( $this->getVar('improvement_territory') ) $obstanovka.='����������� ���������� �������������, ';
		if( $obstanovka )
		{
			$obstanovka=substr($obstanovka, 0, -2);
			$out.=$this->getElement('����������:', $obstanovka);
		}

		$out.=$this->getElementIf('������:', $this->getVar('fence'));

		if( $this->getVar('garbage_chute') ) $out.=$this->getElement('������������:', '�������');
		if( $this->getVar('security') ) $out.=$this->getElement('������:', $a['security']);
		if( $this->getVar('sale_with_parking') ) $parking=', �������� ������� ��������� � ������� ��� �����������';
		if( $this->getVar('parking') ) $out.=$this->getElement('��������:', $a['parking'].$parking);

		$road = '';
        $road.=$this->getElementIf($this->nbsp(4).'�������� �����:', $this->getVar('road_covering'));
		$road.=$this->getElementIf($this->nbsp(4).'��������� �������� �����:', $this->getVar('road_state'));
		if( $road )
		{
			$out.=$this->getElement('�������� �������:','').$road;
		}


		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>����������, ������������:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return '������ �� ������';
		$out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
                    $this->getSecondHead().
					$this->getDemandsBlock().
					/* '<p>'.
						$this->getAnnotation()
					.'</p> */
				'</div>'.
				$this->getImagesHtml($this->object).
				'<div class="domstor_object_common">'.
					$this->getLocationBlock().
					$this->getRoomsBlock().
					$this->getFloorsBlock().
				'</div>';
		$out.=$this->getRealizationBlock();
		$out.=$this->getSizeBlock();
		$out.=$this->getBuildingsBlock();
		$out.=$this->getTechnicBlock();
		$out.=$this->getFurnitureBlock();
		$out.=$this->getFinanceBlock();
		$out.=$this->getCommentBlock();
		$out.=$this->getContactBlock();
		$out.=$this->getDateBlock();
		$out.=$this->getNavigationHtml();
		return $out;
	}

}

class DomstorHouseDemand extends DomstorCommonDemand
{
	public function getPageTitle()
	{
		$a = &$this->object;

		$out = $this->getTitle();

		if( isset($a['Agency']) and isset($a['Agency']['name']) ) $out.=' &mdash; '.$a['Agency']['name'];

		return $out;
	}

	public function getTitle()
	{
        $out = $this->getOfferType2().' ';
        $out.= $this->getVar('house_type', '������� ���, �������');
        $city = $this->getVar('city');
        if( $city ) $out.= ' � '.$city;
		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['house_type'] ) $annotation.=', '.$a['house_type'];
		$rooms=$this->getFromTo($a['room_count_min'], $a['room_count_max'], ' ����.');
		if( $rooms ) $annotation.=', '.$rooms;
		$location=$this->getAddress();
		if( $location ) $annotation.=', '.$location;
		$price=$this->getPrice();
		if( $price ) $annotation.=', '.$price;
		if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getRoomsBlock()
	{
		$a=&$this->object;
		$rooms=$this->getFromTo($a['room_count_min'], $a['room_count_max'], ' ����.');
		if( $rooms )
		{
			$rooms='<div class="domstor_object_rooms">
					<h3>����� ������</h3><p>'.
					$rooms.
				'</p></div>';
		}
		return $rooms;
	}

	public function getSizeBlock()
	{
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('����� ������� ����:', $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max')), ' ��.�');
		$out.=$this->getElementIf('������� ����� ������:', $this->getFromTo($this->getVar('square_living_min'), $this->getVar('square_living_max')), ' ��.�');
		$out.=$this->getElementIF('������� ���������� �������:',  $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max')), ' '.$this->getVar('square_ground_unit'));
		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>������� ���� � �������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getBuildingsBlock()
	{
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('����:', $this->getVar('bath_house_want'));
		$out.=$this->getElementIf('�������:', $this->getVar('swimming_pool_want'));
		$out.=$this->getElementIf('�����:', $this->getVar('garage_want'));
		$out.=$this->getElementIf('���������� ���� ��� ���������� �� �����:', $this->getVar('car_park_count'));

		if( $out )
		{
			$out='<div class="domstor_object_buildings">
					<h3>���������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getTechnicBlock()
	{
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('�������:', $this->getVar('phone_want'));
		$out.=$this->getElementIf('����������:', $this->getVar('gas_want'));
		$out.=$this->getElementIf('��������� ��:', $this->getVar('cable_tv_want'));
		$out.=$this->getElementIf('����������� ��:', $this->getVar('satellite_tv_want'));
		$out.=$this->getElementIf('��������:', $this->getVar('internet_want'));
		$out.=$this->getElementIf('�������� ������������:', $this->getVar('signalizing_want'));
		$out.=$this->getElementIf('��������������� ������������:', $this->getVar('fire_prevention_want'));
		$out.=$this->getElementIf('������� � ����:', $this->getVar('toilet_want'));

		$electro = '';
        $electro.=$this->getElementIf($this->nbsp(4).'����������:', $this->getVar('electro_voltage'));
		$electro.=$this->getElementIf($this->nbsp(4).'�������� �� �����:', $this->getVar('electro_power'));
		if( $electro ) $out.=$this->getElement('����������������:', '').$electro;

		$out.=$this->getElementIf('��������������:', $this->getVar('heat'));
		$out.=$this->getElementIf('�������������:', $this->getVar('water'));
		$out.=$this->getElementIf('�����������:', $this->getVar('sewerage'));

		$out.=$this->getElementIf('��������� ������� �� ����� ���:', $this->getVar('state'));


		if( $out )
		{
			$out='<div class="domstor_object_technic">
					<h3>����������� ��������������</h3>
					<table>'.
						$out.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return '������ �� �������';
		$out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
                     $this->getSecondHead().
					//'<p>'.$this->getAnnotation().'</p>'.
				'</div>
				<div class="domstor_object_common">'.
					$this->getLocationBlock().
					$this->getRoomsBlock().
					//$this->getFloorsBlock().
				'</div>';
		$out.=$this->getSizeBlock();
		$out.=$this->getBuildingsBlock();
		$out.=$this->getTechnicBlock();
		$out.=$this->getFinanceBlock();
		$out.=$this->getCommentBlock();
		$out.=$this->getContactBlock();
		$out.=$this->getDateBlock();
		$out.=$this->getNavigationHtml();
		return $out;
	}
}

//GARAGE******************************************************

class DomstorGarage extends DomstorCommonObject
{
	public function getPageTitle()
	{
		$a = &$this->object;

		$out = $this->getTitle();

		if( $a['Agency']['name'] ) $out.=' &mdash; '.$a['Agency']['name'];

		return $out;
	}

	public function getTitle()
	{
		$a = &$this->object;

		$out = $this->getOfferType2().' ';

		$type = $a['garage_type']? strtolower($a['garage_type']) : '�����';
		$out.= $type.' ';

		if( $a['city'] ) $out.= '� '.$a['city'];

		$addr = $this->getStreetBuilding();
		$district = ($a['district_parent'] == '��������' or $a['district'] == '��������' )? ', '.$a['district'] : '';
		$out.= $district.($addr? ', '.$addr : ($a['address_note']? ', '.$a['address_note'] : '' ));

		if( isset($a['cooperative_name']) and $a['cooperative_name'] ) $out.= ' ���������� '.$a['cooperative_name'];

		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['garage_type'] ) $annotation.=', '.$a['garage_type'];
		if( $a['size_x'] and $a['size_y'] ) $annotation.=', '.$a['size_x'].' x '.$a['size_y'].' �';
		$address=parent::getAddress();
		if( $address ) $annotation.=', '.$address;
		$price=$this->getPrice($a['price_full'], $a['price_currency'], $a['rent_full'], $a['rent_period'], $a['rent_currency']);
		if( $price ) $annotation.=', '.$price;
		if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getAddress()
	{
		$out = parent::getAddress();
		$space = $out? ', ' : '';
		if( $this->getVar('cooperative_name') ) $out.= $space.$this->getVar('cooperative_name');
		return $out;
	}

	public function getAllocationBlock()
	{
        $floor = '';
		$floor.=$this->getElementIf('��� ����������:', $this->getVar('placing_type'));
		$floor.=$this->getElementIf('���� ������������ �������:', $this->getVar('object_floor'), ' ����');
		$floor.=$this->getElementIf('������ � ��������:', $this->getVar('building_floor'));
		if( $floor )
		{
			$floor='<div class="domstor_object_allocation">
					<h3>������������</h3><table>'.
					$floor.
				'</table></div>';
		}
		return $floor;
	}

	public function getSizeBlock()
	{
		$out = '';
        $out.= $this->getElementIf('������:', $this->getVar('size_x'), ' �' );
		$out.= $this->getElementIf('�����:', $this->getVar('size_y'), ' �' );
		$out.= $this->getElementIf('������:', $this->getVar('size_z'), ' �' );
		$out.= $this->getElementIf('�������:', $this->getVar('square'), ' ��.�.' );

        $gate_size = '';
		$gate_size.= $this->getElementIf($this->nbsp(4).'������:', $this->getVar('gate_height'), ' �' );
		$gate_size.= $this->getElementIf($this->nbsp(4).'������:', $this->getVar('gate_width'), ' �' );
		if( $gate_size ) $out.= $this->getElement('������ �����:','').$gate_size;

		$out.= $this->getElementIf('���������� ����������:', $this->getVar('car_count'));
		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>�������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getTechnicBlock()
	{
        $out = '';

		$cellar = $this->getElementIf('������:', $this->getVar('cellar'));
		$repair_pit =$this->getElementIf('�������������� ��������� ���:', $this->getVar('repair_pit'));
		$gate_type = $this->getElementIf('��� �����:', $this->getVar('gate_type'));

        $electro = '';
		$electro.= $this->getElementIf($this->nbsp(8).'����������', $this->getVar('electro_voltage'), ' �');
		$electro.= $this->getElementIf($this->nbsp(8).'��������', $this->getVar('electro_power'), ' ���');
		if( $this->getVar('electro_reserve') ) $electro.= $this->getElement('', '��������� ���������� ����������');
		if( $this->getVar('electro_not') ) $electro.= $this->getElement('', '��� �������������');
		if( $electro ) $electro = $this->getElement($this->nbsp(4).'����������������:', '').$electro;

		$communications = '';
		$communications.= $this->getElementIf($this->nbsp(4).'��������������:', $this->getVar('heat'));
		$communications.= $this->getElementIf($this->nbsp(4).'����������:', $this->getVar('ventilation'));
		$communications.= $this->getElementIf($this->nbsp(4).'�������� ������������:', $this->getVar('signalizing'));
		$communications.= $this->getElementIf($this->nbsp(4).'���������������:', $this->getVar('video_tracking'));
		$communications.= $this->getElementIf($this->nbsp(4).'��������������� ������������:', $this->getVar('fire_signalizing'));
		$communications.= $this->getElementIf($this->nbsp(4).'������� �������������:', $this->getVar('fire_prevention'));

		$show = false;
        if( $communications )
		{
			$communications = $this->getElement('������������:', '').$communications;
			$show = true;
		}

        $construction = '';
		$construction.=$this->getElementIf($this->nbsp(4).'�������� �������� ����:', $this->getVar('material_wall'));
		$construction.=$this->getElementIf($this->nbsp(4).'�������� ����������:', $this->getVar('material_ceiling'));


		if( $construction )
		{
			$construction = $this->getElement('����������� ��������:', '').$construction;
			$show=true;
		}

		//	���������
        $state = '';
		if( $this->getVar('build_year') ) $state.= $this->getElement($this->nbsp(4).'��� ���������:', $this->getVar('build_year'));
		if( $this->getVar('wearout') ) $state.= $this->getElement($this->nbsp(4).'������� ������: ', $this->getVar('wearout').'%');
		if( $this->getVar('state') ) $state.= $this->getElement($this->nbsp(4).'���������:', $this->getVar('state'));
		if( $state )
		{
			$state = $this->getElement('��������� �������:', '').$state;
			$show = true;
		}

		if( $show )
		{
			$out = '<div class="domstor_object_technic">
					<h3>����������� ��������������</h3>
					<table>'.
						$cellar.
						$repair_pit.
						$gate_type.
                        $electro.
						$communications.
						$construction.
						$state.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getFurnitureBlock()
	{
		$a=&$this->object;
        $out = '';

		$out.= $this->getElementIf('������ ����������:', $this->getVar('territory_security'));
		$out.= $this->getElementIf('����� ��������� ���:', $this->getVar('public_repair_pit'));
		$out.= $this->getElementIf('���������� � �����������:', $this->getVar('auto_service'));
		$out.= $this->getElementIf('��������� � �����������:', $this->getVar('auto_washing'));

        $road = '';
		$road.= $this->getElementIf($this->nbsp(4).'�������� �������� � �����������:', $this->getVar('road_covering_inside'));
		$road.= $this->getElementIf($this->nbsp(4).'�������� ����� �� �������� � �����������:', $this->getVar('road_covering'));
		$road.= $this->getElementIf($this->nbsp(4).'��������� �������� �����:', $this->getVar('road_state'));

		if( $road )
		{
			$out.=$this->getElement('�������� �������:','').$road;
		}


		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>����������, ������������:</h3>
					<table>'.$out.'</table>
				</div>';
		}

		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return '������ �� ������';
		$out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
                    $this->getSecondHead().
				'</div>'.
				$this->getImagesHtml($this->object).
				'<div class="domstor_object_common">'.
					$this->getLocationBlock().
				'</div>';
		$out.=$this->getAllocationBlock();
		$out.=$this->getRealizationBlock();
		$out.=$this->getSizeBlock();
		$out.=$this->getTechnicBlock();
		$out.=$this->getFurnitureBlock();
		$out.=$this->getFinanceBlock();
		$out.=$this->getCommentBlock();
		$out.=$this->getContactBlock();
		$out.=$this->getDateBlock();
		$out.=$this->getNavigationHtml();
		return $out;
	}

}

class DomstorGarageDemand extends DomstorCommonDemand
{
	public function getPageTitle()
	{
		$a = &$this->object;

		$out = $this->getTitle();

		if( isset($a['Agency']) and isset($a['Agency']['name']) ) $out.=' &mdash; '.$a['Agency']['name'];

		return $out;
	}

	public function getTitle()
	{
		$out = $this->getOfferType2().' ';
        $out.= $this->getVar('garage_type', '�����');
        $city = $this->getVar('city');
        if( $city ) $out.= ' � '.$city;
		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['garage_type'] ) $annotation.=', '.$a['garage_type'];
		if( $a['size_x'] and $a['size_y'] ) $annotation.=', �� ����� '.$a['size_x'].' x '.$a['size_y'].' �';
		$location=$this->getAddress();
		if( $location ) $annotation.=', '.$location;
		$price=$this->getPrice();
		if( $price ) $annotation.=', '.$price;
		if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getAllocationBlock()
	{
		$a=&$this->object;
		if( $a['placing_type'] ) $out='<p>'.$a['placing_type'].'</p>';
		if( $out )
		{
			$out='<div class="domstor_object_rooms">
					<h3>������������</h3>'.
					$out.
				'</div>';
		}
		return $out;
	}

	public function getSizeBlock()
	{
        $out = '';
		$out.= $this->getElementIf('������ �� �����:', $this->getVar('size_x'), ' �' );
		$out.= $this->getElementIf('����� �� �����:', $this->getVar('size_y'), ' �' );
		$out.= $this->getElementIf('������ �� �����:', $this->getVar('size_z'), ' �' );
		$out.= $this->getElementIf('������� �� �����:', $this->getVar('square'), ' ��.�.' );

        $gate_size = '';
		$gate_size.= $this->getElementIf($this->nbsp(4).'������ �� �����:', $this->getVar('gate_height'), ' �' );
		$gate_size.= $this->getElementIf($this->nbsp(4).'������ �� �����:', $this->getVar('gate_width'), ' �' );

        if( $gate_size ) $out.= $this->getElement('������ �����:','').$gate_size;

        $out.= $this->getElementIf('���������� ����������:', $this->getFromTo($this->getVar('car_count_min'), $this->getVar('car_count_max')));
		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>�������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getTechnicBlock()
	{
        $out = '';

		$out.= $this->getElementIf('������� �������:', $this->getVar('cellar_want'));
		$out.= $this->getElementIf('������� �������������� ��������� ���:', $this->getVar('repair_pit_want'));

        $electro = '';
		$electro.= $this->getElementIf($this->nbsp(8).'����������', $this->getVar('electro_voltage'), ' �');
		$electro.= $this->getElementIf($this->nbsp(8).'�������� �� �����', $this->getVar('electro_power'), ' ���');
		if( $electro ) $electro = $this->getElement($this->nbsp(4).'����������������:', '').$electro;

		$communications = $electro;

		$communications.= $this->getElementIf($this->nbsp(4).'��������������:', $this->getVar('heat_want'));
		$communications.= $this->getElementIf($this->nbsp(4).'����������:', $this->getVar('ventilation_want'));
		$communications.= $this->getElementIf($this->nbsp(4).'�������� ������������:', $this->getVar('signalizing_want'));
		$communications.= $this->getElementIf($this->nbsp(4).'���������������:', $this->getVar('video_tracking_want'));
		$communications.= $this->getElementIf($this->nbsp(4).'��������������� ������������:', $this->getVar('fire_signalizing_want'));
		$communications.= $this->getElementIf($this->nbsp(4).'������� �������������:', $this->getVar('fire_prevention_want'));

		if( $communications )
		{
			$communications = $this->getElement('������������:', '').$communications;
			$out.= $communications;
		}

		if( $this->getVar('state') ) $out.=$this->getElement('��������� ������� �� ����� ���:', $this->getVar('state'));


		if( $out )
		{
			$out='<div class="domstor_object_technic">
					<h3>����������� ��������������</h3>
					<table>'.
						$out.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getFurnitureBlock()
	{
        $out = '';

		$out.= $this->getElementIf('������ ����������:', $this->getVar('territory_security_want'));
		$out.= $this->getElementIf('����� ��������� ���:', $this->getVar('public_repair_pit_want'));
		$out.= $this->getElementIf('���������� � �����������:', $this->getVar('auto_service_want'));
		$out.= $this->getElementIf('��������� � �����������:', $this->getVar('auto_washing_want'));

		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>����������, ������������:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return '������ �� �������';
		$out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
                     $this->getSecondHead().
					//'<p>'.$this->getAnnotation().'</p>'.
				'</div>
				<div class="domstor_object_common">'.
					$this->getLocationBlock().
					$this->getAllocationBlock().
					//$this->getFloorsBlock().
				'</div>';
		$out.=$this->getSizeBlock();
		$out.=$this->getTechnicBlock();
		$out.=$this->getFurnitureBlock();
		$out.=$this->getFinanceBlock();
		$out.=$this->getCommentBlock();
		$out.=$this->getContactBlock();
		$out.=$this->getDateBlock();
		$out.=$this->getNavigationHtml();
		return $out;
	}

}

//LAND******************************************************

class DomstorLand extends DomstorCommonObject
{
	public function getPageTitle()
	{
		$a = &$this->object;

		$out = $this->getTitle();

		if( $a['Agency']['name'] ) $out.=' &mdash; '.$a['Agency']['name'];

		return $out;
	}

	public function getTitle()
	{
		$a = &$this->object;

		$out = $this->getOfferType2().' ';

		$type = $a['land_type']? lcfirst($a['land_type']) : '��������� �������';
		$out.= $type.' ';

		if( $a['city'] ) $out.= '� '.$a['city'];

		$addr = $this->getStreetBuilding();
		$district = ($a['district_parent'] == '��������' or $a['district'] == '��������' )? ', '.$a['district'] : '';
		$out.= $district.($addr? ', '.$addr : ($a['address_note']? ', '.$a['address_note'] : '' ));

		if( isset($a['cooperative_name']) and $a['cooperative_name'] ) $out.=', '.$a['cooperative_name'];

		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['land_type'] ) $annotation.=', '.$a['land_type'];
		if( $a['square_ground'] )
		{
			$annotation.=', '.$a['square_ground'];
			if( $a['square_ground_unit'] ) $annotation.=' '.$a['square_ground_unit'];
		}
		$address=$this->getAddress();
		if( $address ) $annotation.=', '.$address;
		$price=$this->getPrice();
		if( $price ) $annotation.=', '.$price;
		if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getSizeBlock()
	{
        $out = '';
		$out.= $this->getElementIf('�������:', $this->getVar('square_ground'), ' '.$this->getVar('square_ground_unit') );

        if( $this->getVar('size_ground_x') and $this->getVar('size_ground_y') )
            $out.=$this->getElement('�� ���������:', $this->getVar('size_ground_x').' x '.$this->getVar('size_ground_y').' �' );

        if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>�������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getBuildingsBlock()
	{
		$a=&$this->object;
        $out = '';
		$out.= $this->getElementIf('��� ���������:', $this->getVar('living_building'));
		$out.= $this->getElementIf('����� ����� ������:', $this->getVar('room_count'));
		$out.= $this->getElementIf('���������� ������:', $this->getVar('building_floor'));
		$out.= $this->getElementIf('�������:', $this->getVar('square_house'));
		$out.= $this->getElementIf('���������:', $this->getVar('heat'));
		$out.= $this->getElementIf('��������� ��������:', $this->getVar('state'));

        $other = '';
		if( $this->getVar('bath_house') ) $other.= '����, ';
		if( $this->getVar('swimming_pool') ) $other.= '�������, ';
		if( $this->getVar('garage') ) $other.= '�����, ';
		if( $this->getVar('other_building') ) $other.= '������, ';
		if( $other )
		{
			$other= substr($other, 0, -2);
			$out.= $this->getElement('������������� ���������:', $other);
		}

		if( $out )
		{
			$out='<div class="domstor_object_buildings">
					<h3>���������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getTechnicBlock()
	{
        $out = '';

        $electro = '';
		$electro.= $this->getElementIf($this->nbsp(8).'����������', $this->getVar('electro_voltage'), ' �');
		$electro.= $this->getElementIf($this->nbsp(8).'��������', $this->getVar('electro_power'), ' ���');
		if( $this->getVar('electro_reserve') ) $electro.= $this->getElement('', '��������� ���������� ����������');
		if( $this->getVar('electro_not') ) $electro.= $this->getElement('', '��� �������������');
		if( $electro ) $electro = $this->getElement($this->nbsp(4).'����������������:', '').$electro;

        $show = false;
		$communications = $electro;
        $water = '';
		if( $this->getVar('water_basin') ) $water=' (����������� ���������� �� ���������� �������)';
		$communications.= $this->getElementIf($this->nbsp(4).'�������������:', $this->getVar('water').$water);
		$communications.= $this->getElementIf($this->nbsp(4).'��� ������������ ���������� ������������:', $this->getVar('communications_year'));
		if( $communications )
		{
			$communications = $this->getElement('������������:', '').$communications;
			$show = true;
		}

		//	���������
        $state = '';
		$state.= $this->getElementIf($this->nbsp(4).'������� �����:', $this->getVar('height_difference'), ' �');
		$state.= $this->getElementIf($this->nbsp(4).'������ ������:', $this->getVar('coat_structure'));
		$state.= $this->getElementIf($this->nbsp(4).'������ ��������� ���:', $this->getVar('ground_water_height'));
		$state.= $this->getElementIf($this->nbsp(4).'������� ��������� ������:', $this->getVar('karstic_hole'));

		if( $state )
		{
			$state = $this->getElement('��������� �������:', '').$state;
			$show = true;
		}

		if( $show )
		{
			$out='<div class="domstor_object_technic">
					<h3>����������� ��������������</h3>
					<table>'.
						$communications.
						$state.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getFurnitureBlock()
	{
        $out = '';

		$out.= $this->getElementIf('����������:', $this->getVar('fence'));
		$out.= $this->getElementIf('����������� �� �������:', $this->getVar('remote_water'), ' �');
		$out.= $this->getElementIf('������������ ����:', $this->getVar('water_conservation_zone'), ' �');
		$out.= $this->getElementIf('����������� �� ������� �������:', $this->getVar('remote_forest'), ' �');
		$out.= $this->getElementIf('������� ����������� �� �������:', $this->getVar('forest_cover'));

        $road = '';
		$road.= $this->getElementIf($this->nbsp(4).'����������� ������� �� ����������:', $this->getVar('remote_highway'), ' �');
		$road.= $this->getElementIf($this->nbsp(4).'�������� ���������� ������:', $this->getVar('road_covering'));
		$road.= $this->getElementIf($this->nbsp(4).'��������� �������� �����:', $this->getVar('road_state'));
		$road.= $this->getElementIf($this->nbsp(4).'����������� ������� �����:', $this->getVar('road_winter'));

		if( $road )
		{
			$out.= $this->getElement('�������� �������:','').$road;
		}
		$out.= $this->getElementIf('��� ���������� ���������:', $this->getVar('settlement_type'));

		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>����������, ������������:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return '������ �� ������';
		$out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
                    $this->getSecondHead().
				'</div>'.
				$this->getImagesHtml($this->object).
				'<div class="domstor_object_common">'.
					$this->getLocationBlock().
				'</div>';
		$out.=$this->getRealizationBlock();
		$out.=$this->getSizeBlock();
		$out.=$this->getBuildingsBlock();
		$out.=$this->getTechnicBlock();
		$out.=$this->getFurnitureBlock();
		$out.=$this->getFinanceBlock();
		$out.=$this->getCommentBlock();
		$out.=$this->getContactBlock();
		$out.=$this->getDateBlock();
		$out.=$this->getNavigationHtml();
		return $out;
	}

}

class DomstorLandDemand extends DomstorCommonDemand
{
	public function getPageTitle()
	{
		$a = &$this->object;

		$out = $this->getTitle();

		if( isset($a['Agency']) and isset($a['Agency']['name']) ) $out.= ' &mdash; '.$a['Agency']['name'];

		return $out;
	}

	public function getLocation()
	{
        $out = '';

		if( $this->in_region )
		{
			//if( $a['address_note'] ) $out.=$a['address_note'].', ';
			if( $this->getVar('cooperative_name') ) $out.= $this->getVar('cooperative_name').', ';
			if( $this->getVar('city') ) $out.= $this->getVar('city').', ';
			if( $this->getVar('region') ) $out.= $this->getVar('region').', ';
		}
		else
		{
			if( $this->getVar('street') ) $out.= $this->getVar('street').', ';
			if( $this->getVar('cooperative_name') ) $out.= $this->getVar('cooperative_name').', ';
			if( $this->getVar('district') ) $out.= $this->getVar('district').', ';
			if( $this->getVar('city') ) $out.= $this->getVar('city').', ';
		}
		$out = substr($out, 0, -2);
		return $out;
	}

	public function getTitle()
	{
		$out = $this->getOfferType2().' ';
        $out.= $this->getVar('land_type', '������� �������');
        $city = $this->getVar('city');
        if( $city ) $out.= ' � '.$city;
		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $this->getVar('land_type') ) $annotation.=', '.$this->getVar('land_type');
		$annotation.= $this->getIf($this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit')),', ');
		$location = $this->getLocation();
		if( $location ) $annotation.= ', '.$location;
		$price = $this->getPrice($a['price_full'], $a['price_currency'], $a['rent_full'], $a['rent_period'], $a['rent_currency']);
		if( $price ) $annotation.=', '.$price;
		if( $a['note_addition'] ) $annotation.=', '.$a['note_addition'];
		return $annotation;
	}

	public function getSizeBlock()
	{
		$out = $this->getElementIf('�������:', $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit')));
		if( $out )
		{
			$out = '<div class="domstor_object_size">
					<h3>�������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getBuildingsBlock()
	{
        $out = '';
		$out.=$this->getElementIf('��������� ����� ���������:', $this->getVar('living_building'));
		$out.=$this->getElementIf('���������:', $this->getVar('heat_want'));
		$out.=$this->getElementIf('��������� �������� �� ����� ���:', $this->getVar('state'));

        $other = '';
		if( $this->getVar('bath_house') ) $other.='����, ';
		if( $this->getVar('swimming_pool') ) $other.='�������, ';
		if( $this->getVar('garage') ) $other.='�����, ';
		if( $other )
		{
			$other = substr($other, 0, -2);
			$out.= $this->getElement('������������� ���������:', $other);
		}


		if( $out )
		{
			$out='<div class="domstor_object_buildings">
					<h3>���������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getTechnicBlock()
	{
        $out = '';

        $electro = '';
		$electro.=$this->getElementIf($this->nbsp(4).'����������', $this->getVar('electro_voltage'), ' �');
		$electro.=$this->getElementIf($this->nbsp(4).'�������� �� �����', $this->getVar('electro_power'), ' ���');

		if( $electro ) $out = $this->getElement('����������������:', '').$electro;

		$out.= $this->getElementIf('�������������:', $this->getVar('water_want'));

		if( $out )
		{
			$out='<div class="domstor_object_technic">
					<h3>����������� ��������������</h3>
					<table>'.
						$out.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getFurnitureBlock()
	{
        $out = '';

        $road = '';
		$road.= $this->getElementIf($this->nbsp(4).'����������� ������� �� ���������� �� �����:', $this->getVar('remote_highway'), ' �');
		$road.= $this->getElementIf($this->nbsp(4).'�������� ���������� ������:', $this->getVar('road_covering'));
		$road.= $this->getElementIf($this->nbsp(4).'����������� ������� �����:', $this->getVar('road_winter'));
		if( $road )
		{
			$out.= $this->getElement('�������� �������:','').$road;
		}
		$out.= $this->getElementIf('��� ���������� ���������:', $this->getVar('settlement_type'));

		if( $out )
		{
			$out = '<div class="domstor_object_furniture">
					<h3>����������, ������������:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return '������ �� �������';
		$out = '	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
                     $this->getSecondHead().
					//'<p>'.$this->getAnnotation().'</p>'.
				'</div>
				<div class="domstor_object_common">'.
					$this->getLocationBlock().
				'</div>';
		$out.= $this->getSizeBlock();
		$out.= $this->getBuildingsBlock();
		$out.= $this->getTechnicBlock();
		$out.= $this->getFurnitureBlock();
		$out.= $this->getFinanceBlock();
		$out.= $this->getCommentBlock();
		$out.= $this->getContactBlock();
		$out.= $this->getDateBlock();
		$out.= $this->getNavigationHtml();
		return $out;
	}

}

//COMMERCE******************************************************

class DomstorCommerce extends DomstorCommonObject
{
	public function getPageTitle()
	{
		$a = &$this->object;
		$out = $this->getTitle();
		$out.= $this->getIf(strtolower($this->getPurpose()), ' (����������: ', ')');

		$out.= $this->getIf( $a['Agency']['name'], ' &mdash; ');
		return $out;
	}

	public function getTitle()
	{
		$a = &$this->object;
		$out = $this->getOfferType2().' ������� ��������� ';

		if( $a['city'] ) $out.= '� '.$this->getVar('city');

		$addr = $this->getStreetBuilding();
		$district = ($this->getVar('district_parent') == '��������' or $this->getVar('district') == '��������' )? ', '.$this->getVar('district') : '';
		$out.= $district.($addr? ', '.$addr : ($this->getVar('address_note')? ', '.$this->getVar('address_note') : '' ));

		return $out;
	}

	public function getSquareGround()
	{
		$a=&$this->object;
		return $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit'), '������� ���������� ������� ');
	}

	public function getSquareHouse()
	{
		$a=&$this->object;
		return $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max'), ' ��.�',  '������� ��������� ');
	}

	public function getSquare()
	{
		$a=&$this->object;
		if( $a['Purposes'][1009] )
		{
			if( count($a['Purposes'])==1 )
			{
				$out.=$this->getSquareGround();
			}
			else
			{
				$out=$this->getIf($this->getSquareHouse(), '', ', ');
				$out.=$this->getSquareGround();
			}
		}
		else
		{
			$out=$this->getSquareHouse();
		}
		return $out;
	}

	public function getFloor($min, $max)
	{
		if( isset($min) )
		{
			if( isset($max) )
			{
				if($min==$max)
				{
					if( $min!='0' )
					{
						$out=$min;
					}
				}
				else
				{
					$out=$min.' &ndash; '.$max;
				}
			}
			else
			{
				$out=$min;
			}
		}
		else
		{
			if( isset($max) )
			{
				if( $max!='0' )
				{
					$out=$max;
				}
			}
		}
		return $out;
	}

	public function getObjectFloor($min, $max)
	{
		$min_flag = FALSE;
		$max_flag = FALSE;
		$out = '';

		if( isset($min) and $min != '' ) $min_flag = TRUE;
		if( isset($max) and $max != '' ) $max_flag = TRUE;

		if( $min_flag and $max_flag )
		{
			if( $min == $max )
			{
				$out = ($min == '0')? '������' : $min;
			}
			else
			{
				$out = '��&nbsp;'.$min.' ��&nbsp;'.$max;
				$out = str_replace('0', '������', $out);
			}
		}
		elseif( $min_flag or $max_flag )
		{
			if( $min_flag )
			{
				$out = '��&nbsp;'.$min;
			}
			else
			{
				$out = '��&nbsp;'.$max;
			}
			$out = str_replace('0', '������', $out);
		}

		return $out;
	}

	public function getAnnotation()
	{
		$a = $this->object;
		$annotation=$this->getOfferType($this->action);
		$annotation.=$this->getIf( $this->getPurpose(), ', ');
		if( $this->getVar('complex_id') )$annotation.=', � ������� �������������� ���������';
		$annotation.=$this->getIf( $this->getSquare(), ', ');
		$annotation.=$this->getIf( $this->getAddress(), ', ');
		$annotation.=$this->getIf( $this->getPrice(), ', ');
		$annotation.=$this->getIf( $this->getVar('note_addition'), ', ');
		return $annotation;
	}

	public function getDelayBlock()
	{
		$a = &$this->object;
        $out = '';
		if( $this->getVar('delay_sale_dt') ) $out.='<p>����������� ������� � '.date('d.m.Y', strtotime($this->getVar('delay_sale_dt'))).'</p>';
		if( $this->getVar('delay_rent_dt') ) $out.='<p>����������� ������ � '.date('d.m.Y', strtotime($this->getVar('delay_rent_dt'))).'</p>';
		if( $out )
		{
			$out='<div class="domstor_object_delay">
					<h3>����������� �����������</h3>'.
					$out.
				'</div>';
		}
		return $out;
	}

	public function getComplexBlock()
	{
		$a = $this->object;
        $out ='';
		if( isset($a['complex']) and $a['complex'] )
		{
			$this->object = $a['complex'];
			$out = '������ <a href="'.$this->getCommerceUrl($this->object['id']).'" class="domstor_link">'.$this->object['code'].'</a>, '.$this->getAnnotation();
			$this->object = $a;
		}

		if( $out )
		{
			$out='<div class="domstor_object_complex">
					<h3>� ������� �������������� ���������</h3>'.
					$out.
				'</div>';
		}

		return $out;
	}

	public function getComplexObjectsBlock()
	{
		$a = $this->object;
        $out = '';
		if( isset($a['ComplexObjects']) and is_array($a['ComplexObjects']) )
		{
			foreach( $a['ComplexObjects'] as $object )
			{
				$this->object=$object;
				$out.='<p><a href="'.$this->getCommerceUrl($this->object['id']).'" class="domstor_link">������ '.$object['code'].'</a>, '.$this->getAnnotation().'</p>';
			}
			$this->object=$a;
		}

		if( $out )
		{
			$out='<div class="domstor_object_complexobj">
					<h3>������� �������������� ���������</h3>'.
					$out.
				'</div>';
		}

		return $out;
	}

	public function getPurposeBlock()
	{
		$a = &$this->object;
        $out = '';

		if( isset($a['Purposes'][1009]) or isset($a['class']) )
		{
			$purp=array();
			for($i=1013; $i<1022; $i++)
			{
				if( $a['Purposes'][$i] ) $purp[]=$a['Purposes'][$i];
			}
			$out=implode(', ', $purp);
			$out=$this->getElementIf('��������� �������:', $out);
			$out.=$this->getElementIf('����� �������:', $this->getVar('class'));
			if( $out )
			{
				$out='<div class="domstor_object_purpose">
						<h3>����������</h3><table>'.
						$out.
					'</table></div>';
			}
		}
		return $out;
	}

	public function getSizeBlock()
	{
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('������� ���������:', $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max'), ' ��.�') );
		$out.=$this->getElementIf('������� ���������� �������:', $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit')) );
		$out.=$this->getElementIf('������ ���������:', $this->getFromTo($this->getVar('height_min'), $this->getVar('height_max'), ' �') );
		$out.=$this->getElementIf('���������� �����:', $this->getVar('gate_count'));
		$out.=$this->getElementIf('������������ ������ �����:', $this->getVar('gate_height'), ' �');
		$out.=$this->getElementIf('������������ ������ �����:', $this->getVar('gate_width'), ' �');
		$out.=$this->getElementIf('���������� ������:', $this->getVar('door_count'));
		$out.=$this->getElementIf('���������� ����������� ����:', $this->getVar('load_window_count'));

		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>�������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getAllocationSubBlock()
	{
		$a=&$this->object;
		if( $this->getVar('placing_features_id')==-1001 ) return;
		if( $this->getVar('placing_features_id')==1116 )
		{
			$name='� ��������� ������';
		}
		else
		{
			$name=$this->getVar('placing_features');
		}
		$pt=array();
        $inside = $out = '';
		if( $this->getVar('placing_type') ) $pt[]=$this->getVar('placing_type');
		if( $this->getVar('placing_type2') ) $pt[]=$this->getVar('placing_type2');
		$pt=implode(', ', $pt);
		if( $pt ) $pt=' ('.$pt.')';
		if( $this->getVar('inside_building') ) $inside=', ��������� ������ ������ ('.$this->getVar('inside_building').')';
		$out=$this->getElementIf('����������� ����������:', $name.$pt.$inside);
		return $out;
	}

	public function getAllocationBlock()
	{
        $out = '';
		$out.=$this->getElementIf('���� �������:',$this->getObjectFloor($this->getVar('object_floor_min'), $this->getVar('object_floor_max')));
		$out.=$this->getElementIf('��������� ������:', $this->getFromTo($this->getVar('building_floor_min'), $this->getVar('building_floor_max')) );
		if( $this->getVar('ground_floor') ) $out.=$this->getElement('', '������� ��������� ����');
		$out.=$this->getAllocationSubBlock();
		if( $out )
		{
			$out='<div class="domstor_object_allocation">
					<h3>���������� �������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getTechnicBlock()
	{
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('���������� �����:', $this->getVar('phone_count'));
		$out.=$this->getElementIf('��������-�����������:', $this->getVar('internet_count'));

        $electro = '';
		$electro.=$this->getElementIf($this->nbsp(4).'����������', $this->getVar('electro_voltage'), ' �');
		$electro.=$this->getElementIf($this->nbsp(4).'��������', $this->getVar('electro_power'), ' ���');
		$electro.=$this->getElementIf($this->nbsp(4).'����������� ���������� �������� ��', $this->getVar('electro_power_up'), ' ���');
		if( $this->getVar('electro_reserve') ) $electro.=$this->getElement('', '���� ��������� ���������� ����������');
		if( $this->getVar('electro_not') ) $electro.=$this->getElement('', '��� �������������');
		if( $this->getVar('electro_allow') ) $electro.=$this->getElement('', '�������� ������������ ��� �����������');
		if( $electro ) $electro = $this->getElement('����������������:', '').$electro;
		$out.=$electro;


		$out.=$this->getElementIf('��������������:', $this->getVar('heat'));
		if( $this->getVar('heat_control') ) $out.=$this->getElementIf('', '������������ ������������� �����');
		$out.=$this->getElementIf('�������������:', $this->getVar('water'));
		if( $this->getVar('water_reserve') ) $out.=$this->getElementIf('', '��������� ��������');
		$out.=$this->getElementIf('�����������:', $this->getVar('sewerage'));
		$out.=$this->getElementIf('����������:', $this->getVar('ventilation'));
		$out.=$this->getElementIf('�������������:', $this->getVar('gas'));

        $construction = '';
		$construction.=$this->getElementIf($this->nbsp(4).'�������� �������� ����:', $this->getVar('material_wall'));
		$construction.=$this->getElementIf($this->nbsp(4).'�������� ����������:', $this->getVar('material_ceiling'));
		$construction.=$this->getElementIf($this->nbsp(4).'�������� ������� �����������:', $this->getVar('material_carrying'));
		$construction.=$this->getElementIf($this->nbsp(4).'����������� ��� ������:', $this->getVar('pillar_step'), ' �');
		$construction.=$this->getElementIf($this->nbsp(4).'�������� �����:', $this->getVar('paul_coating'));
		$construction.=$this->getElementIf($this->nbsp(4).'����� �����:', $this->getVar('paul_bias'));
		$construction.=$this->getElementIf($this->nbsp(4).'�������� �� ���:', $this->getVar('paul_loading'), ' ��/��.�');
		if( $construction )	$out.=$this->getElement('����������� ��������:', '').$construction;

		//	���������
        $state = '';
		$state.=$this->getElementIf($this->nbsp(4).'��� ���������:', $this->getVar('build_year'));
		$state.=$this->getElementIf($this->nbsp(4).'������� ������: ', $this->getVar('wearout'), '%');
		$state.=$this->getElementIf($this->nbsp(4).'���������:', $this->getVar('state'));
		if( $state ) $out.=$this->getElement('��������� �������:', '').$state;

        $ice = '';
		$ice.=$this->getElementIf($this->nbsp(4).'����������� ������������:', $this->getVar('refrigerator'));
		$ice.=$this->getElementIf($this->nbsp(4).'������������� �����:', $this->getFromTo($this->getVar('refrigerator_temperature_min'), $this->getVar('refrigerator_temperature_max'), ' &deg;C'));
		$ice.=$this->getElementIf($this->nbsp(4).'����� �����:', $this->getVar('refrigerator_capacity'), ' ���.�');
		if( $ice ) $out.=$this->getElement('����������� ������������:', '').$ice;

		$lifts = '';
        $lifts.=$this->getElementIf($this->nbsp(8).'������������ ����:', $this->getVar('lift_passenger'), $this->getIf($this->getVar('lift_passenger_weight'),', �� ', ' ��'));
		$lifts.=$this->getElementIf($this->nbsp(8).'�������� ����:', $this->getVar('lift_cargo'), $this->getIf($this->getVar('lift_cargo_weight'),', �� ', ' ��'));
		$lifts.=$this->getElementIf($this->nbsp(8).'���������:', $this->getVar('escalator'));
		$lifts.=$this->getElementIf($this->nbsp(8).'����������:', $this->getVar('travelator'));
		$lifts.=$this->getElementIf($this->nbsp(8).'�������:', $this->getVar('telpher'), $this->getIf($this->getVar('telpher_weight'),', �� ', ' ��'));
		$lifts.=$this->getElementIf($this->nbsp(8).'����-�����:', $this->getVar('crane_beam'), $this->getIf($this->getVar('crane_beam_weight'),', �� ', ' �'));
		$lifts.=$this->getElementIf($this->nbsp(8).'�������� ����:', $this->getVar('crane_trestle'), $this->getIf($this->getVar('crane_trestle_weight'),', �� ', ' �'));
		if( $lifts ) $infra.=$this->getElement($this->nbsp(4).'�������������� ����������:', '').$lifts;

		$infra = '';
        $infra.=$this->getElementIf($this->nbsp(4).'������:', $this->getVar('security'));
		$infra.=$this->getElementIf($this->nbsp(4).'������������:', $this->getVar('signalizing'));
		$infra.=$this->getElementIf($this->nbsp(4).'������� �������������:', $this->getVar('fire_prevention'));
		$infra.=$this->getElementIf($this->nbsp(4).'��������:', $this->getVar('dinning'));
		$infra.=$this->getElementIf($this->nbsp(4).'���������� ��������:', $this->getVar('toilet_count'));
		$infra.=$this->getElementIf($this->nbsp(4).'����������� �����������:', $this->getVar('technical_note'));
		if( $infra ) $out.=$this->getElement('��������������:', '').$infra;


		if( $out )
		{
			$out='<div class="domstor_object_technic">
					<h3>����������� ��������������</h3>
					<table>'.
						$out.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getTransportBlock()
	{
		$a=&$this->object;
        $transp = $out = '';
		$transp.=$this->getElementIf($this->nbsp(4).'�/� ����:', $this->getVar('realroad'));
		if( $this->getVar('realroad_not_active') ) $transp.=$this->getElementIf('', '�� �����������');
		$transp.=$this->getElementIf($this->nbsp(4).'������������� �����:', $this->getVar('realroad_length'), ' �');
		$transp.=$this->getElementIf($this->nbsp(4).'����� ��������:', $this->getVar('realroad_load_length'), ' �');
		if( $this->getVar('pandus') ) $transp.=$this->getElement('', '������');
		$transp.=$this->getElementIf($this->nbsp(4).'�������, �������� ����:', $this->getVar('road'));
		$transp.=$this->getElementIf($this->nbsp(4).'��������:', $this->getVar('parking'));
		if( $this->getVar('parking_underground') ) $transp.=$this->getElement($this->nbsp(4).'', '���������');
		if( $this->getVar('parking_many_floor') ) $transp.=$this->getElement($this->nbsp(4).'', '������������');
		if( $transp ) $out.=$this->getElement('��������, ��������, ��������:','').$transp;

        $road = '';
		$road.=$this->getElementIf($this->nbsp(4).'������������� ������������� ������:', $this->getVar('transport_stream'));
		$road.=$this->getElementIf($this->nbsp(4).'������������� ����������� ������:', $this->getVar('people_stream'));
		$road.=$this->getElementIf($this->nbsp(4).'�������� �����:', $this->getVar('road_covering'));
		$road.=$this->getElementIf($this->nbsp(4).'��������� �������� �����:', $this->getVar('road_state'));
		$road.=$this->getElementIf($this->nbsp(4).'���������� �����������, �����:', $this->getVar('lanes_count'));
		if( $this->getVar('one_way_traffic') ) $road.=$this->getElement($this->nbsp(4).'������������� ��������', '');
		if( $road ) $out.=$this->getElement('�������� �������:','').$road;

		if( $out )
		{
			$out='<div class="domstor_object_transport">
					<h3>������������ �������</h3>
					<table>'.
						$out.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getFurnitureBlock()
	{
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('����������� �� ����������:', $this->getVar('remote_highway'));
		$out.=$this->getElementIf('����������� �� �/� ����:', $this->getVar('remote_realroad'));
		$out.=$this->getElementIf('������:', $this->getVar('relief'));
		$out.=$this->getElementIf('������� ����:', $this->getVar('forest'));
		$out.=$this->getElementIf('������� �� �������:', $this->getVar('objects'));
		$out.=$this->getElementIf('���������������� ���������:', $this->getVar('territory'));

		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>����������, ������������:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getFinanceBlock()
	{
		$a = &$this->object;
		$out ='';

		$a['rent_m2_min'] = (float) $this->getVar('rent_m2_min');
		$a['rent_m2_max'] = (float) $this->getVar('rent_m2_max');
		$a['rent_full'] = (float) $this->getVar('rent_full');

		$a['price_m2_min'] = (float) $this->getVar('price_m2_min');
		$a['price_m2_max'] = (float) $this->getVar('price_m2_max');
		$a['price_full'] = (float) $this->getVar('price_full');

		$price_ground_unit = $this->getVar('price_m2_unit')? $a['price_m2_unit'] : '��.�';

		$price = '';
		$price.= $this->getIf($this->getFormatedPrice($a['price_full'], $this->getVar('price_currency')));

		if( $this->getVar('offer_parts') ) $price.= $this->getIf($this->getPriceFromTo($a['price_m2_min'], $a['price_m2_max'], $this->getVar('price_currency')), ' (', '/ '.$price_ground_unit.')' );

        if( $this->getVar('active_sale') )
            $out.=$this->getElementIf('����:', $price);

		$rent ='';
		$rent_ground_unit = $this->getVar('rent_m2_unit')? $a['rent_m2_unit'] : '��.�';
		$rent.= $this->getIf($this->getFormatedPrice($a['rent_full'], $this->getVar('rent_currency'), $this->getVar('rent_period')));

		if( $this->getVar('active_rent') )
        {
            $out.= $this->getElementIf('�������� ������:', $this->getPriceFromTo($a['rent_m2_min'], $a['rent_m2_max'], $this->getVar('rent_currency'), $this->getVar('rent_period')), '/ '.$rent_ground_unit );
            $out.= $this->getElementIf($this->nbsp(4).'�� ���� ������:', $this->getFormatedPrice($a['rent_full'], $this->getVar('rent_currency'), $this->getVar('rent_period')));
            $out.= $this->getElementIf('������������ �������:', $this->getVar('rent_communal_payment'));
        }

		if( $out )
		{
			$out = '<div class="domstor_object_finance">
					<h3>���������� �������:</h3>
					<table>'.$out.'</table>
				</div>';
		}

		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return '������ �� ������';
		$out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
					$this->getIf(strtolower($this->getPurpose()), '<h2>����������: ', '</h2>').
                    $this->getSecondHead().
				'</div>'.
				$this->getImagesHtml($this->object).
				'<div class="domstor_object_common">'.
					$this->getLocationBlock().
					$this->getDelayBlock().
				'</div>';
		$out.=$this->getRealizationBlock();
		$out.=$this->getComplexBlock();
		$out.=$this->getComplexObjectsBlock();
		$out.=$this->getPurposeBlock();
		$out.=$this->getSizeBlock();
		$out.=$this->getAllocationBlock();
		$out.=$this->getTechnicBlock();
		$out.=$this->getTransportBlock();
		$out.=$this->getFurnitureBlock();
		$out.=$this->getFinanceBlock();
		$out.=$this->getCommentBlock();
		$out.=$this->getContactBlock();
		$out.=$this->getDateBlock();
		$out.=$this->getNavigationHtml();
		return $out;
	}

}

class DomstorCommerceDemand extends DomstorCommonDemand
{
	public function getPageTitle()
	{
		$a = &$this->object;
		$out = $this->getTitle();
		$out.= $this->getIf( $this->getPurpose(), ' (����������: ', ')');
		$out.= $this->getIf( $a['Agency']['name'], ' &mdash; ');
		return $out;
	}

	public function getTitle()
	{
		$out = $this->getOfferType2().' ������� ���������';

        $city = $this->getVar('city');
        if( $city ) $out.= ' � '.$city;
		return $out;
	}

	public function getSquareGround()
	{
		$a=&$this->object;
		return $this->getFromTo($a['square_ground_min'], $a['square_ground_max'], ' '.$a['square_ground_unit'], '������� ���������� ������� ');
	}

	public function getSquareHouse()
	{
		$a=&$this->object;
		return $this->getFromTo($a['square_house_min'], $a['square_house_max'], ' ��.�',  '������� ��������� ');
	}

	public function getSquare()
	{
		$a=&$this->object;
		if( $a['Purposes'][1009] )
		{
			if( count($a['Purposes'])==1 )
			{
				$out.=$this->getSquareGround();
			}
			else
			{
				$out=$this->getIf($this->getSquareHouse(), '', ', ');
				$out.=$this->getSquareGround();
			}
		}
		else
		{
			$out=$this->getSquareHouse();
		}
		return $out;
	}

	public function getFloor($min, $max)
	{
		if( isset($min) )
		{
			if( isset($max) )
			{
				if($min==$max)
				{
					if( $min!='0' )
					{
						$out=$min;
					}
				}
				else
				{
					$out=$min.' &ndash; '.$max;
				}
			}
			else
			{
				$out=$min;
			}
		}
		else
		{
			if( isset($max) )
			{
				if( $max!='0' )
				{
					$out=$max;
				}
			}
		}
		return $out;
	}

	public function getFormatedPrice()
	{
        $out = '';
        $price = (float) $this->getVar('price_full');
		if( $price )
		{
			$out = number_format($price, 0, ',', ' ');
			$out.= $this->getIf( $this->getVar('price_currency'), ' ');
			$out = str_replace(' ', '&nbsp;', $out);
		}
		return $out;
	}

	public function getFormatedPriceM2()
	{
		$out = '';
        $price = (float) $this->getVar('price_m2');
		if( $price )
		{
			$out = number_format($this->getVar('price_m2'), 0, ',', ' ');
			$out.= $this->getIf($this->getVar('price_currency'), ' ');
			$out.= $this->getIf($this->getVar('price_m2_unit'), '/ ');
			$out = str_replace(' ', '&nbsp;', $out);
		}
		return $out;
	}

	public function getFormatedRent()
	{
        $out = '';
        $rent = (float) $this->getVar('rent_full');
		if( $rent )
		{
			$out = number_format($rent, 0, ',', ' ');
			$out.= $this->getIf($this->getVar('rent_currency'), ' ');
			if( $this->getVar('rent_period') ) $out.=' '.$this->getVar('rent_period');
			$out = str_replace(' ', '&nbsp;', $out);
		}
		return $out;
	}

	public function getFormatedRentM2()
	{
		$out = '';
        $rent = (float) $this->getVar('rent_m2');
		if( $rent )
		{
			$out = number_format($this->getVar('rent_m2'), 0, ',', ' ');
			$out.= $this->getIf($this->getVar('rent_currency'), ' ');
			$out.= $this->getIf($this->getVar('rent_m2_unit'), '/ ');
			if( $this->getVar('rent_period') ) $out.=' '.$this->getVar('rent_period');
			$out = str_replace(' ', '&nbsp;', $out);
		}
		return $out;
	}

	public function getPrice()
	{
		if( $this->action=='rent' )
		{
			$rent=$this->getIf($this->getFormatedRent(), '�� ������ ');
			$rent_m2=$this->getIf($this->getFormatedRentM2());
			if( $rent and $rent_m2 ) $rent_m2=' ('.$rent_m2.')';
			$out=$rent.$rent_m2;
		}
		else
		{
			$price=$this->getIf($this->getFormatedPrice(), '�� ������ ');
			$price_m2=$this->getIf($this->getFormatedPriceM2());
			if( $price and $price_m2 ) $price_m2=' ('.$price_m2.')';
			$out=$price.$price_m2;
		}
		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		$annotation.=$this->getIf( $this->getPurpose(), ', ');
		$annotation.=$this->getIf( $a['complex'], ', ');
		$annotation.=$this->getIf( $this->getSquare(), ', ');
		$annotation.=$this->getIf( $this->getAddress(), ', ');
		$annotation.=$this->getIf( $this->getPrice(), ', �� ������ ');
		$annotation.=$this->getIf( $a['note_addition'], ', ');
		return $annotation;
	}

	public function getDelayBlock()
	{
		$a = &$this->object;
        $out = '';
		if( isset($a['delay_sale_dt']) ) $out.='<p>����������� ������� � '.date('d.m.Y', strtotime($a['delay_sale_dt'])).'</p>';
		if( isset($a['delay_rent_dt']) ) $out.='<p>����������� ������ � '.date('d.m.Y', strtotime($a['delay_rent_dt'])).'</p>';
		if( $out )
		{
			$out='<div class="domstor_object_delay">
					<h3>����������� �����������</h3>'.
					$out.
				'</div>';
		}
		return $out;
	}

	public function getPurposeBlock()
	{
        $out = '';

		$out.= $this->getElementIf('��������� ����������:', $this->getPurpose());
		$out.= $this->getElementIf('�������������� ������������� �������:', $this->getVar('use_plan'));
		$out.= $this->getElementIf('����������� ��� ������������� ���������� �������:', $this->getVar('ground_use_allow'));
		$out.= $this->getElementIf('����� �������:', $this->getFromTo($this->getVar('class_min'), $this->getVar('class_max')));

		if( $out )
				{
					$out='<div class="domstor_object_purpose">
							<h3>����������</h3><table>'.
							$out.
						'</table></div>';
				}
		return $out;
	}

	public function getSizeBlock()
	{
        $out = '';

		$out.= $this->getElementIf('������� ���������:', $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max'), ' ��.�') );
		$out.= $this->getElementIf('������� ���������� �������:', $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit')) );
		$out.= $this->getElementIf('������ ���������:', $this->getFromTo($this->getVar('height_min'), $this->getVar('height_max'), ' �') );
		$out.= $this->getElementIf('���������� ����� �� �����:', $this->getVar('gate_count'));
		$out.= $this->getElementIf('������������ ������ ����� �� �����:', $this->getVar('gate_height'), ' �');
		$out.= $this->getElementIf('������������ ������ ����� �� �����:', $this->getVar('gate_width'), ' �');

		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>�������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getAllocationSubBlock()
	{
        $out = '';

		$placing_features = array();

        if( $this->getVar('placing_separate_building') ) $placing_features[]='��������-������� ������';
		if( $this->getVar('placing_separate_door') ) $placing_features[]='� ��������� ������ � ������';
		if( $this->getVar('placing_commerce_only') ) $placing_features[]='������ ������� ������';
		if( $this->getVar('inside_building') )
		{
			$inside = '��������� ������ ������';
			if( $this->getVar('inside_building') ) $inside.=' ('.$this->getVar('inside_building').')';
			$placing_features[]=$inside;
		}
		$out = $this->getElementIf('����������� ����������:', implode(', ', $placing_features));
		return $out;
	}

	public function getAllocationBlock()
	{
        $out = '';
		$out.= $this->getElementIf('���� �������:',$this->getFromTo($this->getVar('object_floor_min'), $this->getVar('object_floor_max')));
		$out.= $this->getElementIf('', $this->getVar('object_floor_limit'));
		$out.= $this->getAllocationSubBlock();
		if( $out )
		{
			$out='<div class="domstor_object_allocation">
					<h3>���������� �������</h3>
					<table>'.
					$out.
					'</table>
			</div>';
		}
		return $out;
	}

	public function getTechnicBlock()
	{
        $out = '';

		$out.= $this->getElementIf('���������� ����� �� �����:', $this->getVar('phone_count'));
		$out.= $this->getElementIf('��������-�����������:', $this->getVar('internet_want'));

		$electro = '';
        $electro.= $this->getElementIf($this->nbsp(4).'����������', $this->getVar('electro_voltage'), ' �');
		$electro.= $this->getElementIf($this->nbsp(4).'�������� �� �����', $this->getVar('electro_power'), ' ���');
		if( $electro ) $electro = $this->getElement('����������������:', '').$electro;
		$out.= $electro;

		$out.= $this->getElementIf('��������������:', $this->getVar('heat_want'));
		if( $this->getVar('heat_control') ) $out.= $this->getElementIf('', '������������ ������������� �����');
		$out.= $this->getElementIf('�������������:', $this->getVar('water_want'));
		$out.= $this->getElementIf('��� �������������:', $this->getVar('water'));
		if( $this->getVar('water_reserve') ) $out.= $this->getElementIf('', '��������� ��������');
		$out.= $this->getElementIf('�����������:', $this->getVar('sewerage_want'));
		$out.= $this->getElementIf('��� �����������:', $this->getVar('sewerage'));
		$out.= $this->getElementIf('�������������:', $this->getVar('gas_want'));
		$out.= $this->getElementIf('��� �������������:', $this->getVar('gas'));

        $construction = '';
		$construction.= $this->getElementIf($this->nbsp(4).'��� ������ �� �����:', $this->getVar('pillar_step'), ' �');
		$construction.= $this->getElementIf($this->nbsp(4).'�������� �����:', $this->getVar('paul_coating'));
		$construction.= $this->getElementIf($this->nbsp(4).'����� �����:', $this->getVar('paul_bias'));
		$construction.= $this->getElementIf($this->nbsp(4).'�������� �� ��� �� �����:', $this->getVar('paul_loading'), ' ��/��.�');
		if( $construction )	$out.=$this->getElement('����������� ��������:', '').$construction;

		//	���������
        $state = '';
		$state.=$this->getElementIf($this->nbsp(4).'�� ����� ���:', $this->getVar('state'));
		if( $state ) $out.=$this->getElement('��������� �������:', '').$state;


        $lift_pas_w = $lift_car_w = $telpher_w = $crane_beam_w = $crane_tres_w = '';
		if( $this->getVar('lift_passenger_weight') ) $lift_pas_w=' �� '.$this->getVar('lift_passenger_weight').' ��';
		if( $this->getVar('lift_cargo_weight') ) $lift_car_w=' �� '.$this->getVar('lift_cargo_weight').' ��';
		if( $this->getVar('telpher_weight') ) $telpher_w=' �� '.$this->getVar('telpher_weight').' �';
		if( $this->getVar('crane_beam_weight') ) $crane_beam_w=' �� '.$this->getVar('crane_beam_weight').' �';
		if( $this->getVar('crane_trestle_weight') ) $crane_tres_w=' �� '.$this->getVar('crane_trestle_weight').' �';

		$lifts = '';
        if( $this->getVar('lift_passenger') ) $lifts.= '������������ ����'.$lift_pas_w.', ';
		if( $this->getVar('lift_cargo') ) $lifts.= '�������� ����'.$lift_car_w.', ';
		if( $this->getVar('escalator') ) $lifts.= '���������, ';
		if( $this->getVar('travelator') ) $lifts.= '����������, ';
		if( $this->getVar('telpher') ) $lifts.= '�������'.$telpher_w.', ';
		if( $this->getVar('crane_beam') ) $lifts.= '����-�����'.$crane_beam_w.', ';
		if( $this->getVar('crane_trestle') ) $lifts.= '�������� ����'.$crane_tres_w.', ';

		$lifts = substr($lifts, 0, -2);

        $infra = '';
		$infra.= $this->getElementIf($this->nbsp(4).'����������� �������������� ����������:', $lifts);
		$infra.= $this->getElementIf($this->nbsp(4).'�������:', $this->getVar('toilet_want'));
		if( $infra ) $out.= $this->getElement('��������������:', '').$infra;

		$ice = '';
        $ice.= $this->getElementIf($this->nbsp(4).'����������� ������������:', $this->getVar('refrigerator_want'));
		$ice.= $this->getElementIf($this->nbsp(4).'������������� �����:', $this->getFromTo($this->getVar('refrigerator_temperature_min'), $this->getVar('refrigerator_temperature_max'), ' &deg;C'));
		$ice.= $this->getElementIf($this->nbsp(4).'����� �����:',  $this->getFromTo($this->getVar('refrigerator_capacity_max'), $this->getVar('refrigerator_capacity_min'), ' ���.�'));
		if( $ice ) $out.= $this->getElement('����������� ������������:', '').$ice;

		if( $out )
		{
			$out='<div class="domstor_object_technic">
					<h3>����������� ��������������</h3>
					<table>'.
						$out.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getTransportBlock()
	{
        $out = '';

        $transp = '';
		$transp.= $this->getElementIf($this->nbsp(4).'�/� ����, �/� �����', $this->getVar('realroad_want'));
		$transp.= $this->getElementIf($this->nbsp(4).'������������� �����:', $this->getVar('realroad_length'), ' �');
		$transp.= $this->getElementIf($this->nbsp(4).'����� ��������:', $this->getVar('realroad_load_length'), ' �');
		$transp.= $this->getElementIf($this->nbsp(4).'������:', $this->getVar('pandus_want'));
		$transp.= $this->getElementIf($this->nbsp(4).'�������, �������� ����:', $this->getVar('road'));
		$transp.= $this->getElementIf($this->nbsp(4).'��������:', $this->getVar('parking'));
		if( $transp ) $out.=$this->getElement('��������, ��������, ��������:','').$transp;

        $road = '';
		$road.= $this->getElementIf($this->nbsp(4).'������������� ������������� ������:', $this->getVar('transport_stream'));
		$road.= $this->getElementIf($this->nbsp(4).'������������� ����������� ������:', $this->getVar('people_stream'));
		if( $road ) $out.= $this->getElement('�������� �������:','').$road;

		if( $out )
		{
			$out='<div class="domstor_object_transport">
					<h3>������������ �������</h3>
					<table>'.
						$out.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getFurnitureBlock()
	{
        $out = '';

		$out.=$this->getElementIf('����������� �� ����������:', $this->getVar('remote_highway'));
		$out.=$this->getElementIf('����������� �� �/� ����:', $this->getVar('remote_realroad'));
		$out.=$this->getElementIf('������:', $this->getVar('relief'));
		$out.=$this->getElementIf('������� ����:', $this->getVar('forest'));
		$out.=$this->getElementIf('������� �� �������:', $this->getVar('objects'));
		$out.=$this->getElementIf('���������������� ���������:', $this->getVar('territory'));

		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>����������, ������������:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getFinanceBlock()
	{
        $out = '';

		$price = $this->getIf($this->getFormatedPrice());
		$price_m2 = $this->getIf($this->getFormatedPriceM2());
		if( $price and $price_m2 ) $price_m2=' ('.$price_m2.')';

        if( $this->getVar('active_sale') )
            $out.=$this->getElementIf('������:', $price.$price_m2, '', '�� ����� ');

        if( $this->getVar('active_rent') )
        {
            $out.=$this->getElementIf('������:', $this->getFormatedRentM2(), '', '�� ����� ');
            $out.=$this->getElementIf($this->nbsp(4).'�� ���� ������:',  $this->getFormatedRent(), '', '�� ����� ');
        }

		if( $out )
		{
			$out = '<div class="domstor_object_finance">
					<h3>���������� �������:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return '������ �� �������';
		$out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
					$this->getIf(strtolower($this->getPurpose()), '<h2>����������: ', '</h2>').
                    $this->getSecondHead().
				'</div>
				<div class="domstor_object_common">'.
					$this->getLocationBlock().
					$this->getDelayBlock().
				'</div>';
		$out.=$this->getPurposeBlock();
		$out.=$this->getSizeBlock();
		$out.=$this->getAllocationBlock();
		$out.=$this->getTechnicBlock();
		$out.=$this->getTransportBlock();
		$out.=$this->getFurnitureBlock();
		$out.=$this->getFinanceBlock();
		$out.=$this->getCommentBlock();
		$out.=$this->getContactBlock();
		$out.=$this->getDateBlock();
		$out.=$this->getNavigationHtml();
		return $out;
	}

}
