<?php
$this->breadcrumbs=array(
	'Origendestino Faenas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List OrigendestinoFaena', 'url'=>array('index')),
	array('label'=>'Create OrigendestinoFaena', 'url'=>array('create')),
	array('label'=>'Update OrigendestinoFaena', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete OrigendestinoFaena', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage OrigendestinoFaena', 'url'=>array('admin')),
);
?>

<h1>View OrigendestinoFaena #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'origen_id',
		'destino_id',
		'faena_id',
		'pu',
		'id',
	),
)); ?>
