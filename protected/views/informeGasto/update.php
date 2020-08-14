<?php
/* @var $this InformeGastoController */
/* @var $model InformeGasto */

$this->breadcrumbs=array(
	'Informe Gastos'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List InformeGasto', 'url'=>array('index')),
	array('label'=>'Create InformeGasto', 'url'=>array('create')),
	array('label'=>'View InformeGasto', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage InformeGasto', 'url'=>array('admin')),
);
?>

<h1>Update InformeGasto <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>