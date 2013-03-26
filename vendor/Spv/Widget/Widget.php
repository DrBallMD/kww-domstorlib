<?php

/**
 * Description of Widget
 *
 * @author pahhan
 */
abstract class Spv_Widget_Widget implements Spv_Widget_WidgetInterface
{
    protected $_is_visible = TRUE;

	public function __toString()
	{
		return $this->render();
	}

	public function display()
	{
		echo $this->render();
	}

	public function hide()
	{
		$this->_is_visible = FALSE;
		return $this;
	}

	public function show()
	{
		$this->_is_visible = TRUE;
		return $this;
	}

	public function toggleVisible()
	{
		if( $this->isVisible ) $this->hide();
		else $this->show();
		return $this;
	}

	public function isVisible()
	{
		return $this->_is_visible;
	}
}

