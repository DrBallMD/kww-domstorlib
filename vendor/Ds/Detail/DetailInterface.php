<?php

/**
 *
 * @author pahhan
 */
interface Ds_Detail_DetailInterface //extends Spv_Widget_WidgetInterface
{
    public function setData($data);
    public function hasData();
    public function render();

    /**
     *
     * @param type $id
     */
    public function getBlock($id);

    /**
     * Value for title in head
     */
    public function getHeadTitle();

    /**
     * Value for title on page
     */
    public function getPageTitle();
}
