<?php
/* @var $this ExpedicionesController */
/* @var $model Expediciones */

$this->breadcrumbs=array(
	'Expediciones'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Expediciones', 'url'=>array('index')),
	array('label'=>'Create Expediciones', 'url'=>array('create')),
	array('label'=>'Update Expediciones', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Expediciones', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Expediciones', 'url'=>array('admin')),
);
?>

<h1>View Expediciones #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nVueltas',
		'totalTransportado',
		'total',
		'kmRecorridos',
		'fecha',
		'vehiculo',
		'chofer',
		'propio_arrendado',
	),
)); ?>
