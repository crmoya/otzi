<?php
/* @var $this UnidadTiempoController */
/* @var $model UnidadTiempo */

$this->breadcrumbs=array(
	'Unidad Tiempos'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Crear Unidad de Tiempo', 'url'=>array('create')),
	array('label'=>'Editar Unidad de Tiempo', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Eliminar Unidad de Tiempo', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Administrar Unidades de Tiempo', 'url'=>array('admin')),
);
?>

<h1>Ver Unidad de Tiempo #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
	),
)); ?>
