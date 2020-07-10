<?php
/* @var $this EpCanjeRetencionController */
/* @var $model EpCanjeRetencion */

$this->breadcrumbs=array(
	'Ep Canje Retencions'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List EpCanjeRetencion', 'url'=>array('index')),
	array('label'=>'Create EpCanjeRetencion', 'url'=>array('create')),
	array('label'=>'Update EpCanjeRetencion', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete EpCanjeRetencion', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage EpCanjeRetencion', 'url'=>array('admin')),
);
?>

<h1>View EpCanjeRetencion #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'valor',
		'comentarios',
		'resoluciones_id',
	),
)); ?>
