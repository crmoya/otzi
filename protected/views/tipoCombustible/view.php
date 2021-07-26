<?php
$this->menu=array(
	array('label'=>'Crear Tipo de Combustible', 'url'=>array('create')),
	array('label'=>'Editar Tipo de Combustible', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Tipo de Combustible', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Adminsitrar Tipo de Combustible', 'url'=>array('admin')),
);
?>

<h1>Ver Tipo de Combustible #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',	
		'vigente',
	),
)); ?>
