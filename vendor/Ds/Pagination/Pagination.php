<?php

/**
 * Description of Pagination
 *
 * @author pahhan
 */
class Ds_Pagination_Pagination
{
	protected $on_page = 20;
	protected $pager_count = 13;
	protected $total;
	protected $href_tmpl;			// /some-list/%page
	protected $link_tmpl; 			// <a href="%href">%text</a>
	protected $current_page_tmpl; 	// <span>%text</span>
	protected $layout_tmpl; 		// <div class="pager">%text</div>
	protected $first_page_text;
	protected $last_page_text;
    protected $template = '@list/pagination/base.html.twig';
    protected $current;

    protected $url_generator;

    protected $data;

    protected $templating;

    public function __construct(Spv_Templating_TemplatingInterface $templating)
	{
		$this->templating = $templating;
	}

    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    public function setCurrent($current)
    {
        $this->current = $current;
        return $this;
    }

    public function setOnPage($onpage)
    {
        $this->on_page = $onpage;
        return $this;
    }

    public function setHrefTmpl($href_tmpl)
    {
        $this->href_tmpl = $href_tmpl;
        return $this;
    }

    public function setUrlGenerator($url_generator)
    {
        $this->url_generator = $url_generator;
    }

    public function process($current)
	{
		$total = $this->total;
		$on_page = $this->on_page;
		$pager_count = $this->pager_count;

		if( $total <= $on_page ) return false;

		$plus = $total % $on_page? 1 : 0;
		$out['last_page']=(int)(floor($total / $on_page)) + $plus;

		$out['prev']=$current-1;

		if( $current<=1 )
		{
			$out['is_first']=true;
			$current=1;
			$out['prev'] = FALSE;
		}
		$out['next']=$current+1;

		if( $current>=$out['last_page'] )
		{
			$out['is_last']=true;
			$current=$out['last_page'];
			$out['next'] = FALSE;
		}

		$out['current'] = $current;

		if( $pager_count )
		{
			if( $out['last_page']<=$pager_count )
			{
				$before=1;
				$after=$out['last_page'];
			}
			else
			{
				$near_count=(int)floor($pager_count/2);
				$before = $current - $near_count;
				$after  = $current + $near_count;
				$before_check=$before-1;
				if( $before_check < 0 )
				{
					$before=1;
					$after=$after-$before_check;
					if( $after>$out['last_page'] ) $after=$out['last_page'];
				}

				$after_check=$after-$out['last_page'];
				if( $after_check > 0 )
				{
					$after=$out['last_page'];
					$before=$before-$after_check;
					if( $before<1 ) $before=1;
				}

			}

			for($i=$before; $i<=$after; $i++)
			{
				$out['pages'][]=$i;
			}
		}
		$this->data = $out;
		return true;
	}

	public function getFirst()
	{
		return 1;
	}

	public function getLast()
	{
		return $this->data['last_page'];
	}

	public function getPrev()
	{
		return $this->data['prev'];
	}

	public function getCurrent()
	{
		return $this->data['current'];
	}

	public function getNext()
	{
		return $this->data['next'];
	}

	public function isLast()
	{
		return $this->data['is_last'];
	}

	public function isFirst()
	{
		return $this->data['is_first'];
	}

	public function getPages()
	{
		return $this->data['pages'];
	}

	public function getPagerCount()
	{
		return $this->pager_count;
	}

	public function renderHref($href, $current, $replaces=array())
	{
		if( is_array($replaces) and count($replaces)>0 )
		{
			$keys = array_keys($replaces);
			$values = array_values($replaces);
		}
		$keys[]	= '%page';
		$values[] =	$current;
		return str_replace($keys, $values, $href);
	}

    public function getHref($page)
    {
        return $this->url_generator->generatePage($page);
    }

    public function set($name, $value)
	{
		$this->$name = $value;
		return $this;
	}

    public function render()
    {
        if( !$this->process($this->current) ) return '';
        $this->data['pagination'] = $this;
        return $this->templating->render($this->template, $this->data);
    }

    public function display($current, $replaces=array(), $return=false)
	{
		if( !$this->process($current) ) return '';
		$content='';
		//echo $this->href_tmpl;
		foreach($this->getPages() as $page)
		{
			if( $page==$this->getCurrent() )
			{
				$text = str_replace('%text', $page, $this->current_page_tmpl);
			}
			else
			{
				$href = str_replace('%page', $page, $this->href_tmpl);
				$text = str_replace(array('%href', '%text'), array($href, $page), $this->link_tmpl);
			}
			$content.= $text;
		}

		if( $this->getLast() > $this->getPagerCount() )
		{
			$info = 'Страница&nbsp;'.$this->getCurrent().'&nbsp;из&nbsp;'.$this->getLast();

		}
		else
		{
			$info = '';
		}

		$out = str_replace(array('%info','%text'), array($info, $content), $this->layout_tmpl);

		if( $return )return $out;
		echo $out;
	}
}
