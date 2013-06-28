<?php

/**
 * Description of Common
 *
 * @author pahhan
 */
abstract class Domstor_Detail_Common
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