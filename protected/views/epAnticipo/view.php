<?php
/* @var $this EpAnticipoController */
/* @var $model EpAnticipo */

$this->breadcrumbs=array(
	'Ep Anticipos'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List EpAnticipo', 'url'=>array('index')),
	array('label'=>'Create EpAnticipo', 'url'=>array('create')),
	array('label'=>'Update EpAnticipo', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete EpAnticipo', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage EpAnticipo', 'url'=>array('admin')),
);
?>

<h1>View EpAnticipo #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'valor',
		'comentarios',
		'resoluciones_id',
	),
)); ?>
