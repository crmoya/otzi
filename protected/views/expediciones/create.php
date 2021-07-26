<?php
/* @var $this ExpedicionesController */
/* @var $model Expediciones */

$this->breadcrumbs=array(
	'Expediciones'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Expediciones', 'url'=>array('index')),
	array('label'=>'Manage Expediciones', 'url'=>array('admin')),
);
?>

<h1>Create Expediciones</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>