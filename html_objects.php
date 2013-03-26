<?php

abstract class DomstorCommon
{
	protected $object;
	protected $action;//изачально задумывался чтобы хранить значения rent или sale
	protected $_action;// но потом понадобились варианты sale, rent, purchase, rentuse ну и т.д
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



	//реализована в domstorlib, но оказалось что может понадобиться и тут
	public function getNavigationHtml()
	{
		$prev = $this->getVar('prev_id');
		$next = $this->getVar('next_id');
        $out = '';

		if($this->_action=='rentuse' or $this->_action=='purchase')
		{
			$name='заявка';
			$end='ая';
		}
		else
		{
			$name='объект';
			$end='ий';
		}

		if( $prev )
		{
			$href = str_replace('%id', $prev, $this->object_href);
			$out.='<a class="domstor_link" href="'.$href.'">&larr;Предыдущ'.$end.' '.$name.'</a> ';
		}

		if( $next )
		{
			$href = str_replace('%id', $next, $this->object_href);
			$out.='<a class="domstor_link" href="'.$href.'">Следующ'.$end.' '.$name.'&rarr;</a> ';
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
		$from_string='от&nbsp;';
		$to_string='до&nbsp;';
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
		$from_string='от&nbsp;';
		$to_string='до&nbsp;';
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
				<h3>Комментарий</h3>
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
            $out.=$this->getIf($a['Agent']['name_as'], '<p>Агент: ', '</p>');
		if( $this->_show_agency ) $out.=$this->getIf($a['Agency']['shotname'], '<p>Агентство: ', '</p>');
		$out.=$this->getIf($phone,'<p>Телефон: ', '</p>');
		$out.=$this->getIf($mail,'<p>Эл. почта: ', '</p>');
		$edit_dt = '';
		if( $a['edit_dt'] ) $edit_dt = '<p>Обновлено: '.date('d.m.Y', strtotime($a['edit_dt'])).'</p>';
		$edit_dt.= '<p>Просмотров: '.((int) $a['view_count'] + 1).'</p>';
		if( $out )
		{
			$out='<div class="domstor_object_contacts">
					<h3>Контактная информация</h3>
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
		return 'Объект '.$this->object['code'];
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
			$out='Аренда';
		}
		elseif( $action=='exchange' )
		{
			$out='Обмен';
		}
		else
		{
			$out='Продажа';
		}
		return $out;
	}

	public function getOfferType2($action = NULL)
	{
		if( !$action ) $action = $this->_action;
		if( $action == 'rent' )
		{
			$out='Сдается';
		}
		elseif( $action == 'exchange' )
		{
			$out='Обменяю';
		}
		else
		{
			$out='Продается';
		}
		return $out;
	}

	public function getDemandsBlock()
	{
		$obj = &$this->object;
        $out = '';
		if( $obj['Demands'] and $this->action=='exchange')
		{
			$type=array(4=>'Квартира', 6=>'Дом');
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
				if( $a['new_building'] ) $annotation.=', Новостройка';
				for($room=1; $room<6; $room++)
				{
					if( $a['room_count_'.$room] ) $rooms.=$room.', ';
				}
				$rooms=substr($rooms, 0, -2);
				if( $rooms )  $annotation.=', '.$rooms.' комн.';
				if( $a['in_communal'] ) $annotation.=', (в коммуналке)';
				if( $a['object_floor_limit'] )  $annotation.=', '.$a['object_floor_limit'].' эт.';
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
						<h3>Заявки</h3>'.$out.'
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
				$room=' комната';
			elseif( $count<5 )
				$room=' комнаты';
			else
				$room=' комнат';
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
		if( $this->in_region or $a['district'] == 'Пригород' or $district_type == 7 )
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
				if( substr($a['district'], -1)=='й' )
				{
					$out.=$space.$a['district'].'&nbsp;р-н';
				}
				else
				{
					$out.=$space.'р-н&nbsp;'.$a['district'];
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
			if( isset($a['first_line']) and $a['first_line'] ) $location.='<p>Первая линия</p>';
			if( isset($a['metro']) and $a['metro'] ) $location.='<p>'.$a['metro'].'</p>';
			if( isset($a['available_bus']) and $a['available_bus'] ) $location.='<p>От остановки '.$a['available_bus'].' мин.</p>';
			if( isset($a['available_metro']) and $a['available_metro'] ) $location.='<p>От метро '.$a['available_metro'].' мин.</p>';
			if( isset($a['available_bus_to_metro']) and $a['available_bus_to_metro'] ) $location.='<p>Транспортом до метро '.$a['available_bus_to_metro'].' мин.</p>';
			if( isset($a['map_weblink']) and $a['map_weblink'] ) $location.='<p><a href="'.$a['map_weblink'].'" target="_blank">На карте</a></p>';
			if( isset($a['img_map']) and $map = $this->getMapHtml($a['img_map']) ) $location.= $map;
			if( isset($a['zone']) and $a['zone'] ) $location.='<p>Территориальная зона'.$a['zone'].'</p>';
			$location='
				<div class="domstor_object_place">
					<h3>Местоположение</h3>'.$location.'
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
				$out.=$this->getElement('Способ реализации:', $a['realization_way']);
				$out.=$this->getElementIf('Начальная цена:', $this->getFormatedPrice($a['auction_initial_price'], $a['auction_currency']));
				$out.=$this->getElementIf('Сумма задатка:', $this->getFormatedPrice($a['auction_advance'], $a['auction_currency']));
				$out.=$this->getElementIf('Шаг аукциона:', $this->getFormatedPrice($a['auction_step'], $a['auction_currency']));
				$out.=$this->getElementIf('Тип аукциона:', $a['auction_type']);
				$date=strtotime($a['auction_dttm']);
				if( $date ) $out.=$this->getElement('Дата проведения:', date('d.m.Y', $date));
				if( $date ) $out.=$this->getElement('Время проведения:', date('H:i', $date));
				$out.=$this->getElementIf('Место проведения:', $a['auction_location']);
				$date=strtotime($a['auction_filing_start']);
				if( $date ) $out.=$this->getElement('Дата начал подачи заявок:', date('d.m.Y', $date));
				$date=strtotime($a['auction_filing_finish']);
				if( $date ) $out.=$this->getElement('Дата окончания подачи заявок:', date('d.m.Y', $date));
			}
			else
			{
				$out.=$this->getElement('', $a['realization_way']);
			}
			$out = '<div class="domstor_object_realization">
					<h3>Способ реализации:</h3>
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
            $out.=$this->getElement('Цена:', $this->getFormatedPrice($a['price_full'], $a['price_currency']));

		if( $this->getVar('active_rent') and (float) $this->getVar('rent_full') )
            $out.=$this->getElement('Арендная ставка:', $this->getFormatedPrice($a['rent_full'], $a['rent_currency'], $a['rent_period']));

		if( $out )
		{
			$out = '<div class="domstor_object_finance">
					<h3>Финансовые условия:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

    public function getEntityType()
    {
        return 'Объект';
    }

    public function getSecondHead()
    {
        if( !$this->show_second_head ) return '';

        $tmpl = '<h3>Код объекта: %s</h3>';
        return sprintf($tmpl, $this->getCode());
    }
}

class DomstorCommonDemand extends DomstorCommon
{
	public function getObjectCode()
	{
		return 'Заявка '.$this->object['code'];
	}

	public function getOfferType($action)
	{
		if( $action=='rent' )
		{
			$out='Снимут';
		}
		else
		{
			$out='Купят';
		}
		return $out;
	}

    public function getOfferType2()
	{
		if( $this->action == 'rent' )
		{
			$out='Сниму';
		}
		else
		{
			$out='Куплю';
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
			if( $this->getVar('district') ) $out='Районы: '.$a['district'].', ';
			if( $this->getVar('street') ) $out.='Улицы: '.$a['street'].', ';
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
			if( $this->getVar('first_line_want') ) $location.='<p>Первая линия: '.$a['first_line_want'].'</p>';
			if( $this->getVar('metro') ) $location.='<p>'.$a['metro'].'</p>';
			if( $this->getVar('available_bus') ) $location.='<p>От остановки не более '.$a['available_bus'].' мин.</p>';
			if( $this->getVar('available_metro') ) $location.='<p>От метро не более '.$a['available_metro'].' мин.</p>';
			if( $this->getVar('available_bus_to_metro') ) $location.='<p>Транспортом до метро не более '.$a['available_bus_to_metro'].' мин.</p>';
			$location='<div class="domstor_object_place">
						<h3>Местоположение</h3>'.$location.'
					</div>';
		}

		return $location;
	}

	public function getFinanceBlock()
	{
        $out = '';

        $price = $this->getFormatedPrice();
        if( $this->getVar('active_sale') and $price )
            $out.= $this->getElementIf('Бюджет:', $price);

        $rent = $this->getFormatedRent();
        if( $this->getVar('active_rent') and $rent )
            $out.= $this->getElementIf('Бюджет:', $rent);

        if( $out )
		{
			$out = '<div class="domstor_object_finance">
					<h3>Финансовые условия:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

    public function getEntityType()
    {
        return 'Заявка';
    }

    public function getSecondHead()
    {
        if( !$this->show_second_head ) return '';

        $tmpl = '<h3>Код заявки: %s</h3>';
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
			1 => 'одно',
			2 => 'двух',
			3 => 'трех',
			4 => 'четырех',
			5 => 'пяти',
			6 => 'шести',
			7 => 'семи',
		);

		$out = $this->getOfferType2().' ';

		if( isset($rooms[$a['room_count']]) ) $out.= $rooms[$a['room_count']].($this->_action=='exchange'? 'комнатную ' : 'комнатная ');

		$out.= ($this->_action=='exchange'? 'квартиру ' : 'квартира ');

		if( $a['city'] ) $out.= 'в '.$a['city'];

		$addr = $this->getStreetBuilding();
        if( $addr ) $out.= ', '.$addr;

		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['new_building'] ) $annotation.=', Новостройка';
		if( $a['room_count'] )  $annotation.=', '.$this->getRoomCount($a['room_count']);
		if( $a['in_communal'] ) $annotation.=' (в коммуналке)';
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
		if( $a['in_communal'] ) $room.='<p>Комнаты в коммуналке</p>';
		if( $a['in_pocket'] ) $room.='<p>Есть карман</p>';
		if( $a['Together']['id'] )
		{
			$tgh=$a['Together'];
			$together='<a href="'.$this->getObjectUrl($tgh['id']).'" class="domstro_link">'.$tgh['code'].'</a>';
			if( $tgh['room_count'] ) $together.=', '.$this->getRoomCount($a['room_count']);
			$squares=$this->getSquares($tgh['square_house'], $tgh['square_living'], $tgh['square_kitchen']);
			if( $squares )  $together.=', '.$squares;
			$price=$this->getFormatedPrice($tgh['price_full'], $tgh['price_currency']);
			if( $price ) $together.=', '.$price;
			if( $a['Together'] ) $room.='<p>Совместная продажа с соседней квартирой:<br />'.$this->nbsp(4).$together.'</p>';
		}
		if( $room )
		{
			$room='<div class="domstor_object_rooms">
					<h3>Число комнат</h3>'.
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
		if( $a['ground_floor'] ) $out.='<p>В здании имеется цокольный этаж</p>';
		if( $a['first_floor_commerce'] ) $out.='<p>Первые этажи нежилые</p>';
		if( $out )
		{
			$out='<div class="domstor_object_floor">
					<h3>Этаж</h3>'.
					$out.
				'</div>';
		}
		return $out;
	}

	public function getTypeBlock()
	{
		$a = &$this->object;
        $type = '';
		if( $this->getVar('flat_type') ) $type=$this->getElement('Тип квартиры:', $a['flat_type']);
		if( $this->getVar('planning') ) $type.=$this->getElement('Планировка:', $a['planning']);
		if( $this->getVar('building_material') ) $type.=$this->getElement('Материал строения:', $a['building_material']);
		if( $type )
		{
			$type='<div class="domstor_object_type">
					<h3>Тип квартиры (здания)</h3>
					<table>'.$type.'</table>
				</div>';
		}
		return $type;
	}

	public function getSizeBlock()
	{
		$a = &$this->object;
        $square = $out = '';
		if( isset($a['height']) and $a['height'] ) $out=$this->getElement('Высота потолков:', $a['height'], ' м.');
		if( isset($a['floor_count']) and $a['floor_count'] > 1 ) $out.=$this->getElement('Количество уровней:', $a['floor_count']);
		if( isset($a['square_house']) and $a['square_house'] )  $square.=$this->getElement($this->nbsp(4).'Общая:', $a['square_house']);
		if( isset($a['square_living']) and $a['square_living'] ) $square.=$this->getElement($this->nbsp(4).'Жилая:', $a['square_living']);
		if( isset($a['square_kitchen']) and $a['square_kitchen'] ) $square.=$this->getElement($this->nbsp(4).'Кухня:', $a['square_kitchen']);
		if( isset($a['square_pocket']) and $a['square_pocket'] ) $square.=$this->getElement($this->nbsp(4).'Карман:', $a['square_pocket']);
		if( $square ) $square=$this->getElement('Площадь, кв.м.:', '').$square;
		$out.= $square;
		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>Размеры</h3>
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

		if( $this->getVar('phone') ) $communications.='телефон, ';
		if( $this->getVar('internet') ) $communications.='интернет, ';
		if( $this->getVar('cable_tv') ) $communications.='кабельное ТВ, ';
		if( $this->getVar('door_phone') ) $communications.='домофон, ';
		if( $this->getVar('gas') ) $communications.='газопровод, ';
		if( $this->getVar('satellite_tv') ) $communications.='спутниковое ТВ, ';
		if( $this->getVar('signalizing') ) $communications.='охранная сигнализация, ';
		if( $this->getVar('fire_prevention') ) $communications.='противопожарная сигнализация, ';
		if( $communications )
		{
			$communications=substr($communications, 0, -2);
			$communications = $this->getElement('Коммуникации:', $communications);
			$show=true;
		}

        $san_tech = '';
		if( $this->getVar('toilet') ) $san_tech.=$this->getElement($this->nbsp(4).'Cанузел:', $a['toilet']);
		if( $this->getVar('toilet_count') ) $san_tech.=$this->getElement($this->nbsp(4).'Количество санузлов:', $a['toilet_count']);
		if( $this->getVar('santech_year') ) $san_tech.=$this->getElement($this->nbsp(4).'Год замены (установки) сантехники:', $a['santech_year']);
		if( $this->getVar('santech_material') ) $san_tech.=$this->getElement($this->nbsp(4).'Сантех. трубы:', $a['santech_material']);
		if( $this->getVar('sewerage_material') ) $san_tech.=$this->getElement($this->nbsp(4).'Трубы канализации:', $a['sewerage_material']);
		if( $this->getVar('heat_battery') ) $san_tech.=$this->getElement($this->nbsp(4).'Батареи отопления:', $a['heat_battery']);
		if( $san_tech )
		{
			$san_tech = $this->getElement('Санузел, сантехника, арматура:', '').$san_tech;
			$show=true;
		}

        $construction = '';
		if( $this->getVar('material_wall') ) $construction.=$this->getElement($this->nbsp(4).'Материал наружных стен:', $a['material_wall']);
		if( $this->getVar('material_ceiling') ) $construction.=$this->getElement($this->nbsp(4).'Материал перекрытий:', $a['material_ceiling']);
		if( $this->getVar('material_carying') ) $construction.=$this->getElement($this->nbsp(4).'Материал несущих конструкций:', $a['material_carying']);
		if( $construction )
		{
			$construction = $this->getElement('Конструкция здания:', '').$construction;
			$show=true;
		}

		if( $this->getVar('balcony_count') == 1 )
			$balcony=' балкон';
		elseif( $this->getVar('balcony_count')<5 )
			$balcony=' балкона';
		else
			$balcony=' балконов';

		if( $this->getVar('loggia_count') == 1 )
			$loggia=' лоджия';
		elseif( $this->getVar('loggia_count') < 5 )
			$loggia=' лоджии';
		else
			$loggia=' лоджий';

        $balc_log = '';
		if(  $this->getVar('balcony_count') ) $balc_log.=$this->getElement($this->nbsp(4).'Количество балконов:', $a['balcony_count']);
		if(  $this->getVar('loggia_count') ) $balc_log.=$this->getElement($this->nbsp(4).'Количество лоджий:', $a['loggia_count']);
		if(  $this->getVar('balcony_arrangement') ) $balc_log.=$this->getElement($this->nbsp(4).'Обустройство:', $a['balcony_arrangement']);
		if( $balc_log )
		{
			$balc_log = $this->getElement('Балкон, лоджия:', '').$balc_log;
			$show=true;
		}

        $windows = '';
		if( $this->getVar('window_material') ) $windows.=$this->getElement($this->nbsp(4).'Материал рам:', $a['window_material']);
		if( $this->getVar('window_glasing') ) $windows.=$this->getElement($this->nbsp(4).'Тип остекления:', $a['window_glasing']);
		if( $this->getVar('window_opening') ) $windows.=$this->getElement($this->nbsp(4).'Тип открывания:', $a['window_opening']);
		if( $windows )
		{
			$windows = $this->getElement('Окна:', '').$windows;
			$show=true;
		}

        $doors = '';
		if( $this->getVar('door_room') ) $doors.=$this->getElement($this->nbsp(4).'Двери межкомнатные:', $a['door_room']);
		if( $this->getVar('door_front_material') ) $door_front_material=', '.$a['door_front_material'];
		if( $this->getVar('door_front') ) $doors.=$this->getElement($this->nbsp(4).'Входная дверь:', $a['door_front'].$door_front_material);
		if( $this->getVar('door_pocket_material') ) $doors.=$this->getElement($this->nbsp(4).'Дверь в карман:', $a['door_pocket_material']);
		if( $doors )
		{
			$doors = $this->getElement('Двери:', '').$doors;
			$show=true;
		}

		//	отделка
        $finish = '';
		if( $this->getVar('finish_ceiling') ) $finish.=$this->getElement($this->nbsp(4).'Потолки:', $a['finish_ceiling']);
		if( $this->getVar('finish_paul') ) $finish.=$this->getElement($this->nbsp(4).'Полы: ', $a['finish_paul']);
		if( $this->getVar('finish_partition') ) $finish.=$this->getElement($this->nbsp(4).'Перегородки: ', $a['finish_partition']);
		if( $finish )
		{
			$finish = $this->getElement('Отделка:', '').$finish;
			$show=true;
		}

		//	состояние
        $state = '';

		if( $this->getVar('build_year') ) $state.=$this->getElement($this->nbsp(4).'Год постройки:', $a['build_year']);
		if( $this->getVar('wearout') ) $state.=$this->getElement($this->nbsp(4).'Процент износа: ', $a['wearout'].'%');
		if( $this->getVar('state') ) $state.=$this->getElement($this->nbsp(4).'Состояние:', $a['state']);
		if( $state )
		{
			$state = $this->getElement('Состояние объекта:', '').$state;
			$show=true;
		}

		if( $show )
		{
			$out='<div class="domstor_object_technic">
					<h3>Технические характеристики</h3>
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
		if( $this->getVar('furniture') ) $out.= $this->getElement('Мебель:', $this->getVar('furniture'));
        if( $this->getVar('household_technique') ) $out.= $this->getElement('Бытовая техника:', $this->getVar('household_technique'));
		if( $this->getVar('in_corner') ) $out.=$this->getElement('Расположение:', 'угловая квартира');

        $window_direction = '';
		if( $this->getVar('window_to_south') ) $window_direction='юг, ';
		if( $this->getVar('window_to_north') ) $window_direction.='север, ';
		if( $this->getVar('window_to_west') ) $window_direction.='запад, ';
		if( $this->getVar('window_to_east') ) $window_direction.='восток, ';
		if( $window_direction )
		{
			$window_direction=substr($window_direction, 0, -2);
			$out.=$this->getElement('Расположение окон:', $window_direction);
		}

        $parking = '';
		if( $this->getVar('garbage_chute') ) $out.=$this->getElement('Мусоропровод:', 'имеется');
		if( $this->getVar('security') ) $out.=$this->getElement('Охрана:', $a['security']);
		if( $this->getVar('sale_with_parking') ) $parking=', Возможна продажа совместно с гаражом или паркоместом';
		if( $this->getVar('parking') ) $out.=$this->getElement('Парковка:', $a['parking'].$parking);

		$lifts = '';
        if( $this->getVar('lift_count') == 1 )
			$lift=' лифт';
		elseif( $this->getVar('lift_count') < 5 )
			$lift=' лифта';
		else
			$lift=' лифтов';
		if( $this->getVar('lift_count') ) $lifts=$a['lift_count'].$lift.', ';
		if( $this->getVar('lift_cargo') ) $lifts.='есть грузовой, ';
		if( $lifts )
		{
			$lifts=substr($lifts, 0, -2);
			$lifts = $this->getElement('Лифты', $lifts);
			$out.=$lifts;
		}
		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>Обстановка, расположение:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return 'Объект не найден';
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

		$out.= str_replace('комн.', 'комнатную', $this->getRooms()).' ';
        $out.= 'квартиру в '.$a['city'];
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
		if( $rooms )  $rooms.=' комн.';
		return $rooms;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $this->getVar('new_building') ) $annotation.=', Новостройка';
		$rooms=$this->getRooms();
		if( $rooms )  $annotation.=', '.$rooms;
		if( $this->getVar('in_communal') ) $annotation.=' (в коммуналке)';
		if( $this->getVar('object_floor') )  $annotation.=', '.$a['object_floor'].' эт.';
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
			if( $this->getVar('room_count_'.$room) ) $rooms.=$room.' комн., ';
		}
		$rooms=substr($rooms, 0, -2);

		if( $rooms ) $rooms='<p>'.$rooms.'</p>';
		if( $this->getVar('in_communal') ) $rooms.='<p>Комнаты в коммуналке</p>';
		if( $rooms )
		{
			$rooms='<div class="domstor_object_rooms">
					<h3>Число комнат</h3>'.
					$rooms.
				'</div>';
		}
		return $rooms;
	}

	public function getFloorsBlock()
	{
		$a = &$this->object;
        $floor = '';
		if( $this->getVar('object_floor') ) $floor='<p>'.$a['object_floor'].' эт.</p>';
		if( $this->getVar('object_floor_limit') ) $floor.='<p>'.$a['object_floor_limit'].'</p>';
		if( $floor )
		{
			$floor='<div class="domstor_object_floor">
					<h3>Этаж</h3>'.
					$floor.
				'</div>';
		}
		return $floor;
	}

	public function getTypeBlock()
	{
		$a = &$this->object;
        $type = '';
		if( $this->getVar('flat_type') ) $type=$this->getElement('Тип квартиры:', $a['flat_type']);
		if( $this->getVar('planning') ) $type.=$this->getElement('Планировка:', $a['planning']);
		if( $this->getVar('building_material') ) $type.=$this->getElement('Материал строения:', $a['building_material']);
		if( $type )
		{
			$type='<div class="domstor_object_type">
					<h3>Тип квартиры (здания)</h3>
					<table>'.$type.'</table>
				</div>';
		}
		return $type;
	}

	public function getSizeBlock()
	{
		$a = &$this->object;
		$out = $this->getElementIf('Высота потолков:', $this->getFromTo($this->getVar('height_min'), $this->getVar('height_max'), ' м'));

        $square = '';
		$square.=$this->getElementIf($this->nbsp(4).'Общая:', $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max')));
		$square.=$this->getElementIf($this->nbsp(4).'Жилая:', $this->getFromTo($this->getVar('square_living_min'), $this->getVar('square_living_max')));
		$square.=$this->getElementIF($this->nbsp(4).'Кухня:',  $this->getFromTo($this->getVar('square_kitchen_min'), $this->getVar('square_kitchen_max')));
		if( $square ) $sqaure=$this->getElement('Площадь, кв.м.:', '').$square;

		$out.= $square;
		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>Размеры</h3>
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
		$communications.=$this->getElementIf($this->nbsp(4).'Телефон:', $this->getVar('phone_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'Интернет:', $this->getVar('internet_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'Кабельное ТВ:', $this->getVar('cable_tv_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'Домофон:', $this->getVar('door_phone_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'Газопровод:', $this->getVar('gas_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'Спутниковое ТВ:', $this->getVar('satellite_tv_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'Охранная сигнализация:', $this->getVar('signalizing_want'));
		$communications.=$this->getElementIf($this->nbsp(4).'Противопожарная сигнализация:', $this->getVar('fire_prevention_want'));

		if( $communications )
		{
			$out = $this->getElement('Коммуникации:', '').$communications;
		}

		$out.=$this->getElementIf($this->nbsp(4).'Состояние объекта не менее чем:', $this->getVar('state'));


		if( $out )
		{
			$out='<div class="domstor_object_technic">
					<h3>Технические характеристики</h3>
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
		$out.=$this->getElementIf('Наличие мебели:', $this->getVar('with_furniture_want'));
		$out.=$this->getElementIf('Грузовой лифт:', $this->getVar('lift_cargo_want'));
		$out.=$this->getElementIf('Парковка:', $this->getVar('parking'));
		$out.=$this->getElementIf('Квартира совместно с гаражом или паркоместом:', $this->getVar('sale_with_parking'));

		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>Обстановка, расположение:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return 'Заявка не найдена';
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

		$type = $this->getVar('house_type', 'дом');
		$out.= $type.' ';

		if( $a['city'] ) $out.= 'в '.$a['city'];

		$addr = $this->getStreetBuilding();
		$district = ($this->getVar('district_parent') == 'Пригород')? ', '.$this->getVar('district') : '';
		$out.= $district.($addr? ', '.$addr : ($this->getVar('address_note')? ', '.$this->getVar('address_note') : '' ));

		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['house_type'] ) $annotation.=', '.$a['house_type'];
		if( $a['room_count'] )  $annotation.=', '.$this->getRoomCount($a['room_count']);
		if( $a['square_house'] )  $annotation.=', '.$a['square_house'].' кв.м.';
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
					<h3>Число комнат</h3><p>'.
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
			$floor = $a['building_floor'].' эт.';
			if(  $this->getVar('ground_floor') ) $floor.=', цокольный этаж';
			if(  $this->getVar('mansard') ) $floor.=', мансарда';
			if(  $this->getVar('cellar') ) $floor.=', подвал';
			$floor='<div class="domstor_object_floor">
					<h3>Этажи</h3><p>'.
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
		if( $this->getVar('square_house') )  $square.=$this->getElement($this->nbsp(4).'Общая:', $a['square_house']);
		if( $this->getVar('square_living') ) $square.=$this->getElement($this->nbsp(4).'Жилая:', $a['square_living']);
		if( $this->getVar('square_kitchen') ) $square.=$this->getElement($this->nbsp(4).'Кухня:', $a['square_kitchen']);
		if( $this->getVar('square_utility') ) $square.=$this->getElement($this->nbsp(4).'Подсобные помещения:', $a['square_utility']);
		if( $square ) $sqaure=$this->getElement('Площадь, кв.м.:', '').$square;
		$out.=$sqaure;

        $size = '';
		if( $this->getVar('size_house_x') and $this->getVar('size_house_y') ) $size.=$this->getElement($this->nbsp(4).'Периметр:', $a['size_house_x'].' x '.$a['size_house_y'].' м');
		if( $this->getVar('size_house_z') ) $size.=$this->getElement($this->nbsp(4).'Высота под крышу:', $a['size_house_z'].' м');
		if( $this->getVar('size_house_z_full') ) $size.=$this->getElement($this->nbsp(4).'Высота с крышей:', $a['size_house_z_full'].' м');
		if( $size ) $size=$this->getElement('Размеры:', '').$size;
		$out.=$size;

        $square_ground = '';
        $ground = '';
		if( $this->getVar('square_ground') )
		{
			$square_ground = $a['square_ground'].' '.strtolower($a['square_ground_unit']);
		}
		else
		{
			if( $this->getVar('square_ground_m2') )$square_ground = $a['square_ground_m2'].' кв.м.';
		}
		if( $square_ground ) $ground.=$this->getElement($this->nbsp(4).'Площадь:', $square_ground);
		if( $this->getVar('size_ground_x') and $this->getVar('size_ground_y') ) $ground.=$this->getElement($this->nbsp(4).'Периметр, м.:', $a['size_ground_x'].' x '.$a['size_ground_y'].' м');
		if( $ground ) $ground=$this->getElement('Земельный участок:', '').$ground;
		$out.=$ground;

		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>Размеры</h3>
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

		if( $this->getVar('bath_house') ) $out.=$this->getElement($this->nbsp(4).'Баня:', $a['bath_house']);
		if( $this->getVar('swimming_pool') ) $out.=$this->getElement($this->nbsp(4).'Бассейн:', $a['swimming_pool']);
		if( $this->getVar('garage') ) $out.=$this->getElement($this->nbsp(4).'Гараж:', $a['garage']);
		if( $this->getVar('car_park_count') ) $out.=$this->getElement($this->nbsp(4).'Количество мест под автомобили:', $a['car_park_count']);
		if( $this->getVar('other_building') ) $out.=$this->getElement($this->nbsp(4).'Прочие постройки:', $a['other_building']);

		if( $out )
		{
			$out='<div class="domstor_object_buildings">
					<h3>Постройки</h3>
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
		if( $this->getVar('phone') ) $communications.='телефон, ';
		if( $this->getVar('internet') ) $communications.='интернет, ';
		if( $this->getVar('cable_tv') ) $communications.='кабельное ТВ, ';
		if( $this->getVar('door_phone') ) $communications.='домофон, ';
		if( $this->getVar('gas') ) $communications.='газопровод, ';
		if( $this->getVar('satellite_tv') ) $communications.='спутниковое ТВ, ';
		if( $this->getVar('signalizing') ) $communications.='охранная сигнализация, ';
		if( $this->getVar('fire_prevention') ) $communications.='противопожарная сигнализация, ';
		if( $communications )
		{
			$communications=substr($communications, 0, -2);
			$communications = $this->getElement('Коммуникации:', $communications);
			$show=true;
		}

        $san_tech = '';
		if( $this->getVar('toilet_count') ) $san_tech.=$this->getElement($this->nbsp(4).'Количество санузлов в доме:', $a['toilet_count']);

		//электричество
        $electro = '';
		if( $this->getVar('electro_voltage') ) $electro=$this->getElement($this->nbsp(4).'Напряжение', $a['electro_voltage'].' В');
		if( $this->getVar('electro_power') ) $electro=$this->getElement($this->nbsp(4).'Мощность', $a['electro_power'].' кВт');
		if( $this->getVar('electro_reserve') ) $electro=$this->getElement('', 'Резервная автономная подстанция');
		if( $this->getVar('electro_not') ) $electro=$this->getElement('', 'Нет электричества');
		if( $electro )
		{
			$electro = $this->getElement('Электроснабжение:', '').$electro;
			$show=true;
		}

		$heat=$this->getElementIf('Теплоснабжение:', $a['heat']);
		$water=$this->getElementIf('Водоснабжение:', $a['water']);
		$sewerage=$this->getElementIf('Канализация:', $a['sewerage']);

        $construction = '';
		$construction.=$this->getElementIf($this->nbsp(4).'Материал наружных стен:', $this->getVar('material_wall'));
		$construction.=$this->getElementIf($this->nbsp(4).'Материал перекрытий:', $this->getVar('material_ceiling'));
		$construction.=$this->getElementIf($this->nbsp(4).'Материал несущих конструкций:', $this->getVar('material_carying'));
		$construction.=$this->getElementIf($this->nbsp(4).'Материал кровли:', $this->getVar('roof_material'));
		$construction.=$this->getElementIf($this->nbsp(4).'Тип кровли:', $this->getVar('roof_type'));
		$construction.=$this->getElementIf($this->nbsp(4).'Фундамент:', $this->getVar('foundation'));


		if( $construction )
		{
			$construction = $this->getElement('Конструкция здания:', '').$construction;
			$show=true;
		}

        $windows = '';
		if( $this->getVar('window_material') ) $windows.=$this->getElement($this->nbsp(4).'Материал рам:', $a['window_material']);
		if( $this->getVar('window_glasing') ) $windows.=$this->getElement($this->nbsp(4).'Тип остекления:', $a['window_glasing']);
		if( $this->getVar('window_opening') ) $windows.=$this->getElement($this->nbsp(4).'Тип открывания:', $a['window_opening']);
		if( $windows )
		{
			$windows = $this->getElement('Окна:', '').$windows;
			$show=true;
		}

        $doors = '';
		if( $this->getVar('door_room') ) $doors.=$this->getElement($this->nbsp(4).'Двери межкомнатные:', $a['door_room']);

		//	отделка
        $finish = '';
		if( $this->getVar('finish_ceiling') ) $finish.=$this->getElement($this->nbsp(4).'Потолки:', $a['finish_ceiling']);
		if( $this->getVar('finish_paul') ) $finish.=$this->getElement($this->nbsp(4).'Полы: ', $a['finish_paul']);
		if( $this->getVar('finish_partition') ) $finish.=$this->getElement($this->nbsp(4).'Перегородки: ', $a['finish_partition']);
		if( $this->getVar('facade') ) $finish.=$this->getElement($this->nbsp(4).'Фасад: ', $a['facade']);
		if( $finish )
		{
			$finish = $this->getElement('Отделка:', '').$finish;
			$show=true;
		}

		//	состояние
        $state = '';
		if( $this->getVar('build_year') ) $state.=$this->getElement($this->nbsp(4).'Год постройки:', $a['build_year']);
		if( $this->getVar('wearout') ) $state.=$this->getElement($this->nbsp(4).'Процент износа: ', $a['wearout'].'%');
		if( $this->getVar('state') ) $state.=$this->getElement($this->nbsp(4).'Состояние:', $a['state']);
		if( $state )
		{
			$state = $this->getElement('Состояние объекта:', '').$state;
			$show=true;
		}

		if( $show )
		{
			$out='<div class="domstor_object_technic">
					<h3>Технические характеристики</h3>
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
		if( $this->getVar('with_furniture') ) $obstanovka.='С мебелью, ';
		if( $this->getVar('garden') ) $obstanovka.='Посадки, огород на участке, ';
		if( $this->getVar('landscape_design') ) $obstanovka.='Ландшафтный дизайн, ';
		if( $this->getVar('improvement_territory') ) $obstanovka.='Прилегающая территория благоустроена, ';
		if( $obstanovka )
		{
			$obstanovka=substr($obstanovka, 0, -2);
			$out.=$this->getElement('Обстановка:', $obstanovka);
		}

		$out.=$this->getElementIf('Ограда:', $this->getVar('fence'));

		if( $this->getVar('garbage_chute') ) $out.=$this->getElement('Мусоропровод:', 'имеется');
		if( $this->getVar('security') ) $out.=$this->getElement('Охрана:', $a['security']);
		if( $this->getVar('sale_with_parking') ) $parking=', Возможна продажа совместно с гаражом или паркоместом';
		if( $this->getVar('parking') ) $out.=$this->getElement('Парковка:', $a['parking'].$parking);

		$road = '';
        $road.=$this->getElementIf($this->nbsp(4).'Покрытие дорог:', $this->getVar('road_covering'));
		$road.=$this->getElementIf($this->nbsp(4).'Состояние покрытия дорог:', $this->getVar('road_state'));
		if( $road )
		{
			$out.=$this->getElement('Дорожные условия:','').$road;
		}


		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>Обстановка, расположение:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return 'Объект не найден';
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
        $out.= $this->getVar('house_type', 'частный дом, коттедж');
        $city = $this->getVar('city');
        if( $city ) $out.= ' в '.$city;
		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['house_type'] ) $annotation.=', '.$a['house_type'];
		$rooms=$this->getFromTo($a['room_count_min'], $a['room_count_max'], ' комн.');
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
		$rooms=$this->getFromTo($a['room_count_min'], $a['room_count_max'], ' комн.');
		if( $rooms )
		{
			$rooms='<div class="domstor_object_rooms">
					<h3>Число комнат</h3><p>'.
					$rooms.
				'</p></div>';
		}
		return $rooms;
	}

	public function getSizeBlock()
	{
		$a=&$this->object;
        $out = '';
		$out.=$this->getElementIf('Общая площадь дома:', $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max')), ' кв.м');
		$out.=$this->getElementIf('Площадь жилых комнат:', $this->getFromTo($this->getVar('square_living_min'), $this->getVar('square_living_max')), ' кв.м');
		$out.=$this->getElementIF('Площадь земельного участка:',  $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max')), ' '.$this->getVar('square_ground_unit'));
		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>Размеры дома и участка</h3>
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
		$out.=$this->getElementIf('Баня:', $this->getVar('bath_house_want'));
		$out.=$this->getElementIf('Бассейн:', $this->getVar('swimming_pool_want'));
		$out.=$this->getElementIf('Гараж:', $this->getVar('garage_want'));
		$out.=$this->getElementIf('Количество мест под автомобили не менее:', $this->getVar('car_park_count'));

		if( $out )
		{
			$out='<div class="domstor_object_buildings">
					<h3>Постройки</h3>
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
		$out.=$this->getElementIf('Телефон:', $this->getVar('phone_want'));
		$out.=$this->getElementIf('Газопровод:', $this->getVar('gas_want'));
		$out.=$this->getElementIf('Кабельное ТВ:', $this->getVar('cable_tv_want'));
		$out.=$this->getElementIf('Спутниковое ТВ:', $this->getVar('satellite_tv_want'));
		$out.=$this->getElementIf('Интернет:', $this->getVar('internet_want'));
		$out.=$this->getElementIf('Охранная сигнализация:', $this->getVar('signalizing_want'));
		$out.=$this->getElementIf('Противопожарная сигнализация:', $this->getVar('fire_prevention_want'));
		$out.=$this->getElementIf('Санузел в доме:', $this->getVar('toilet_want'));

		$electro = '';
        $electro.=$this->getElementIf($this->nbsp(4).'Напряжение:', $this->getVar('electro_voltage'));
		$electro.=$this->getElementIf($this->nbsp(4).'Мощность не менее:', $this->getVar('electro_power'));
		if( $electro ) $out.=$this->getElement('Электроснабжение:', '').$electro;

		$out.=$this->getElementIf('Теплоснабжение:', $this->getVar('heat'));
		$out.=$this->getElementIf('Водоснабжение:', $this->getVar('water'));
		$out.=$this->getElementIf('Канализация:', $this->getVar('sewerage'));

		$out.=$this->getElementIf('Состояние объекта не менее чем:', $this->getVar('state'));


		if( $out )
		{
			$out='<div class="domstor_object_technic">
					<h3>Технические характеристики</h3>
					<table>'.
						$out.
					'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return 'Заявка не найдена';
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

		$type = $a['garage_type']? strtolower($a['garage_type']) : 'гараж';
		$out.= $type.' ';

		if( $a['city'] ) $out.= 'в '.$a['city'];

		$addr = $this->getStreetBuilding();
		$district = ($a['district_parent'] == 'Пригород' or $a['district'] == 'Пригород' )? ', '.$a['district'] : '';
		$out.= $district.($addr? ', '.$addr : ($a['address_note']? ', '.$a['address_note'] : '' ));

		if( isset($a['cooperative_name']) and $a['cooperative_name'] ) $out.= ' кооператив '.$a['cooperative_name'];

		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['garage_type'] ) $annotation.=', '.$a['garage_type'];
		if( $a['size_x'] and $a['size_y'] ) $annotation.=', '.$a['size_x'].' x '.$a['size_y'].' м';
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
		$floor.=$this->getElementIf('Вид размещения:', $this->getVar('placing_type'));
		$floor.=$this->getElementIf('Этаж расположения объекта:', $this->getVar('object_floor'), ' этаж');
		$floor.=$this->getElementIf('Этажей в строении:', $this->getVar('building_floor'));
		if( $floor )
		{
			$floor='<div class="domstor_object_allocation">
					<h3>Расположение</h3><table>'.
					$floor.
				'</table></div>';
		}
		return $floor;
	}

	public function getSizeBlock()
	{
		$out = '';
        $out.= $this->getElementIf('Ширина:', $this->getVar('size_x'), ' м' );
		$out.= $this->getElementIf('Длина:', $this->getVar('size_y'), ' м' );
		$out.= $this->getElementIf('Высота:', $this->getVar('size_z'), ' м' );
		$out.= $this->getElementIf('Площадь:', $this->getVar('square'), ' кв.м.' );

        $gate_size = '';
		$gate_size.= $this->getElementIf($this->nbsp(4).'Высота:', $this->getVar('gate_height'), ' м' );
		$gate_size.= $this->getElementIf($this->nbsp(4).'Ширина:', $this->getVar('gate_width'), ' м' );
		if( $gate_size ) $out.= $this->getElement('Размер ворот:','').$gate_size;

		$out.= $this->getElementIf('Количество машиномест:', $this->getVar('car_count'));
		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>Размеры</h3>
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

		$cellar = $this->getElementIf('Погреб:', $this->getVar('cellar'));
		$repair_pit =$this->getElementIf('Индивидуальная смотровая яма:', $this->getVar('repair_pit'));
		$gate_type = $this->getElementIf('Тип ворот:', $this->getVar('gate_type'));

        $electro = '';
		$electro.= $this->getElementIf($this->nbsp(8).'Напряжение', $this->getVar('electro_voltage'), ' В');
		$electro.= $this->getElementIf($this->nbsp(8).'Мощность', $this->getVar('electro_power'), ' кВт');
		if( $this->getVar('electro_reserve') ) $electro.= $this->getElement('', 'Резервная автономная подстанция');
		if( $this->getVar('electro_not') ) $electro.= $this->getElement('', 'Нет электричества');
		if( $electro ) $electro = $this->getElement($this->nbsp(4).'Электроснабжение:', '').$electro;

		$communications = '';
		$communications.= $this->getElementIf($this->nbsp(4).'Теплоснабжение:', $this->getVar('heat'));
		$communications.= $this->getElementIf($this->nbsp(4).'Вентиляция:', $this->getVar('ventilation'));
		$communications.= $this->getElementIf($this->nbsp(4).'Охранная сигнализация:', $this->getVar('signalizing'));
		$communications.= $this->getElementIf($this->nbsp(4).'Видеонаблюдение:', $this->getVar('video_tracking'));
		$communications.= $this->getElementIf($this->nbsp(4).'Противопожарная сигнализация:', $this->getVar('fire_signalizing'));
		$communications.= $this->getElementIf($this->nbsp(4).'Система пожаротушения:', $this->getVar('fire_prevention'));

		$show = false;
        if( $communications )
		{
			$communications = $this->getElement('Коммуникации:', '').$communications;
			$show = true;
		}

        $construction = '';
		$construction.=$this->getElementIf($this->nbsp(4).'Материал наружных стен:', $this->getVar('material_wall'));
		$construction.=$this->getElementIf($this->nbsp(4).'Материал перекрытий:', $this->getVar('material_ceiling'));


		if( $construction )
		{
			$construction = $this->getElement('Конструкция строения:', '').$construction;
			$show=true;
		}

		//	состояние
        $state = '';
		if( $this->getVar('build_year') ) $state.= $this->getElement($this->nbsp(4).'Год постройки:', $this->getVar('build_year'));
		if( $this->getVar('wearout') ) $state.= $this->getElement($this->nbsp(4).'Процент износа: ', $this->getVar('wearout').'%');
		if( $this->getVar('state') ) $state.= $this->getElement($this->nbsp(4).'Состояние:', $this->getVar('state'));
		if( $state )
		{
			$state = $this->getElement('Состояние объекта:', '').$state;
			$show = true;
		}

		if( $show )
		{
			$out = '<div class="domstor_object_technic">
					<h3>Технические характеристики</h3>
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

		$out.= $this->getElementIf('Охрана территории:', $this->getVar('territory_security'));
		$out.= $this->getElementIf('Общая смотровая яма:', $this->getVar('public_repair_pit'));
		$out.= $this->getElementIf('Автосервис в кооперативе:', $this->getVar('auto_service'));
		$out.= $this->getElementIf('Автомойка в кооперативе:', $this->getVar('auto_washing'));

        $road = '';
		$road.= $this->getElementIf($this->nbsp(4).'Покрытие проездов в кооперативе:', $this->getVar('road_covering_inside'));
		$road.= $this->getElementIf($this->nbsp(4).'Покрытие дорог на подъезде к кооперативу:', $this->getVar('road_covering'));
		$road.= $this->getElementIf($this->nbsp(4).'Состояние покрытия дорог:', $this->getVar('road_state'));

		if( $road )
		{
			$out.=$this->getElement('Дорожные условия:','').$road;
		}


		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>Обстановка, расположение:</h3>
					<table>'.$out.'</table>
				</div>';
		}

		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return 'Объект не найден';
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
        $out.= $this->getVar('garage_type', 'гараж');
        $city = $this->getVar('city');
        if( $city ) $out.= ' в '.$city;
		return $out;
	}

	public function getAnnotation()
	{
		$a=&$this->object;
		$annotation=$this->getOfferType($this->action);
		if( $a['garage_type'] ) $annotation.=', '.$a['garage_type'];
		if( $a['size_x'] and $a['size_y'] ) $annotation.=', не менее '.$a['size_x'].' x '.$a['size_y'].' м';
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
					<h3>Расположение</h3>'.
					$out.
				'</div>';
		}
		return $out;
	}

	public function getSizeBlock()
	{
        $out = '';
		$out.= $this->getElementIf('Ширина не менее:', $this->getVar('size_x'), ' м' );
		$out.= $this->getElementIf('Длина не менее:', $this->getVar('size_y'), ' м' );
		$out.= $this->getElementIf('Высота не менее:', $this->getVar('size_z'), ' м' );
		$out.= $this->getElementIf('Площадь не менее:', $this->getVar('square'), ' кв.м.' );

        $gate_size = '';
		$gate_size.= $this->getElementIf($this->nbsp(4).'Высота не менее:', $this->getVar('gate_height'), ' м' );
		$gate_size.= $this->getElementIf($this->nbsp(4).'Ширина не менее:', $this->getVar('gate_width'), ' м' );

        if( $gate_size ) $out.= $this->getElement('Размер ворот:','').$gate_size;

        $out.= $this->getElementIf('Количество машиномест:', $this->getFromTo($this->getVar('car_count_min'), $this->getVar('car_count_max')));
		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>Размеры</h3>
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

		$out.= $this->getElementIf('Наличие погреба:', $this->getVar('cellar_want'));
		$out.= $this->getElementIf('Наличие индивидуальной смотровой ямы:', $this->getVar('repair_pit_want'));

        $electro = '';
		$electro.= $this->getElementIf($this->nbsp(8).'Напряжение', $this->getVar('electro_voltage'), ' В');
		$electro.= $this->getElementIf($this->nbsp(8).'Мощность не менее', $this->getVar('electro_power'), ' кВт');
		if( $electro ) $electro = $this->getElement($this->nbsp(4).'Электроснабжение:', '').$electro;

		$communications = $electro;

		$communications.= $this->getElementIf($this->nbsp(4).'Теплоснабжение:', $this->getVar('heat_want'));
		$communications.= $this->getElementIf($this->nbsp(4).'Вентиляция:', $this->getVar('ventilation_want'));
		$communications.= $this->getElementIf($this->nbsp(4).'Охранная сигнализация:', $this->getVar('signalizing_want'));
		$communications.= $this->getElementIf($this->nbsp(4).'Видеонаблюдение:', $this->getVar('video_tracking_want'));
		$communications.= $this->getElementIf($this->nbsp(4).'Противопожарная сигнализация:', $this->getVar('fire_signalizing_want'));
		$communications.= $this->getElementIf($this->nbsp(4).'Система пожаротушения:', $this->getVar('fire_prevention_want'));

		if( $communications )
		{
			$communications = $this->getElement('Коммуникации:', '').$communications;
			$out.= $communications;
		}

		if( $this->getVar('state') ) $out.=$this->getElement('Состояние объекта не менее чем:', $this->getVar('state'));


		if( $out )
		{
			$out='<div class="domstor_object_technic">
					<h3>Технические характеристики</h3>
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

		$out.= $this->getElementIf('Охрана территории:', $this->getVar('territory_security_want'));
		$out.= $this->getElementIf('Общая смотровая яма:', $this->getVar('public_repair_pit_want'));
		$out.= $this->getElementIf('Автосервис в кооперативе:', $this->getVar('auto_service_want'));
		$out.= $this->getElementIf('Автомойка в кооперативе:', $this->getVar('auto_washing_want'));

		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>Обстановка, расположение:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return 'Заявка не найдена';
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

		$type = $a['land_type']? lcfirst($a['land_type']) : 'земельный участок';
		$out.= $type.' ';

		if( $a['city'] ) $out.= 'в '.$a['city'];

		$addr = $this->getStreetBuilding();
		$district = ($a['district_parent'] == 'Пригород' or $a['district'] == 'Пригород' )? ', '.$a['district'] : '';
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
		$out.= $this->getElementIf('Площадь:', $this->getVar('square_ground'), ' '.$this->getVar('square_ground_unit') );

        if( $this->getVar('size_ground_x') and $this->getVar('size_ground_y') )
            $out.=$this->getElement('По периметру:', $this->getVar('size_ground_x').' x '.$this->getVar('size_ground_y').' м' );

        if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>Размеры</h3>
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
		$out.= $this->getElementIf('Тип постройки:', $this->getVar('living_building'));
		$out.= $this->getElementIf('Число жилых комнат:', $this->getVar('room_count'));
		$out.= $this->getElementIf('Количество этажей:', $this->getVar('building_floor'));
		$out.= $this->getElementIf('Площадь:', $this->getVar('square_house'));
		$out.= $this->getElementIf('Отопление:', $this->getVar('heat'));
		$out.= $this->getElementIf('Состояние построек:', $this->getVar('state'));

        $other = '';
		if( $this->getVar('bath_house') ) $other.= 'Баня, ';
		if( $this->getVar('swimming_pool') ) $other.= 'Бассейн, ';
		if( $this->getVar('garage') ) $other.= 'Гараж, ';
		if( $this->getVar('other_building') ) $other.= 'Прочие, ';
		if( $other )
		{
			$other= substr($other, 0, -2);
			$out.= $this->getElement('Хозяйственные постройки:', $other);
		}

		if( $out )
		{
			$out='<div class="domstor_object_buildings">
					<h3>Постройки</h3>
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
		$electro.= $this->getElementIf($this->nbsp(8).'Напряжение', $this->getVar('electro_voltage'), ' В');
		$electro.= $this->getElementIf($this->nbsp(8).'Мощность', $this->getVar('electro_power'), ' кВт');
		if( $this->getVar('electro_reserve') ) $electro.= $this->getElement('', 'Резервная автономная подстанция');
		if( $this->getVar('electro_not') ) $electro.= $this->getElement('', 'Нет электричества');
		if( $electro ) $electro = $this->getElement($this->nbsp(4).'Электроснабжение:', '').$electro;

        $show = false;
		$communications = $electro;
        $water = '';
		if( $this->getVar('water_basin') ) $water=' (Возможность водозабора из ближайшего водоема)';
		$communications.= $this->getElementIf($this->nbsp(4).'Водоснабжение:', $this->getVar('water').$water);
		$communications.= $this->getElementIf($this->nbsp(4).'Год планируемого устройства коммуникаций:', $this->getVar('communications_year'));
		if( $communications )
		{
			$communications = $this->getElement('Коммуникации:', '').$communications;
			$show = true;
		}

		//	состояние
        $state = '';
		$state.= $this->getElementIf($this->nbsp(4).'Перепад высот:', $this->getVar('height_difference'), ' м');
		$state.= $this->getElementIf($this->nbsp(4).'Состав грунта:', $this->getVar('coat_structure'));
		$state.= $this->getElementIf($this->nbsp(4).'Высота подземных вод:', $this->getVar('ground_water_height'));
		$state.= $this->getElementIf($this->nbsp(4).'Наличие карстовых пустот:', $this->getVar('karstic_hole'));

		if( $state )
		{
			$state = $this->getElement('Состояние объекта:', '').$state;
			$show = true;
		}

		if( $show )
		{
			$out='<div class="domstor_object_technic">
					<h3>Технические характеристики</h3>
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

		$out.= $this->getElementIf('Ограждение:', $this->getVar('fence'));
		$out.= $this->getElementIf('Удаленность от водоема:', $this->getVar('remote_water'), ' м');
		$out.= $this->getElementIf('Водоохранная зона:', $this->getVar('water_conservation_zone'), ' м');
		$out.= $this->getElementIf('Удаленность от лесного массива:', $this->getVar('remote_forest'), ' м');
		$out.= $this->getElementIf('Наличие лесопосадок на участке:', $this->getVar('forest_cover'));

        $road = '';
		$road.= $this->getElementIf($this->nbsp(4).'Удаленность участка от автотрассы:', $this->getVar('remote_highway'), ' м');
		$road.= $this->getElementIf($this->nbsp(4).'Покрытие подъездной дороги:', $this->getVar('road_covering'));
		$road.= $this->getElementIf($this->nbsp(4).'Состояние покрытия дорог:', $this->getVar('road_state'));
		$road.= $this->getElementIf($this->nbsp(4).'Возможность проезда зимой:', $this->getVar('road_winter'));

		if( $road )
		{
			$out.= $this->getElement('Дорожные условия:','').$road;
		}
		$out.= $this->getElementIf('Вид территории поселения:', $this->getVar('settlement_type'));

		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>Обстановка, расположение:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return 'Объект не найден';
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
        $out.= $this->getVar('land_type', 'садовый участок');
        $city = $this->getVar('city');
        if( $city ) $out.= ' в '.$city;
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
		$out = $this->getElementIf('Площадь:', $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit')));
		if( $out )
		{
			$out = '<div class="domstor_object_size">
					<h3>Размеры</h3>
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
		$out.=$this->getElementIf('Требуются жилые постройки:', $this->getVar('living_building'));
		$out.=$this->getElementIf('Отопление:', $this->getVar('heat_want'));
		$out.=$this->getElementIf('Состояние построек не менее чем:', $this->getVar('state'));

        $other = '';
		if( $this->getVar('bath_house') ) $other.='Баня, ';
		if( $this->getVar('swimming_pool') ) $other.='Бассейн, ';
		if( $this->getVar('garage') ) $other.='Гараж, ';
		if( $other )
		{
			$other = substr($other, 0, -2);
			$out.= $this->getElement('Хозяйственные постройки:', $other);
		}


		if( $out )
		{
			$out='<div class="domstor_object_buildings">
					<h3>Постройки</h3>
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
		$electro.=$this->getElementIf($this->nbsp(4).'Напряжение', $this->getVar('electro_voltage'), ' В');
		$electro.=$this->getElementIf($this->nbsp(4).'Мощность не менее', $this->getVar('electro_power'), ' кВт');

		if( $electro ) $out = $this->getElement('Электроснабжение:', '').$electro;

		$out.= $this->getElementIf('Водоснабжение:', $this->getVar('water_want'));

		if( $out )
		{
			$out='<div class="domstor_object_technic">
					<h3>Технические характеристики</h3>
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
		$road.= $this->getElementIf($this->nbsp(4).'Удаленность участка от автотрассы не более:', $this->getVar('remote_highway'), ' м');
		$road.= $this->getElementIf($this->nbsp(4).'Покрытие подъездной дороги:', $this->getVar('road_covering'));
		$road.= $this->getElementIf($this->nbsp(4).'Возможность проезда зимой:', $this->getVar('road_winter'));
		if( $road )
		{
			$out.= $this->getElement('Дорожные условия:','').$road;
		}
		$out.= $this->getElementIf('Вид территории поселения:', $this->getVar('settlement_type'));

		if( $out )
		{
			$out = '<div class="domstor_object_furniture">
					<h3>Обстановка, расположение:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return 'Заявка не найдена';
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
		$out.= $this->getIf(strtolower($this->getPurpose()), ' (назначение: ', ')');

		$out.= $this->getIf( $a['Agency']['name'], ' &mdash; ');
		return $out;
	}

	public function getTitle()
	{
		$a = &$this->object;
		$out = $this->getOfferType2().' нежилое помещение ';

		if( $a['city'] ) $out.= 'в '.$this->getVar('city');

		$addr = $this->getStreetBuilding();
		$district = ($this->getVar('district_parent') == 'Пригород' or $this->getVar('district') == 'Пригород' )? ', '.$this->getVar('district') : '';
		$out.= $district.($addr? ', '.$addr : ($this->getVar('address_note')? ', '.$this->getVar('address_note') : '' ));

		return $out;
	}

	public function getSquareGround()
	{
		$a=&$this->object;
		return $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit'), 'Площадь земельного участка ');
	}

	public function getSquareHouse()
	{
		$a=&$this->object;
		return $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max'), ' кв.м',  'Площадь помещений ');
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
				$out = ($min == '0')? 'цоколь' : $min;
			}
			else
			{
				$out = 'от&nbsp;'.$min.' до&nbsp;'.$max;
				$out = str_replace('0', 'цоколя', $out);
			}
		}
		elseif( $min_flag or $max_flag )
		{
			if( $min_flag )
			{
				$out = 'от&nbsp;'.$min;
			}
			else
			{
				$out = 'до&nbsp;'.$max;
			}
			$out = str_replace('0', 'цоколя', $out);
		}

		return $out;
	}

	public function getAnnotation()
	{
		$a = $this->object;
		$annotation=$this->getOfferType($this->action);
		$annotation.=$this->getIf( $this->getPurpose(), ', ');
		if( $this->getVar('complex_id') )$annotation.=', В составе имущественного комплекса';
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
		if( $this->getVar('delay_sale_dt') ) $out.='<p>Отсроченная продажа с '.date('d.m.Y', strtotime($this->getVar('delay_sale_dt'))).'</p>';
		if( $this->getVar('delay_rent_dt') ) $out.='<p>Отсроченная аренда с '.date('d.m.Y', strtotime($this->getVar('delay_rent_dt'))).'</p>';
		if( $out )
		{
			$out='<div class="domstor_object_delay">
					<h3>Отсроченное предложение</h3>'.
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
			$out = 'Объект <a href="'.$this->getCommerceUrl($this->object['id']).'" class="domstor_link">'.$this->object['code'].'</a>, '.$this->getAnnotation();
			$this->object = $a;
		}

		if( $out )
		{
			$out='<div class="domstor_object_complex">
					<h3>В составе имущественного комплекса</h3>'.
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
				$out.='<p><a href="'.$this->getCommerceUrl($this->object['id']).'" class="domstor_link">Объект '.$object['code'].'</a>, '.$this->getAnnotation().'</p>';
			}
			$this->object=$a;
		}

		if( $out )
		{
			$out='<div class="domstor_object_complexobj">
					<h3>Объекты имущественного комплекса</h3>'.
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
			$out=$this->getElementIf('Земельный участок:', $out);
			$out.=$this->getElementIf('Класс объекта:', $this->getVar('class'));
			if( $out )
			{
				$out='<div class="domstor_object_purpose">
						<h3>Назначение</h3><table>'.
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
		$out.=$this->getElementIf('Площадь помещений:', $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max'), ' кв.м') );
		$out.=$this->getElementIf('Площадь земельного участка:', $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit')) );
		$out.=$this->getElementIf('Высота помещений:', $this->getFromTo($this->getVar('height_min'), $this->getVar('height_max'), ' м') );
		$out.=$this->getElementIf('Количество ворот:', $this->getVar('gate_count'));
		$out.=$this->getElementIf('Максимальная высота ворот:', $this->getVar('gate_height'), ' м');
		$out.=$this->getElementIf('Максимальная ширина ворот:', $this->getVar('gate_width'), ' м');
		$out.=$this->getElementIf('Количество входов:', $this->getVar('door_count'));
		$out.=$this->getElementIf('Количество загрузочных окон:', $this->getVar('load_window_count'));

		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>Размеры</h3>
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
			$name='С отдельным входом';
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
		if( $this->getVar('inside_building') ) $inside=', Помещение внутри здания ('.$this->getVar('inside_building').')';
		$out=$this->getElementIf('Особенности размещения:', $name.$pt.$inside);
		return $out;
	}

	public function getAllocationBlock()
	{
        $out = '';
		$out.=$this->getElementIf('Этаж объекта:',$this->getObjectFloor($this->getVar('object_floor_min'), $this->getVar('object_floor_max')));
		$out.=$this->getElementIf('Этажность здания:', $this->getFromTo($this->getVar('building_floor_min'), $this->getVar('building_floor_max')) );
		if( $this->getVar('ground_floor') ) $out.=$this->getElement('', 'Имеется цокольный этаж');
		$out.=$this->getAllocationSubBlock();
		if( $out )
		{
			$out='<div class="domstor_object_allocation">
					<h3>Размещение объекта</h3>
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
		$out.=$this->getElementIf('Телефонных линий:', $this->getVar('phone_count'));
		$out.=$this->getElementIf('Интернет-провайдеров:', $this->getVar('internet_count'));

        $electro = '';
		$electro.=$this->getElementIf($this->nbsp(4).'Напряжение', $this->getVar('electro_voltage'), ' В');
		$electro.=$this->getElementIf($this->nbsp(4).'Мощность', $this->getVar('electro_power'), ' кВт');
		$electro.=$this->getElementIf($this->nbsp(4).'Возможность увеличения мощности до', $this->getVar('electro_power_up'), ' кВт');
		if( $this->getVar('electro_reserve') ) $electro.=$this->getElement('', 'Есть резервная автономная подстанция');
		if( $this->getVar('electro_not') ) $electro.=$this->getElement('', 'Нет электричества');
		if( $this->getVar('electro_allow') ) $electro.=$this->getElement('', 'Получена документация для подключения');
		if( $electro ) $electro = $this->getElement('Электроснабжение:', '').$electro;
		$out.=$electro;


		$out.=$this->getElementIf('Теплоснабжение:', $this->getVar('heat'));
		if( $this->getVar('heat_control') ) $out.=$this->getElementIf('', 'Регулируемый температурный режим');
		$out.=$this->getElementIf('Водоснабжение:', $this->getVar('water'));
		if( $this->getVar('water_reserve') ) $out.=$this->getElementIf('', 'Резервная скважина');
		$out.=$this->getElementIf('Канализация:', $this->getVar('sewerage'));
		$out.=$this->getElementIf('Вентиляция:', $this->getVar('ventilation'));
		$out.=$this->getElementIf('Газоснабжение:', $this->getVar('gas'));

        $construction = '';
		$construction.=$this->getElementIf($this->nbsp(4).'Материал наружных стен:', $this->getVar('material_wall'));
		$construction.=$this->getElementIf($this->nbsp(4).'Материал перекрытий:', $this->getVar('material_ceiling'));
		$construction.=$this->getElementIf($this->nbsp(4).'Материал несущих конструкций:', $this->getVar('material_carrying'));
		$construction.=$this->getElementIf($this->nbsp(4).'Минимальный шаг колонн:', $this->getVar('pillar_step'), ' м');
		$construction.=$this->getElementIf($this->nbsp(4).'Покрытие полов:', $this->getVar('paul_coating'));
		$construction.=$this->getElementIf($this->nbsp(4).'Уклон полов:', $this->getVar('paul_bias'));
		$construction.=$this->getElementIf($this->nbsp(4).'Нагрузка на пол:', $this->getVar('paul_loading'), ' кг/кв.м');
		if( $construction )	$out.=$this->getElement('Конструкция строения:', '').$construction;

		//	состояние
        $state = '';
		$state.=$this->getElementIf($this->nbsp(4).'Год постройки:', $this->getVar('build_year'));
		$state.=$this->getElementIf($this->nbsp(4).'Процент износа: ', $this->getVar('wearout'), '%');
		$state.=$this->getElementIf($this->nbsp(4).'Состояние:', $this->getVar('state'));
		if( $state ) $out.=$this->getElement('Состояние объекта:', '').$state;

        $ice = '';
		$ice.=$this->getElementIf($this->nbsp(4).'Холодильное оборудование:', $this->getVar('refrigerator'));
		$ice.=$this->getElementIf($this->nbsp(4).'Температурный режим:', $this->getFromTo($this->getVar('refrigerator_temperature_min'), $this->getVar('refrigerator_temperature_max'), ' &deg;C'));
		$ice.=$this->getElementIf($this->nbsp(4).'Объем камер:', $this->getVar('refrigerator_capacity'), ' куб.м');
		if( $ice ) $out.=$this->getElement('Холодильное оборудование:', '').$ice;

		$lifts = '';
        $lifts.=$this->getElementIf($this->nbsp(8).'Пассажирский лифт:', $this->getVar('lift_passenger'), $this->getIf($this->getVar('lift_passenger_weight'),', до ', ' кг'));
		$lifts.=$this->getElementIf($this->nbsp(8).'Грузовой лифт:', $this->getVar('lift_cargo'), $this->getIf($this->getVar('lift_cargo_weight'),', до ', ' кг'));
		$lifts.=$this->getElementIf($this->nbsp(8).'Эскалатор:', $this->getVar('escalator'));
		$lifts.=$this->getElementIf($this->nbsp(8).'Травалатор:', $this->getVar('travelator'));
		$lifts.=$this->getElementIf($this->nbsp(8).'Тельфер:', $this->getVar('telpher'), $this->getIf($this->getVar('telpher_weight'),', до ', ' кг'));
		$lifts.=$this->getElementIf($this->nbsp(8).'Кран-балка:', $this->getVar('crane_beam'), $this->getIf($this->getVar('crane_beam_weight'),', до ', ' т'));
		$lifts.=$this->getElementIf($this->nbsp(8).'Козловой кран:', $this->getVar('crane_trestle'), $this->getIf($this->getVar('crane_trestle_weight'),', до ', ' т'));
		if( $lifts ) $infra.=$this->getElement($this->nbsp(4).'Грузоподъемные устройства:', '').$lifts;

		$infra = '';
        $infra.=$this->getElementIf($this->nbsp(4).'Охрана:', $this->getVar('security'));
		$infra.=$this->getElementIf($this->nbsp(4).'Сигнализация:', $this->getVar('signalizing'));
		$infra.=$this->getElementIf($this->nbsp(4).'Система пожаротушения:', $this->getVar('fire_prevention'));
		$infra.=$this->getElementIf($this->nbsp(4).'Столовая:', $this->getVar('dinning'));
		$infra.=$this->getElementIf($this->nbsp(4).'Количество санузлов:', $this->getVar('toilet_count'));
		$infra.=$this->getElementIf($this->nbsp(4).'Технические особенности:', $this->getVar('technical_note'));
		if( $infra ) $out.=$this->getElement('Инфраструктура:', '').$infra;


		if( $out )
		{
			$out='<div class="domstor_object_technic">
					<h3>Технические характеристики</h3>
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
		$transp.=$this->getElementIf($this->nbsp(4).'Ж/д пути:', $this->getVar('realroad'));
		if( $this->getVar('realroad_not_active') ) $transp.=$this->getElementIf('', 'Не действующие');
		$transp.=$this->getElementIf($this->nbsp(4).'Протяженность путей:', $this->getVar('realroad_length'), ' м');
		$transp.=$this->getElementIf($this->nbsp(4).'Фронт выгрузки:', $this->getVar('realroad_load_length'), ' м');
		if( $this->getVar('pandus') ) $transp.=$this->getElement('', 'Пандус');
		$transp.=$this->getElementIf($this->nbsp(4).'Подъезд, разворот авто:', $this->getVar('road'));
		$transp.=$this->getElementIf($this->nbsp(4).'Парковка:', $this->getVar('parking'));
		if( $this->getVar('parking_underground') ) $transp.=$this->getElement($this->nbsp(4).'', 'Подземная');
		if( $this->getVar('parking_many_floor') ) $transp.=$this->getElement($this->nbsp(4).'', 'Многоярусная');
		if( $transp ) $out.=$this->getElement('Выгрузка, погрузка, парковка:','').$transp;

        $road = '';
		$road.=$this->getElementIf($this->nbsp(4).'Интенсивность транспортного потока:', $this->getVar('transport_stream'));
		$road.=$this->getElementIf($this->nbsp(4).'Интенсивность пешеходного потока:', $this->getVar('people_stream'));
		$road.=$this->getElementIf($this->nbsp(4).'Покрытие дорог:', $this->getVar('road_covering'));
		$road.=$this->getElementIf($this->nbsp(4).'Состояние покрытия дорог:', $this->getVar('road_state'));
		$road.=$this->getElementIf($this->nbsp(4).'Пропускная способность, полос:', $this->getVar('lanes_count'));
		if( $this->getVar('one_way_traffic') ) $road.=$this->getElement($this->nbsp(4).'Одностороннее движение', '');
		if( $road ) $out.=$this->getElement('Дорожные условия:','').$road;

		if( $out )
		{
			$out='<div class="domstor_object_transport">
					<h3>Транспортные условия</h3>
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
		$out.=$this->getElementIf('Удаленность от автотрассы:', $this->getVar('remote_highway'));
		$out.=$this->getElementIf('Удаленность от ж/д узла:', $this->getVar('remote_realroad'));
		$out.=$this->getElementIf('Рельеф:', $this->getVar('relief'));
		$out.=$this->getElementIf('Наличие леса:', $this->getVar('forest'));
		$out.=$this->getElementIf('Объекты на участке:', $this->getVar('objects'));
		$out.=$this->getElementIf('Непосредственное окружение:', $this->getVar('territory'));

		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>Обстановка, расположение:</h3>
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

		$price_ground_unit = $this->getVar('price_m2_unit')? $a['price_m2_unit'] : 'кв.м';

		$price = '';
		$price.= $this->getIf($this->getFormatedPrice($a['price_full'], $this->getVar('price_currency')));

		if( $this->getVar('offer_parts') ) $price.= $this->getIf($this->getPriceFromTo($a['price_m2_min'], $a['price_m2_max'], $this->getVar('price_currency')), ' (', '/ '.$price_ground_unit.')' );

        if( $this->getVar('active_sale') )
            $out.=$this->getElementIf('Цена:', $price);

		$rent ='';
		$rent_ground_unit = $this->getVar('rent_m2_unit')? $a['rent_m2_unit'] : 'кв.м';
		$rent.= $this->getIf($this->getFormatedPrice($a['rent_full'], $this->getVar('rent_currency'), $this->getVar('rent_period')));

		if( $this->getVar('active_rent') )
        {
            $out.= $this->getElementIf('Арендная ставка:', $this->getPriceFromTo($a['rent_m2_min'], $a['rent_m2_max'], $this->getVar('rent_currency'), $this->getVar('rent_period')), '/ '.$rent_ground_unit );
            $out.= $this->getElementIf($this->nbsp(4).'За весь объект:', $this->getFormatedPrice($a['rent_full'], $this->getVar('rent_currency'), $this->getVar('rent_period')));
            $out.= $this->getElementIf('Коммунальные платежи:', $this->getVar('rent_communal_payment'));
        }

		if( $out )
		{
			$out = '<div class="domstor_object_finance">
					<h3>Финансовые условия:</h3>
					<table>'.$out.'</table>
				</div>';
		}

		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return 'Объект не найден';
		$out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
					$this->getIf(strtolower($this->getPurpose()), '<h2>Назначение: ', '</h2>').
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
		$out.= $this->getIf( $this->getPurpose(), ' (назначение: ', ')');
		$out.= $this->getIf( $a['Agency']['name'], ' &mdash; ');
		return $out;
	}

	public function getTitle()
	{
		$out = $this->getOfferType2().' нежилое помещение';

        $city = $this->getVar('city');
        if( $city ) $out.= ' в '.$city;
		return $out;
	}

	public function getSquareGround()
	{
		$a=&$this->object;
		return $this->getFromTo($a['square_ground_min'], $a['square_ground_max'], ' '.$a['square_ground_unit'], 'Площадь земельного участка ');
	}

	public function getSquareHouse()
	{
		$a=&$this->object;
		return $this->getFromTo($a['square_house_min'], $a['square_house_max'], ' кв.м',  'Площадь помещений ');
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
			$rent=$this->getIf($this->getFormatedRent(), 'Не дороже ');
			$rent_m2=$this->getIf($this->getFormatedRentM2());
			if( $rent and $rent_m2 ) $rent_m2=' ('.$rent_m2.')';
			$out=$rent.$rent_m2;
		}
		else
		{
			$price=$this->getIf($this->getFormatedPrice(), 'Не дороже ');
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
		$annotation.=$this->getIf( $this->getPrice(), ', Не дороже ');
		$annotation.=$this->getIf( $a['note_addition'], ', ');
		return $annotation;
	}

	public function getDelayBlock()
	{
		$a = &$this->object;
        $out = '';
		if( isset($a['delay_sale_dt']) ) $out.='<p>Отсроченная продажа с '.date('d.m.Y', strtotime($a['delay_sale_dt'])).'</p>';
		if( isset($a['delay_rent_dt']) ) $out.='<p>Отсроченная аренда с '.date('d.m.Y', strtotime($a['delay_rent_dt'])).'</p>';
		if( $out )
		{
			$out='<div class="domstor_object_delay">
					<h3>Отсроченное предложение</h3>'.
					$out.
				'</div>';
		}
		return $out;
	}

	public function getPurposeBlock()
	{
        $out = '';

		$out.= $this->getElementIf('Требуемые назначения:', $this->getPurpose());
		$out.= $this->getElementIf('Предполагаемое использование объекта:', $this->getVar('use_plan'));
		$out.= $this->getElementIf('Разрешенный вид использования земельного участка:', $this->getVar('ground_use_allow'));
		$out.= $this->getElementIf('Класс объекта:', $this->getFromTo($this->getVar('class_min'), $this->getVar('class_max')));

		if( $out )
				{
					$out='<div class="domstor_object_purpose">
							<h3>Назначение</h3><table>'.
							$out.
						'</table></div>';
				}
		return $out;
	}

	public function getSizeBlock()
	{
        $out = '';

		$out.= $this->getElementIf('Площадь помещений:', $this->getFromTo($this->getVar('square_house_min'), $this->getVar('square_house_max'), ' кв.м') );
		$out.= $this->getElementIf('Площадь земельного участка:', $this->getFromTo($this->getVar('square_ground_min'), $this->getVar('square_ground_max'), ' '.$this->getVar('square_ground_unit')) );
		$out.= $this->getElementIf('Высота помещений:', $this->getFromTo($this->getVar('height_min'), $this->getVar('height_max'), ' м') );
		$out.= $this->getElementIf('Количество ворот не менее:', $this->getVar('gate_count'));
		$out.= $this->getElementIf('Максимальная высота ворот не менее:', $this->getVar('gate_height'), ' м');
		$out.= $this->getElementIf('Максимальная ширина ворот не менее:', $this->getVar('gate_width'), ' м');

		if( $out )
		{
			$out='<div class="domstor_object_size">
					<h3>Размеры</h3>
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

        if( $this->getVar('placing_separate_building') ) $placing_features[]='отдельно-стоящее здание';
		if( $this->getVar('placing_separate_door') ) $placing_features[]='с отдельным входом в здании';
		if( $this->getVar('placing_commerce_only') ) $placing_features[]='только нежилое здание';
		if( $this->getVar('inside_building') )
		{
			$inside = 'Помещение внутри здания';
			if( $this->getVar('inside_building') ) $inside.=' ('.$this->getVar('inside_building').')';
			$placing_features[]=$inside;
		}
		$out = $this->getElementIf('Особенности размещения:', implode(', ', $placing_features));
		return $out;
	}

	public function getAllocationBlock()
	{
        $out = '';
		$out.= $this->getElementIf('Этаж объекта:',$this->getFromTo($this->getVar('object_floor_min'), $this->getVar('object_floor_max')));
		$out.= $this->getElementIf('', $this->getVar('object_floor_limit'));
		$out.= $this->getAllocationSubBlock();
		if( $out )
		{
			$out='<div class="domstor_object_allocation">
					<h3>Размещение объекта</h3>
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

		$out.= $this->getElementIf('Телефонных линий не менее:', $this->getVar('phone_count'));
		$out.= $this->getElementIf('Интернет-провайдеров:', $this->getVar('internet_want'));

		$electro = '';
        $electro.= $this->getElementIf($this->nbsp(4).'Напряжение', $this->getVar('electro_voltage'), ' В');
		$electro.= $this->getElementIf($this->nbsp(4).'Мощность не менее', $this->getVar('electro_power'), ' кВт');
		if( $electro ) $electro = $this->getElement('Электроснабжение:', '').$electro;
		$out.= $electro;

		$out.= $this->getElementIf('Теплоснабжение:', $this->getVar('heat_want'));
		if( $this->getVar('heat_control') ) $out.= $this->getElementIf('', 'Регулируемый температурный режим');
		$out.= $this->getElementIf('Водоснабжение:', $this->getVar('water_want'));
		$out.= $this->getElementIf('Вид водоснабжения:', $this->getVar('water'));
		if( $this->getVar('water_reserve') ) $out.= $this->getElementIf('', 'Резервная скважина');
		$out.= $this->getElementIf('Канализация:', $this->getVar('sewerage_want'));
		$out.= $this->getElementIf('Вид канализации:', $this->getVar('sewerage'));
		$out.= $this->getElementIf('Газоснабжение:', $this->getVar('gas_want'));
		$out.= $this->getElementIf('Вид газоснабжения:', $this->getVar('gas'));

        $construction = '';
		$construction.= $this->getElementIf($this->nbsp(4).'Шаг колонн не менее:', $this->getVar('pillar_step'), ' м');
		$construction.= $this->getElementIf($this->nbsp(4).'Покрытие полов:', $this->getVar('paul_coating'));
		$construction.= $this->getElementIf($this->nbsp(4).'Уклон полов:', $this->getVar('paul_bias'));
		$construction.= $this->getElementIf($this->nbsp(4).'Нагрузка на пол не менее:', $this->getVar('paul_loading'), ' кг/кв.м');
		if( $construction )	$out.=$this->getElement('Конструкция строения:', '').$construction;

		//	состояние
        $state = '';
		$state.=$this->getElementIf($this->nbsp(4).'Не менее чем:', $this->getVar('state'));
		if( $state ) $out.=$this->getElement('Состояние объекта:', '').$state;


        $lift_pas_w = $lift_car_w = $telpher_w = $crane_beam_w = $crane_tres_w = '';
		if( $this->getVar('lift_passenger_weight') ) $lift_pas_w=' до '.$this->getVar('lift_passenger_weight').' кг';
		if( $this->getVar('lift_cargo_weight') ) $lift_car_w=' до '.$this->getVar('lift_cargo_weight').' кг';
		if( $this->getVar('telpher_weight') ) $telpher_w=' до '.$this->getVar('telpher_weight').' т';
		if( $this->getVar('crane_beam_weight') ) $crane_beam_w=' до '.$this->getVar('crane_beam_weight').' т';
		if( $this->getVar('crane_trestle_weight') ) $crane_tres_w=' до '.$this->getVar('crane_trestle_weight').' т';

		$lifts = '';
        if( $this->getVar('lift_passenger') ) $lifts.= 'пассажирский лифт'.$lift_pas_w.', ';
		if( $this->getVar('lift_cargo') ) $lifts.= 'грузовой лифт'.$lift_car_w.', ';
		if( $this->getVar('escalator') ) $lifts.= 'эскалатор, ';
		if( $this->getVar('travelator') ) $lifts.= 'травалатор, ';
		if( $this->getVar('telpher') ) $lifts.= 'тельфер'.$telpher_w.', ';
		if( $this->getVar('crane_beam') ) $lifts.= 'кран-балка'.$crane_beam_w.', ';
		if( $this->getVar('crane_trestle') ) $lifts.= 'козловой кран'.$crane_tres_w.', ';

		$lifts = substr($lifts, 0, -2);

        $infra = '';
		$infra.= $this->getElementIf($this->nbsp(4).'Необходимые грузоподъемные устройства:', $lifts);
		$infra.= $this->getElementIf($this->nbsp(4).'Санузел:', $this->getVar('toilet_want'));
		if( $infra ) $out.= $this->getElement('Инфраструктура:', '').$infra;

		$ice = '';
        $ice.= $this->getElementIf($this->nbsp(4).'Холодильное оборудование:', $this->getVar('refrigerator_want'));
		$ice.= $this->getElementIf($this->nbsp(4).'Температурный режим:', $this->getFromTo($this->getVar('refrigerator_temperature_min'), $this->getVar('refrigerator_temperature_max'), ' &deg;C'));
		$ice.= $this->getElementIf($this->nbsp(4).'Объем камер:',  $this->getFromTo($this->getVar('refrigerator_capacity_max'), $this->getVar('refrigerator_capacity_min'), ' куб.м'));
		if( $ice ) $out.= $this->getElement('Холодильное оборудование:', '').$ice;

		if( $out )
		{
			$out='<div class="domstor_object_technic">
					<h3>Технические характеристики</h3>
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
		$transp.= $this->getElementIf($this->nbsp(4).'Ж/д пути, ж/д тупик', $this->getVar('realroad_want'));
		$transp.= $this->getElementIf($this->nbsp(4).'Протяженность путей:', $this->getVar('realroad_length'), ' м');
		$transp.= $this->getElementIf($this->nbsp(4).'Фронт выгрузки:', $this->getVar('realroad_load_length'), ' м');
		$transp.= $this->getElementIf($this->nbsp(4).'Пандус:', $this->getVar('pandus_want'));
		$transp.= $this->getElementIf($this->nbsp(4).'Подъезд, разворот авто:', $this->getVar('road'));
		$transp.= $this->getElementIf($this->nbsp(4).'Парковка:', $this->getVar('parking'));
		if( $transp ) $out.=$this->getElement('Выгрузка, погрузка, парковка:','').$transp;

        $road = '';
		$road.= $this->getElementIf($this->nbsp(4).'Интенсивность транспортного потока:', $this->getVar('transport_stream'));
		$road.= $this->getElementIf($this->nbsp(4).'Интенсивность пешеходного потока:', $this->getVar('people_stream'));
		if( $road ) $out.= $this->getElement('Дорожные условия:','').$road;

		if( $out )
		{
			$out='<div class="domstor_object_transport">
					<h3>Транспортные условия</h3>
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

		$out.=$this->getElementIf('Удаленность от автотрассы:', $this->getVar('remote_highway'));
		$out.=$this->getElementIf('Удаленность от ж/д узла:', $this->getVar('remote_realroad'));
		$out.=$this->getElementIf('Рельеф:', $this->getVar('relief'));
		$out.=$this->getElementIf('Наличие леса:', $this->getVar('forest'));
		$out.=$this->getElementIf('Объекты на участке:', $this->getVar('objects'));
		$out.=$this->getElementIf('Непосредственное окружение:', $this->getVar('territory'));

		if( $out )
		{
			$out='<div class="domstor_object_furniture">
					<h3>Обстановка, расположение:</h3>
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
            $out.=$this->getElementIf('Бюджет:', $price.$price_m2, '', 'не более ');

        if( $this->getVar('active_rent') )
        {
            $out.=$this->getElementIf('Бюджет:', $this->getFormatedRentM2(), '', 'не более ');
            $out.=$this->getElementIf($this->nbsp(4).'За весь объект:',  $this->getFormatedRent(), '', 'не более ');
        }

		if( $out )
		{
			$out = '<div class="domstor_object_finance">
					<h3>Финансовые условия:</h3>
					<table>'.$out.'</table>
				</div>';
		}
		return $out;
	}

	public function getHtml()
	{
		if( $this->isEmpty() ) return 'Заявка не найдена';
		$out='	<div class="domstor_object_head">
					<h1>'.$this->getTitle().'</h1>'.
					$this->getIf(strtolower($this->getPurpose()), '<h2>Назначение: ', '</h2>').
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
