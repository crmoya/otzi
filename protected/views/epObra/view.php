<?php
/* @var $this EpObraController */
/* @var $model EpObra */

$this->breadcrumbs=array(
	'Ep Obras'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List EpObra', 'url'=>array('index')),
	array('label'=>'Create EpObra', 'url'=>array('create')),
	array('label'=>'Update EpObra', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete EpObra', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage EpObra', 'url'=>array('admin')),
);
?>

<h1>View EpObra #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'produccion',
		'costo',
		'reajuste',
		'retencion',
		'descuento',
		'mes',
		'agno',
		'comentarios',
		'resoluciones_id',
	),
)); ?>
