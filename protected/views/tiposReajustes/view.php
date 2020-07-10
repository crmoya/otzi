<?php
/* @var $this TiposReajustesController */
/* @var $model TiposReajustes */

$this->breadcrumbs=array(
	'Tipos Reajustes'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List TiposReajustes', 'url'=>array('index')),
	array('label'=>'Create TiposReajustes', 'url'=>array('create')),
	array('label'=>'Update TiposReajustes', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TiposReajustes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TiposReajustes', 'url'=>array('admin')),
);
?>

<h1>View TiposReajustes #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
	),
)); ?>
