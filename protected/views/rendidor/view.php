<?php

$this->menu=array(
	array('label'=>'Crear Supervisor de Rendición', 'url'=>array('create')),
	array('label'=>'Editar Supervisor de Rendición', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Supervisor de Rendición', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Administrar Supervisor de Rendición', 'url'=>array('admin')),
);
?>

<h1>Ver Supervisor de Rendición #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		'rut',
		'vigente',
	),
)); ?>
