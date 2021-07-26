<?php
$this->menu=array(
	array('label'=>'Crear Centro de Gestión', 'url'=>array('create')),
	array('label'=>'Editar Centro de Gestión', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Centro de Gestión', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Administrar Centros de Gestión', 'url'=>array('admin')),
);
?>

<h1>Ver Centro de Gestión #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		'vigente',
	),
)); ?>
