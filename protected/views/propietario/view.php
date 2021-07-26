<?php
$this->menu=array(
	array('label'=>'Crear Propietario', 'url'=>array('create')),
	array('label'=>'Editar Propietario', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Propietario', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Administrar Propietarios', 'url'=>array('admin')),
);
?>

<h1>Ver Propietario #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		'rut',
		'vigente',
	),
)); ?>
