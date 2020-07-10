<?php
$this->menu=array(
	array('label'=>'Crear Tipo de Garantía', 'url'=>array('create')),
	array('label'=>'Editar Tipo de Garantía', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Tipo de Garantía', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Seguro desea borrar este registro?')),
	array('label'=>'Administrar Tipos de Garantía', 'url'=>array('admin')),
);
?>

<h1>Ver Tipo de Garantía #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		),
)); ?>
