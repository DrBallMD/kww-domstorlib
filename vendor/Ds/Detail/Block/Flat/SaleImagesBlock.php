<?php

/**
 * Description of SaleImagesBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_Flat_SaleImagesBlock extends Ds_Detail_Block_AbstractBlock
{
    protected $flash;

    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

	public function photos()
	{
		$out = array();
		if( $this->getData()->isSetAndArray('img_photo') )
            foreach ($this->getData()->get('img_photo') as $photo)
                $out[] = 'http://domstor.ru'.$photo;


		return $out;
	}

    public function plans()
	{
		$out = array();
		if( $this->getData()->isSetAndArray('img_plan') )
            foreach ($this->getData()->get('img_plan') as $photo)
                $out[] = 'http://domstor.ru'.$photo;


		return $out;
	}

    protected function validateUrl($url)
    {
        return preg_match('/^(http|https|ftp)\:\/\/[a-zA-Z0-9][a-zA-Z0-9\.\-]+(\.[a-zA-Z]{2,6}\/)([a-zA-z0-9\-\/])+\/{0,1}(\?){0,1}.*/', $url);
    }

    protected function parseSrc($text)
	{
		$to_find = ' src="';
		$start_pos = strpos($text, $to_find);

		if( !$start_pos ) return false;

		$start_pos += strlen($to_find);
		$stop_pos = strpos($text, '"', $start_pos);

		if( !$stop_pos ) return false;

		$res = substr($text, $start_pos, $stop_pos - $start_pos);

		return $res;
	}

    public function flash()
	{
        if( $this->flash ) return $this->flash;
		$video = $this->getData()->get('video_weblink');
		if( !$video ) return;

		if( $this->validateUrl($video) )
		{
			$src = $video;
		}
		else
		{
			$src = $this->parseSrc($video);
			if( !$this->validateUrl($src) ) return;
		}

		$this->flash = $src;

        return $src;
	}
}

