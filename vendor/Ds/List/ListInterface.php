<?php

/**
 *
 * @author pahhan
 */
interface Ds_List_ListInterface extends Spv_Widget_WidgetInterface
{
    public function addColumn($name, Ds_List_Column_ColumnInterface $column);
    public function getColumn($name);
    public function hasColumn($name);
    public function deleteColumn($name);
    public function getColumns();
    public function count();
    public function setData($data);
    public function renderRow($row_data, array $vars = array());
}
