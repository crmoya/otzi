<?php
$this->menu=array(
	array('label'=>'Crear Institución', 'url'=>array('create')),
	array('label'=>'Editar Institución', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Institución', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Seguro desea borrar este registro?')),
	array('label'=>'Administrar Instituciones', 'url'=>array('admin')),
);
?>
<h1>Ver Institución #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		'vigente',
	),
)); ?>
