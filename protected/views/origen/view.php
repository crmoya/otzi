<?php
$this->menu=array(
	array('label'=>'Crear Origen', 'url'=>array('create')),
	array('label'=>'Editar Origen', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Origen', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Administrar Orígenes', 'url'=>array('admin')),
);
?>

<h1>Ver Origen #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		'vigente',
	),
)); ?>
