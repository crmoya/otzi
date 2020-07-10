<?php
$this->menu=array(
	array('label'=>'Crear Objeto de Garantía', 'url'=>array('create')),
	array('label'=>'Editar Objeto de Garantía', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Objeto de Garantía', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Seguro desea borrar este registro?')),
	array('label'=>'Administrar Objetos de Garantía', 'url'=>array('admin')),
);
?>
<h1>Ver Objeto de Garantía #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'descripcion',
	),
)); ?>
