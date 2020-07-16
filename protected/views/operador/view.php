<?php

$this->menu=array(
	array('label'=>'Crear Operador', 'url'=>array('create')),
	array('label'=>'Editar Operador', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Operador', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Administrar Operadores', 'url'=>array('admin')),
);
?>

<h1>Ver Operador #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		'rut',
		'vigente',
	),
)); ?>
