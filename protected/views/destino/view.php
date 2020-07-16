<?php
$this->menu=array(
	array('label'=>'Crear Destino', 'url'=>array('create')),
	array('label'=>'Editar Destino', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Destino', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Administrar Destinos', 'url'=>array('admin')),
);
?>

<h1>Ver Destino #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		'vigente',
	),
)); ?>
