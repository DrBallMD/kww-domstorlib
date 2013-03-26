<?php

/**
 *
 * @author pahhan
 */
interface Ds_List_Column_ColumnInterface extends Spv_Widget_WidgetInterface
{
    public function init(array $params = array());
    public function getList();
    public function getSortUrl();
    public function setRowData($data);
    public function getDataKey();
}
