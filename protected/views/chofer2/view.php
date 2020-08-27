<?php
$this->menu=array(
	array('label'=>'Crear Chofer', 'url'=>array('create')),
	array('label'=>'Editar Chofer', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Chofer', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Administrar Choferes', 'url'=>array('admin')),
);
?>

<h1>Ver Chofer #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		'rut',
		'vigente',
	),
)); ?>
