<?php
/* @var $this ExpedicionesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Expediciones',
);

$this->menu=array(
	array('label'=>'Create Expediciones', 'url'=>array('create')),
	array('label'=>'Manage Expediciones', 'url'=>array('admin')),
);
?>

<h1>Expediciones</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
