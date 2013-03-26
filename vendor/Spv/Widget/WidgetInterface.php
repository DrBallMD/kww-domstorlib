<?php
interface Spv_Widget_WidgetInterface
{
	public function render();	// Render widget object to string
	public function display();	// Display widget
	public function hide(); 	// Turns off rendering
	public function show();		// Turns on rendering
	public function toggleVisible();	// Toggle between show() and hide()
	public function isVisible();// Returns whether widget is visible
}