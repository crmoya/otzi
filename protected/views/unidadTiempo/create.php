<?php
/* @var $this UnidadTiempoController */
/* @var $model UnidadTiempo */

$this->breadcrumbs=array(
	'Unidad Tiempos'=>array('index'),
	'Crear',
);

$this->menu=array(
	array('label'=>'Administrar Unidades de Tiempo', 'url'=>array('admin')),
);
?>

<h1>Crear Unidad de Tiempo</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>