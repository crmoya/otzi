<?php
/* @var $this InformeGastoController */
/* @var $model InformeGasto */

$this->breadcrumbs=array(
	'Informe Gastos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List InformeGasto', 'url'=>array('index')),
	array('label'=>'Manage InformeGasto', 'url'=>array('admin')),
);
?>

<h1>Create InformeGasto</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>