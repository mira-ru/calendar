<?php
Yii::import('zii.widgets.grid.CGridView');
class CustomGridView extends CGridView
{
	public $itemsCssClass = 'table table-striped';
	public $ajaxUpdate = false;
	public $pager = array(
		'class'=>'application.components.widgets.CustomListPager',
		'stepLinks' => 8,
	);
	public $pagerCssClass = 'dummy';
}