<?php
/* @var $this GarantiasController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Garantias',
);

$this->menu=array(
	array('label'=>'Administrar Garantias', 'url'=>array('admin')),
);
?>

<h1>Garantiases</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
