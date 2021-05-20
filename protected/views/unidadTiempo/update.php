<?php
/* @var $this UnidadTiempoController */
/* @var $model UnidadTiempo */

$this->breadcrumbs=array(
	'Unidades de Tiempo'=>array('admin'),
	$model->id=>array('view','id'=>$model->id),
	'Editar',
);

$this->menu=array(
	array('label'=>'Crear Unidad de Tiempo', 'url'=>array('create')),
	array('label'=>'Ver Unidad de iempo', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Administrar Unidades de Tiempo', 'url'=>array('admin')),
);
?>

<h1>Editar Unidad de Tiempo <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>