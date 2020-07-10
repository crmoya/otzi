<?php
/* @var $this TiposReajustesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tipos Reajustes',
);

$this->menu=array(
	array('label'=>'Create TiposReajustes', 'url'=>array('create')),
	array('label'=>'Manage TiposReajustes', 'url'=>array('admin')),
);
?>

<h1>Tipos Reajustes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
